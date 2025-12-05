<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;

class FacebookWebhookController extends Controller
{
    private $_verifyToken;

    public function __construct()
    {
        $this->_verifyToken = env('FB_PAGE_VERIFY_TOKEN');
    }

    // Step 1: Facebook checks you
    public function verify(Request $request)
    {
        if (
            $request->query('hub_mode') === 'subscribe' &&
            $request->query('hub_verify_token') === $this->_verifyToken
        ) {
            return response($request->query('hub_challenge'), 200);
        }

        Log::info('Verification failed: Invalid token or mode');
        return response('Forbidden', 403);
    }

    // Step 2: Facebook sends the actual lead
    public function receive(Request $request)
    {
        Log::info('Webhook hit', $request->all());

        if (!$request->has('entry')) {
            Log::warning('No entry in payload');
            return response()->json(['status' => 'error', 'message' => 'No entry in payload'], 400);
        }

        foreach ($request->input('entry', []) as $entry) {
            if (!isset($entry['changes'])) {
                Log::warning('No changes in entry', $entry);
                continue;
            }

            foreach ($entry['changes'] as $change) {
                Log::info('Change received', $change);

                if ($change['field'] === 'leadgen') {
                    $leadId = $change['value']['lead_id'];
                    $formId = $change['value']['form_id'];

                    // Pull REAL lead data from Facebook
                    $response = Http::get("https://graph.facebook.com/v19.0/{$leadId}", [
                        'access_token' => env('FB_PAGE_ACCESS_TOKEN')
                    ]);

                    if ($response->successful()) {
                        $leadDetails = $response->json();
                        Lead::create([
                            'facebook_lead_id' => $leadId,
                            'form_id' => $formId,
                            'data' => json_encode($leadDetails['field_data'] ?? []),
                        ]);
                    } else {
                        Log::error('Failed to fetch lead details from Facebook', [
                            'lead_id' => $leadId,
                            'response' => $response->body()
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
