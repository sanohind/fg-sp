<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get token from Authorization header
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated - No token provided'
            ], 401);
        }

        // Find the token in database
        $accessToken = PersonalAccessToken::findToken($token);
        
        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated - Invalid token'
            ], 401);
        }

        // Get the user
        $user = $accessToken->tokenable;
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated - User not found'
            ], 401);
        }

        // Manually set the user on the request (bypass Laravel's auth system)
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}