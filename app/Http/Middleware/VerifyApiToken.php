<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the vouchers API with a shared token sent as X-API-Token.
 *
 * With no tokens configured every request is rejected, so an unconfigured
 * deployment never exposes the endpoints.
 */
class VerifyApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $sent = (string) $request->header('X-API-Token');
        $valid = false;

        foreach ((array) config('vouchers.api_tokens') as $token) {
            // hash_equals guards against timing attacks; it needs a non-empty pair.
            if ($token !== '' && $sent !== '' && hash_equals((string) $token, $sent)) {
                $valid = true;
                break;
            }
        }

        if (! $valid) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
