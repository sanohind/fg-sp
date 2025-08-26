<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OperatorController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Test route untuk debugging
Route::get('/test-session', function () {
    dd(session()->all());
});

// Debug route untuk testing role dan user data
Route::get('/debug-user', function () {
    if (session('user')) {
        echo "Session User: ";
        dd(session('user'));
    } elseif (auth()->check()) {
        echo "Auth User: ";
        $user = auth()->user();
        dd([
            'user' => $user->toArray(),
            'role' => $user->role ? $user->role->toArray() : null,
            'role_id' => $user->role_id
        ]);
    } else {
        echo "No user found";
        dd(session()->all());
    }
});

// Debug route untuk testing rack dan slots data
Route::get('/debug-rack', function () {
    $racks = \App\Models\Rack::withCount([
        'slots as slots_count',
        'slots as assigned_slots_count' => function($query) {
            $query->whereNotNull('item_id');
        }
    ])->get();
    
    echo "Rack Data: ";
    dd($racks->toArray());
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (superadmin, admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    
    // Items Routes (AdminController)
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::get('/items/add', [AdminController::class, 'addItem'])->name('items.add');
    Route::post('/items/store', [AdminController::class, 'storeItem'])->name('items.store');
    
    // Slots Routes (AdminController)
    Route::get('/slot', [AdminController::class, 'slot'])->name('slot');
    Route::get('/slot/add', [AdminController::class, 'addSlot'])->name('slot.add');
    Route::post('/slot/store', [AdminController::class, 'storeSlot'])->name('slot.store');
    Route::get('/slot/{id}/detail', [AdminController::class, 'slotDetail'])->name('slot.detail');
    Route::get('/slot/{id}/assign-part', [AdminController::class, 'assignPart'])->name('slot.assign-part');
    Route::post('/slot/{id}/assign-part', [AdminController::class, 'storeAssignPart'])->name('slot.store-assign-part');
    
    // History Routes (AdminController)
    Route::get('/history', [AdminController::class, 'history'])->name('history');
    
    // Rack Routes
    Route::resource('rack', RackController::class);
    Route::get('/rack/{id}/history', [RackController::class, 'history'])->name('rack.history');
    
    // Slot Routes (SlotController - untuk CRUD operations)
    Route::resource('slots', SlotController::class);
    Route::get('/slots/{id}/assign-part', [SlotController::class, 'assignPart'])->name('slots.assign-part');
    Route::post('/slots/{id}/assign-part', [SlotController::class, 'storeAssignPart'])->name('slots.store-assign-part');
    Route::get('/slots/{id}/change-part', [SlotController::class, 'changePart'])->name('slots.change-part');
    Route::post('/slots/{id}/change-part', [SlotController::class, 'storeChangePart'])->name('slots.store-change-part');
    Route::get('/slots/{id}/detail', [SlotController::class, 'detail'])->name('slots.detail');
    Route::get('/slots/{id}/history', [SlotController::class, 'history'])->name('slots.history');
    
    // Item Routes (ItemController - untuk CRUD operations)
    Route::resource('item', ItemController::class);
    Route::get('/item/{id}/history', [ItemController::class, 'history'])->name('item.history');
    
    // History Routes (HistoryController)
    Route::get('/history/index', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    Route::get('/history/export', [HistoryController::class, 'export'])->name('history.export');
});

// Superadmin only routes - User Management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('user', UserController::class);
});

// Operator Routes
Route::prefix('operator')->name('operator.')->middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/', [OperatorController::class, 'index'])->name('index');
    
    // Posting Routes
    Route::get('/posting', [OperatorController::class, 'posting'])->name('posting');
    Route::post('/posting/scan-slotname', [OperatorController::class, 'scanSlotnameForPosting'])->name('posting.scan-slotname');
    Route::post('/posting/store-by-erp', [OperatorController::class, 'storeByErp'])->name('posting.store-by-erp');
    Route::post('/posting/scan-slot', [OperatorController::class, 'scanSlotForPosting'])->name('posting.scan-slot');
    Route::post('/posting/scan-box', [OperatorController::class, 'scanBoxForPosting'])->name('posting.scan-box');
    
    // Pulling Routes
    Route::get('/pulling', [OperatorController::class, 'pulling'])->name('pulling');
    Route::post('/pulling/scan-slot', [OperatorController::class, 'scanSlotForPulling'])->name('pulling.scan-slot');
    Route::post('/pulling/scan-box', [OperatorController::class, 'scanBoxForPulling'])->name('pulling.scan-box');
    
    // Utility Routes
    Route::get('/search-item', [OperatorController::class, 'searchItem'])->name('search-item');
    Route::get('/slot/{slotName}/info', [OperatorController::class, 'getSlotInfo'])->name('slot.info');
    Route::get('/slot/{slotName}/lot-numbers', [OperatorController::class, 'getSlotLotNumbers'])->name('slot.lot-numbers');
});