<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;

// Public API Auth
Route::post('/auth/login', [AuthApiController::class, 'login']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/auth/me', [AuthApiController::class, 'me']);
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/ping', function () {
            return ['ok' => true, 'scope' => 'admin'];
        });
    });

    Route::middleware('role:operator')->group(function () {
        Route::get('/operator/ping', function () {
            return ['ok' => true, 'scope' => 'operator'];
        });
    });
});
