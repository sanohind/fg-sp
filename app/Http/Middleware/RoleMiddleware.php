<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$requiredRoles): Response
    {
        $requiredRoles = array_map('strtolower', $requiredRoles);

        // Prefer Sanctum-authenticated user
        $authUser = $request->user();
        if ($authUser) {
            // Resolve role name via relationship or direct attribute
            $roleName = null;
            if (method_exists($authUser, 'role') && $authUser->relationLoaded('role')) {
                $roleName = strtolower((string) optional($authUser->role)->role_name);
            } elseif (property_exists($authUser, 'role_name')) {
                $roleName = strtolower((string) $authUser->role_name);
            } else {
                $roleName = strtolower((string) DB::table('roles')->where('id', $authUser->role_id)->value('role_name'));
            }

            if (in_array($roleName, $requiredRoles)) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return redirect()->route('login')->with('error', 'Tidak memiliki akses.');
        }

        // Fallback: session-based user (web) - only for web requests
        if (!$request->expectsJson()) {
            try {
                $sessionUser = $request->session()->get('user');
                if ($sessionUser) {
                    $roleName = strtolower((string) ($sessionUser['role'] ?? ''));
                    if (in_array($roleName, $requiredRoles)) {
                        return $next($request);
                    }
                    return redirect()->route('login')->with('error', 'Tidak memiliki akses.');
                }
            } catch (\Exception $e) {
                // Session not available, continue to next check
            }
        }

        // Not authenticated
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return redirect()->route('login');
    }
}


