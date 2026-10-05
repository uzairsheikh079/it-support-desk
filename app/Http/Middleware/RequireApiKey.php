<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $configuredKey = config('supportdesk.api_key');
        $providedKey = $request->header('X-API-Key');

        if (! is_string($configuredKey) || $configuredKey === '') {
            return response()->json(['message' => 'API access is not configured.'], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        if (! is_string($providedKey) || ! hash_equals($configuredKey, $providedKey)) {
            return response()->json(['message' => 'Invalid API key.'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
