<?php

use App\Http\Controllers\Api\RfidLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\BorrowedItemsController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RfidScanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

// Default route (Login Page)
Route::get('/', function () {
    return view('auth.login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Asset Management
    Route::resource('inventory', AssetController::class);

    // Laboratory Management
    Route::resource('laboratories', LaboratoryController::class);

    // Tracking
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::post('/tracking/scan', [TrackingController::class, 'handleRFIDScan'])->name('tracking.scan');
    Route::get('/tracking/history', [TrackingController::class, 'getMovementHistory'])->name('tracking.history');
    Route::get('/tracking/status', [TrackingController::class, 'getCurrentStatus'])->name('tracking.status');
    Route::get('/tracking/search', [TrackingController::class, 'search'])->name('tracking.search');


    // Borrowed Items
    Route::resource('borrowed_items', BorrowedItemsController::class);

    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // User Management
    Route::resource('users', UserController::class);

    // Reports
    Route::resource('reports', ReportController::class);
    Route::get('/reports/{report}/generate', [ReportController::class, 'generateReport'])->name('reports.generate');
    Route::post('/reports/{report}/schedule', [ReportController::class, 'scheduleReport'])->name('reports.schedule');

    // Mark notification as read
    Route::post('/notifications/mark-as-read/{id}', function($id) {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'not found'], 404);
    });
    // 1) Receive scanned RFID data from ESP32/Arduino
    // Route::post('rfid-scans', [RfidScanController::class, 'store']);

    // 2) (Optional) View recent scans in browser

    // 3) Internal: save logs used by your app
    Route::post('rfid-log', [RfidLogController::class, 'store']);

    // Pdf
    Route::get('/generate-report/{id}', [PdfController::class, 'generateReport'])->name('generate-report');
    
});

Route::get('rfid-scans', [RfidScanController::class, 'index']);





