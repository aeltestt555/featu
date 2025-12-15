<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;

class FacebookWebhookController extends Controller
{
    private string $_verifyToken;

    public function __construct()
    {
        $this->_verifyToken = env('FB_PAGE_VERIFY_TOKEN');
    }

    /**
     * Step 1: Facebook webhook verification
     * Handles GET request from Facebook to verify webhook.
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        Log::info('Webhook verification attempt', [
            'hub_mode' => $mode,
            'hub_verify_token' => $token
        ]);

        if ($mode === 'subscribe' && $token === $this->_verifyToken) {
            Log::info('Webhook verified successfully', ['challenge' => $challenge]);
            return response($challenge, 200);
        }

        Log::warning('Webhook verification failed');
        return response('Forbidden', 403);
    }

    /**
     * Step 2: Handle POST requests from Facebook
     * Receives leads and stores them in database
     */
    public function receive(Request $request)
    {
        Log::info('Webhook POST received', ['payload' => $request->all()]);

        // Validate the payload
        $entries = $request->input('entry', []);
        if (empty($entries)) {
            Log::warning('Webhook payload missing entry');
            return response()->json(['status' => 'error', 'message' => 'No entry in payload'], 400);
        }

        foreach ($entries as $entry) {
            $changes = $entry['changes'] ?? [];
            if (empty($changes)) {
                Log::warning('Entry missing changes', ['entry' => $entry]);
                continue;
            }

            foreach ($changes as $change) {
                Log::info('Processing change', $change);

                if (($change['field'] ?? '') === 'leadgen') {
                    // v24 uses 'leadgen_id' instead of 'lead_id'
                    $leadId = $change['value']['leadgen_id'] ?? null;
                    $formId = $change['value']['form_id'] ?? null;

                    if (!$leadId || !$formId) {
                        Log::warning('Missing leadgen_id or form_id', ['change' => $change]);
                        continue;
                    }

                    try {
                        $pageToken = env('LONG_LIVED_FB_PAGE_ACCESS_TOKEN');

                        // Fetch the full lead data
                        $response = Http::timeout(10)->get("https://graph.facebook.com/v24.0/{$leadId}", [
                            'access_token' => $pageToken
                        ]);

                        if ($response->successful()) {
                            $leadDetails = $response->json();

                            Lead::updateOrCreate(
                                ['facebook_lead_id' => $leadId],
                                [
                                    'form_id' => $formId,
                                    'data' => json_encode($leadDetails['field_data'] ?? []),
                                ]
                            );

                            Log::info('Lead saved successfully', ['lead_id' => $leadId]);
                        } else {
                            Log::error('Failed to fetch lead details', [
                                'lead_id' => $leadId,
                                'response' => $response->body()
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Exception while fetching lead', [
                            'lead_id' => $leadId,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        // Always respond 200 OK
        return response()->json(['status' => 'ok'], 200);
    }
}
