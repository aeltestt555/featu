<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    // 🔐 VERIFY WEBHOOK
    public function verify(Request $request)
    {
        $verifyToken = env('WHATSAPP_VERIFY_TOKEN');

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $request->get('hub_verify_token') === $verifyToken
        ) {
            return response($request->get('hub_challenge'), 200);
        }

        return response('Unauthorized', 403);
    }

    // 📩 HANDLE EVENTS
    public function handle(Request $request)
    {
        Log::info('WhatsApp Webhook:', $request->all());
        return response()->json(['status' => 'received']);
    }
}
