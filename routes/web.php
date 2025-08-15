<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    // Handle login logic here
    return redirect()->route('admin.home');
})->name('login.post');

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
    
    Route::get('/items', function () {
        return view('admin.items');
    })->name('items');
    
    Route::get('/history', function () {
        return view('admin.history');
    })->name('history');
    
    Route::get('/user', function () {
        return view('admin.user');
    })->name('user');
});
