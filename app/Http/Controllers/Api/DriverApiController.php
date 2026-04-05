<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule; 
use App\Models\Route; 
use App\Models\Trip; 
use App\Models\TripPassenger; // <--- TAMBAHAN BARU
use Carbon\Carbon;   

class DriverApiController extends Controller
{
    // ====================================================
    // FUNGSI 1: MENGAMBIL DAFTAR JADWAL (YANG SEBELUMNYA)
    // ====================================================
    public function getSchedules(Request $request)
    {
        $user = $request->user();
        $todayEnglish = Carbon::now()->format('l');
        $todayDate = Carbon::now()->format('Y-m-d');

        $rawSchedules = Schedule::with(['route', 'shuttle'])
                        ->where('driver_id', $user->id)
                        ->where('day_of_week', $todayEnglish)
                        ->get();

        $tasks = [];

        foreach ($rawSchedules as $sched) {
            if ($sched->pickup_time) {
                $trip = Trip::where('driver_id', $user->id)->where('date', $todayDate)
                            ->where('type', 'pickup')->where('route_id', $sched->route_id)->first();
                $tasks[] = [
                    'id' => $sched->id, 'type' => 'pickup', 'label' => 'Jemput Pagi',
                    'time' => $sched->pickup_time, 'route_name' => $sched->route->name ?? 'Tidak Diketahui',
                    'shuttle_name' => $sched->shuttle->name ?? 'Tidak Diketahui',
                    'trip_status' => $trip ? $trip->status : 'scheduled', 'trip_id' => $trip ? $trip->id : null,
                ];
            }
            if ($sched->dropoff_time) {
                $trip = Trip::where('driver_id', $user->id)->where('date', $todayDate)
                            ->where('type', 'dropoff')->where('route_id', $sched->route_id)->first();
                $tasks[] = [
                    'id' => $sched->id, 'type' => 'dropoff', 'label' => 'Antar Sore',
                    'time' => $sched->dropoff_time, 'route_name' => $sched->route->name ?? 'Tidak Diketahui',
                    'shuttle_name' => $sched->shuttle->name ?? 'Tidak Diketahui',
                    'trip_status' => $trip ? $trip->status : 'scheduled', 'trip_id' => $trip ? $trip->id : null,
                ];
            }
        }

        usort($tasks, function ($a, $b) { return strtotime($a['time']) - strtotime($b['time']); });

        return response()->json([ 'status' => 'success', 'data' => $tasks ]);
    }

    // ====================================================
    // FUNGSI 2: MENGAMBIL DAFTAR ANAK (YANG BARU)
    // ====================================================
    public function getScheduleStudents(Request $request, $id)
    {
        $user = $request->user();
        $type = $request->query('type', 'pickup'); // Menangkap jenis rute: pickup / dropoff
        $todayDate = Carbon::now()->format('Y-m-d');

        // 1. Cari jadwal beserta data anak-anaknya
        $schedule = Schedule::with(['route', 'students.complex'])->find($id);

        if (!$schedule) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        // 2. Cek apakah perjalanan (Trip) hari ini sudah dimulai
        $trip = Trip::where('driver_id', $user->id)
                    ->where('date', $todayDate)
                    ->where('type', $type)
                    ->where('route_id', $schedule->route_id)
                    ->first();

        $passengers = [];

        // 3. JIKA SUDAH JALAN: Ambil status dari tabel trip_passengers
        if ($trip) {
            $tripPassengers = TripPassenger::with('student.complex')
                                ->where('trip_id', $trip->id)
                                ->get();

            foreach ($tripPassengers as $tp) {
                $passengers[] = [
                    'id' => $tp->student->id ?? 0,
                    'trip_passenger_id' => $tp->id,
                    'name' => $tp->student->name ?? 'Nama Tidak Diketahui',
                    'complex' => $tp->student->complex->name ?? '-',
                    'status' => $tp->status, // waiting, picked_up, dll
                ];
            }
        } 
        // 4. JIKA BELUM JALAN: Ambil daftar nama anak dari jadwal dasar
        else {
            foreach ($schedule->students as $student) {
                $passengers[] = [
                    'id' => $student->id,
                    'trip_passenger_id' => null,
                    'name' => $student->name,
                    'complex' => $student->complex->name ?? '-',
                    'status' => 'Belum Mulai',
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'schedule_id' => $schedule->id,
                'route_name' => $schedule->route->name ?? 'Rute',
                'type' => $type,
                'trip_id' => $trip ? $trip->id : null,
                'trip_status' => $trip ? $trip->status : 'scheduled',
                'passengers' => $passengers
            ]
        ]);
    }
}