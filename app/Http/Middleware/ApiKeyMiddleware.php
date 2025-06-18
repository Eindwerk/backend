<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiKey;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('filament/*')) {
            return $next($request);
        }

        $key = $request->header('X-API-KEY');

        if (!$key || !ApiKey::where('key', $key)->exists()) {
            return response()->json(['message' => 'Unauthorized. Invalid API Key.'], 401);
        }

        return $next($request);
    }
}
