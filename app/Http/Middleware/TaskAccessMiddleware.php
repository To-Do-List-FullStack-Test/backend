<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TaskAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Token required.',
                'error' => 'MISSING_TOKEN'
            ], 401);
        }

        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized. Bearer token required.',
                'error' => 'MISSING_BEARER_TOKEN'
            ], 401);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'Invalid token.',
                    'error' => 'INVALID_TOKEN'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token validation failed.',
                'error' => 'TOKEN_VALIDATION_FAILED'
            ], 401);
        }

        return $next($request);
    }
}