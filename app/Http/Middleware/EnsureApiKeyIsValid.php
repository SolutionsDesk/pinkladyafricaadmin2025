<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the API key from the request header. We'll name the header 'X-API-KEY'.
        $providedApiKey = $request->header('X-API-KEY');

        // 2. Get the valid API key from your environment file.
        $validApiKey = config('services.pinklady.api_key');

        // 3. Check if the provided key is missing or does not match the valid key.
        if (!$providedApiKey || $providedApiKey !== $validApiKey) {
            // If it doesn't match, block the request immediately.
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        // 4. If the keys match, allow the request to continue to the controller.
        return $next($request);
    }
}
