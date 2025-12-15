<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/webhook/facebook-leads',
        '/webhook/facebook-leads/*',
    ];
    protected function tokensMatch($request)
    {
        Log::info('CSRF Middleware executed for: ' . $request->path());

        return parent::tokensMatch($request);
    }
}
