<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverApiController;

// Pintu Masuk Umum (Login)
Route::post('/login', [AuthController::class, 'login']);

// Area yang Wajib Login (Harus bawa Token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Rute Logout (Bisa dipakai Supir & Orang Tua)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cek profil user yang sedang login
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    });

    // ==========================================
    // JALUR KHUSUS APLIKASI SUPIR
    // ==========================================
    Route::prefix('driver')->group(function () {
        
        Route::get('/schedules', [DriverApiController::class, 'getSchedules']);

        Route::get('/schedules/{id}/students', [DriverApiController::class, 'getScheduleStudents']);
        
    }); // <-- Tadi ada kelebihan penutup di bawah baris ini

    // ==========================================
    // JALUR KHUSUS APLIKASI ORANG TUA
    // ==========================================
    Route::prefix('parent')->group(function () {
        // Nanti kita tambahkan API untuk Orang Tua di sini
        // Contoh: Route::get('/anak', [ParentApiController::class, 'getAnak']);
    });

});