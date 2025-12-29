<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendTestMessage($to)
    {
        return Http::withToken(env('WHATSAPP_TOKEN'))
            ->post(env('WHATSAPP_API') . '/' . env('WHATSAPP_PHONE_NUMBER_ID') . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => 'Hello 👋 This is a TEST message from Laravel WhatsApp API'
                ]
            ]);
    }
}
