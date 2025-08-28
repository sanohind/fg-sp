<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\OperatorApiController;

// Public API Auth
Route::post('/auth/login', [AuthApiController::class, 'login']);

// Protected API routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/auth/me', [AuthApiController::class, 'me']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);

        // Test route untuk debugging
        Route::get('/test/auth', function (Request $request) {
            $user = $request->user();
            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'role_id' => $user->role_id,
                    'has_role_relationship' => method_exists($user, 'role'),
                    'role_loaded' => $user->relationLoaded('role'),
                ]
            ]);
        });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/ping', function () {
            return ['ok' => true, 'scope' => 'admin'];
        });
    });

    Route::middleware('api.role:operator')->group(function () {
        Route::get('/operator/ping', function () {
            return ['ok' => true, 'scope' => 'operator'];
        });

        // Test route untuk debugging role operator
        Route::get('/operator/test', function (Request $request) {
            $user = $request->user();
            $role = $user->role;
            
            return response()->json([
                'success' => true,
                'message' => 'Operator role check successful',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'role_id' => $user->role_id,
                    'role_name' => $role ? $role->role_name : 'No role found',
                    'role_loaded' => $user->relationLoaded('role'),
                ]
            ]);
        });

        // Operator Dashboard
        Route::get('/operator/dashboard', [OperatorApiController::class, 'dashboard']);

        // Store Operations
        Route::post('/operator/store/scan-slot', [OperatorApiController::class, 'scanSlotForStore']);
        Route::post('/operator/store/by-erp', [OperatorApiController::class, 'storeByErp']);

        // Pull Operations
        Route::post('/operator/pull/scan-slot', [OperatorApiController::class, 'scanSlotForPull']);
        Route::post('/operator/pull/by-lot', [OperatorApiController::class, 'pullByLotNumber']);

        // Slot Information
        Route::get('/operator/slot/{slotName}', [OperatorApiController::class, 'getSlotInfo']);

        // Search and Utilities
        Route::get('/operator/search/items', [OperatorApiController::class, 'searchItems']);
        Route::get('/operator/activities', [OperatorApiController::class, 'getActivityHistory']);
    });
});
