<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Check if user has role relationship
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        if (!$user->role) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak memiliki role',
                'debug' => [
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'role_loaded' => $user->relationLoaded('role'),
                ]
            ], 403);
        }

        $userRole = strtolower($user->role->role_name);
        $requiredRole = strtolower($role);

        // Check role hierarchy
        $allowedRoles = $this->getAllowedRoles($requiredRole);
        
        if (!in_array($userRole, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Required role: ' . $role,
                'user_role' => $user->role->role_name,
                'required_role' => $role,
                'debug' => [
                    'user_role_lower' => $userRole,
                    'required_role_lower' => $requiredRole,
                    'allowed_roles' => $allowedRoles,
                ]
            ], 403);
        }

        return $next($request);
    }

    /**
     * Get allowed roles based on required role
     */
    private function getAllowedRoles(string $requiredRole): array
    {
        $roleHierarchy = [
            'operator' => ['operator', 'admin', 'super admin'],
            'admin' => ['admin', 'super admin'],
            'super admin' => ['super admin'],
        ];

        return $roleHierarchy[$requiredRole] ?? [$requiredRole];
    }
}
