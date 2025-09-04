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

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (superadmin, admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    
    // Items Routes (ItemController - untuk CRUD operations)
    // Route::get('/items', [ItemController::class, 'index'])->name('items');
    // Route::get('/items/add', [ItemController::class, 'create'])->name('items.add');
    // Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    
    // Slots Routes (AdminController)
    Route::get('/slot', [SlotController::class, 'index'])->name('slot');
    Route::get('/slot/add', [SlotController::class, 'create'])->name('slot.add');
    Route::post('/slot/store', [SlotController::class, 'store'])->name('slot.store');
    Route::get('/slot/{id}/detail', [SlotController::class, 'detail'])->name('slot.detail');
    Route::get('/slot/{id}/assign-part', [SlotController::class, 'assignPart'])->name('slot.assign-part');
    Route::post('/slot/{id}/assign-part', [SlotController::class, 'storeAssignPart'])->name('slot.store-assign-part');
    
    // History Routes (AdminController)
    Route::get('/history', [AdminController::class, 'history'])->name('history');
    Route::get('/item-history', [AdminController::class, 'itemHistory'])->name('item.history.all');
    Route::get('/rack-history-all', [AdminController::class, 'rackHistoryAll'])->name('rack.history.all');
    Route::get('/slot-history', [AdminController::class, 'slotHistory'])->name('slot.history.all');
    
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

    Route::post('/item/upload-excel', [ItemController::class, 'uploadExcel'])->name('item.upload-excel');
    Route::post('/debug-upload', [ItemController::class, 'debugUpload'])->name('debug.upload');
    Route::get('/item/debug-upload', [ItemController::class, 'debugUpload'])->name('item.debug-upload');
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

    // Scan History Routes
    Route::get('/scan-history', [OperatorController::class, 'scanHistory'])->name('scan-history');
});