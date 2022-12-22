<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'transactions/generate-invoice',
        'transactions/generate-return-amount'
    ];

    protected function tokensMatch($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (!$token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header, static::serialized());
        }

        $tokensMatch = hash_equals($request->session()->token(), $token);

        if ($tokensMatch) $request->session()->regenerateToken();

        return is_string($request->session()->token()) &&
            is_string($token) &&
            $tokensMatch;
    }
}
