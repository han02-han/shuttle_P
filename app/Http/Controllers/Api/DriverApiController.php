<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule; // Memanggil model Jadwal
use App\Models\Route;    // Memanggil model Rute

class DriverApiController extends Controller
{
    public function getSchedules(Request $request)
    {
        // 1. Ambil ID Supir yang sedang login di HP
        $driverId = $request->user()->id;

        // 2. Cari jadwal di database yang driver_id nya cocok dengan supir ini
        // (Wajib pakai with('route') jika tabel Schedule terelasi dengan tabel Route)
        $schedules = Schedule::with('route')->where('driver_id', $driverId)->get();

        // 3. Ubah format datanya agar sesuai dengan yang diminta Flutter tadi
        $formattedData = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                // Catatan: Sesuaikan 'name' dan 'departure_time' dengan nama kolom asli di database Anda
                'nama_rute' => $schedule->route ? $schedule->route->name : 'Rute Tidak Diketahui', 
                'jam' => $schedule->departure_time ?? '06:00 WIB', 
            ];
        });

        // 4. Kirim kembali ke HP
        return response()->json([
            'status' => 'success',
            'data' => $formattedData
        ]);
    }
}