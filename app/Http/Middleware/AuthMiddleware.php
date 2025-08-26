<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via session
        if ($request->session()->has('user')) {
            return $next($request);
        }

        // Not authenticated
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
}





