<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('home');
    
    Route::get('/rack', function () {
        return view('admin.rack');
    })->name('rack');
    
    Route::get('/rack/add', function () {
        return view('admin.add-rack');
    })->name('rack.add');

    Route::get('/slot', function () {
        return view('admin.slot');
    })->name('slot');
    
    Route::get('/slot/add', function () {
        return view('admin.add-slot');
    })->name('slot.add');
    
    Route::get('/slot/assign-part', function () {
        return view('admin.assign-part');
    })->name('slot.assign-part');
    
    Route::get('/slot/detail', function () {
        return view('admin.slot-detail');
    })->name('slot.detail');
    
    Route::get('/items', function () {
        return view('admin.items');
    })->name('items');
    
    Route::get('/items/add', function () {
        return view('admin.add-part');
    })->name('items.add');
    
    Route::get('/history', function () {
        return view('admin.history');
    })->name('history');
    
    Route::get('/user', function () {
        return view('admin.user');
    })->name('user');
    
    Route::get('/user/add', function () {
        return view('admin.add-user');
    })->name('user.add');
});

// Operator Routes
Route::prefix('operator')->name('operator.')->group(function () {
Route::get('/menu', function () {
    return view('operator.index');
})->name('menu');

// Route untuk halaman posting F/G
Route::get('/posting', function () {
    return view('operator.posting');
})->name('posting');

// Route untuk halaman pulling F/G
Route::get('/pulling', function () {
    return view('operator.pulling');
})->name('pulling');
// Tambahkan route operator lain di sini jika diperlukan
});