<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


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
        
        Route::get('/schedules', function (Request $request) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    [
                        'id' => 1, 
                        'nama_rute' => 'Jemput Pagi - Perumahan Anggrek', 
                        'jam' => '06:30 WIB'
                    ],
                    [
                        'id' => 2, 
                        'nama_rute' => 'Jemput Pagi - Perumahan Melati', 
                        'jam' => '07:15 WIB'
                    ],
                ]
            ]);
        });
    });

    // ==========================================
    // JALUR KHUSUS APLIKASI ORANG TUA
    // ==========================================
    Route::prefix('parent')->group(function () {
        // Nanti kita tambahkan API untuk Orang Tua di sini
        // Contoh: Route::get('/anak', [ParentApiController::class, 'getAnak']);
    });

});