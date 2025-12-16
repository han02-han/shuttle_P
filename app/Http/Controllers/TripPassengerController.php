<?php

namespace App\Http\Controllers;

use App\Models\TripPassenger;
use App\Models\Trip;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TripPassengerController extends Controller
{
    /**
     * 1. Halaman Proses Perjalanan
     */
    public function process($tripId)
    {
        // Pastikan relasi student.parent dimuat untuk info Wali Murid di Modal
        $trip = Trip::with([
            'passengers.student.parent',
            'passengers.student.complex',
            'route', 
            'shuttle'
        ])->findOrFail($tripId);

        if (Auth::user()->id != $trip->driver_id) {
            return redirect()->route('driver.dashboard')->with('error', 'Akses ditolak.');
        }

        if ($trip->status == 'finished') {
            return redirect()->route('driver.dashboard')->with('success', 'Perjalanan ini sudah selesai.');
        }

        if ($trip->status == 'scheduled') {
            $trip->update(['status' => 'active']);
        }

        return view('driver_dashboard.perjalanan', [
            'trip' => $trip,
            'passengers' => $trip->passengers
        ]);
    }

    /**
     * [BARU] LOGIKA WAITING (Driver Sampai / Menunggu)
     */
    public function waiting($id)
    {
        $passenger = TripPassenger::findOrFail($id);

        // Hanya ubah jika status sebelumnya masih 'pending'
        if ($passenger->status == 'pending') {
            $passenger->update([
                'status' => 'waiting'
            ]);
        }

        return back()->with('success', 'Status diupdate: Menunggu di depan rumah.');
    }

    /**
     * 2. LOGIKA JEMPUT (NAIK KE MOBIL)
     */
    public function pickup($id)
    {
        $passenger = TripPassenger::findOrFail($id);
        
        $passenger->update([
            'status' => 'picked_up',
            'picked_at' => Carbon::now()
        ]);
        
        if ($passenger->trip && $passenger->trip->status == 'scheduled') {
            $passenger->trip->update(['status' => 'active']);
        }

        return back()->with('success', 'Siswa berhasil naik/dijemput.');
    }

    /**
     * 3. LOGIKA SKIP (LEWATI)
     */
    public function skip($id)
    {
        $passenger = TripPassenger::findOrFail($id);
        
        $passenger->update([
            'status' => 'skipped', 
        ]);

        return back()->with('warning', 'Penjemputan siswa dilewati.');
    }

    /**
     * 4. LOGIKA TURUN (SAMPAI RUMAH)
     */
    public function dropoff($id)
    {
        $passenger = TripPassenger::findOrFail($id);
        
        $passenger->update([
            'status' => 'dropped_off',
            'dropped_at' => Carbon::now()
        ]);

        return back()->with('success', 'Siswa telah sampai di rumah.');
    }

    /**
     * 5. SELESAI SESI (FINISH)
     */
    public function finishTrip($tripId)
    {
        $trip = Trip::findOrFail($tripId);
        
        if ($trip->type == 'pickup') { 
            // Jika penjemputan pagi selesai, otomatis set dropoff_time buat yang sudah dijemput?
            // Biasanya trip pagi selesai saat sampai sekolah.
            // Di sini kita anggap semua yang picked_up jadi dropped_off (turun di sekolah)
            $trip->passengers()->where('status', 'picked_up')->update([
                'status' => 'dropped_off',
                'dropped_at' => Carbon::now()
            ]);
        }

        $trip->update([
            'status' => 'finished'
        ]);
        
        return redirect()->route('driver.dashboard')
            ->with('success', 'Perjalanan selesai. Terima kasih!');
    }
}