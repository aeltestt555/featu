<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use GuzzleHttp\Client; // for server-side events (optional)
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function show()
    {
        $fbPixelId = config('services.facebook.pixel_id') ?? env('FB_PIXEL_ID');
        return view('landing', compact('fbPixelId'));
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:50',
        ]);

        $lead = Lead::create($data + ['ip' => $request->ip()]);

        // Optional: send server-side event to Facebook Conversions API
        if (env('FB_ACCESS_TOKEN') && env('FB_PIXEL_ID')) {
            try {
                $this->sendFbServerEvent($lead);
            } catch (\Exception $e) {
                // Log but don't break UX
                Log::error('FB Conversions API error: ' . $e->getMessage());
            }
        }

        return redirect()->route('landing.thankyou');
    }

    public function thankyou()
    {
        return view('thankyou');
    }

    protected function sendFbServerEvent(Lead $lead)
    {
        $pixelId = env('FB_PIXEL_ID');
        $accessToken = env('FB_ACCESS_TOKEN');
        $client = new Client(['base_uri' => 'https://graph.facebook.com/']);

        $event = [
            'data' => [[
                'event_name' => 'Lead',
                'event_time' => time(),
                'user_data' => [
                    'em' => hash('sha256', strtolower(trim($lead->email))),
                    'client_ip_address' => $lead->ip,
                ],
                'custom_data' => [
                    'lead_id' => $lead->id,
                ],
            ]],
            'access_token' => $accessToken,
        ];

        $response = $client->post("v14.0/{$pixelId}/events", [
            'json' => $event,
            'timeout' => 5,
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
