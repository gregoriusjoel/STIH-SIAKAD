<?php

use App\Http\Controllers\Api\JadwalApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Jadwal Admin Approval API
Route::prefix('jadwal')->group(function () {
    // Get pending schedules
    Route::get('/pending', [JadwalApiController::class, 'pending']);
    
    // Get approved schedules (waiting for room)
    Route::get('/approved', [JadwalApiController::class, 'approved']);
    
    // Get active schedules
    Route::get('/active', [JadwalApiController::class, 'active']);
    
    // Approve a schedule
    Route::post('/{id}/approve', [JadwalApiController::class, 'approve']);
    
    // Reject a schedule
    Route::post('/{id}/reject', [JadwalApiController::class, 'reject']);
    
    // Assign room to approved schedule
    Route::post('/{id}/assign-room', [JadwalApiController::class, 'assignRoom']);
});
