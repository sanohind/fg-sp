<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class ApiRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$requiredRoles): Response
    {
        $requiredRoles = array_map('strtolower', $requiredRoles);

        // Get authenticated user from Sanctum
        $authUser = $request->user();
        
        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login first.',
                'error_code' => 'UNAUTHENTICATED'
            ], 401);
        }

        // Resolve role name via relationship or direct attribute
        $roleName = null;
        
        try {
            if (method_exists($authUser, 'role') && $authUser->relationLoaded('role')) {
                $roleName = strtolower((string) optional($authUser->role)->role_name);
            } elseif (property_exists($authUser, 'role_name')) {
                $roleName = strtolower((string) $authUser->role_name);
            } else {
                // Query database for role
                $roleName = strtolower((string) DB::table('roles')
                    ->where('id', $authUser->role_id)
                    ->value('role_name'));
            }

            if (!$roleName) {
                return response()->json([
                    'success' => false,
                    'message' => 'User role not found.',
                    'error_code' => 'ROLE_NOT_FOUND'
                ], 403);
            }

            // Check if user has required role
            if (in_array($roleName, $requiredRoles)) {
                return $next($request);
            }

            return response()->json([
                'success' => false,
                'message' => 'Access denied. Insufficient permissions.',
                'error_code' => 'INSUFFICIENT_PERMISSIONS',
                'required_roles' => $requiredRoles,
                'user_role' => $roleName
            ], 403);

        } catch (\Exception $e) {
            \Log::error('ApiRoleMiddleware error', [
                'user_id' => $authUser->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error while checking permissions.',
                'error_code' => 'ROLE_CHECK_ERROR'
            ], 500);
        }
    }
}
