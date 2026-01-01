<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Shuttle;
use App\Models\Route;
use App\Models\Trip; 
use App\Models\Schedule; 
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik Umum
        $totalStudents = Student::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalRoutes = Route::count();
        $activeShuttles = Shuttle::where('status', 'available')->count();

        // 2. Ambil JADWAL HARI INI
        $todayName = Carbon::now()->format('l'); // Ex: Monday, Tuesday...

        $todaysSchedules = Schedule::with(['driver', 'route.complexes', 'shuttle'])
                        ->withCount('students') // Hitung jumlah siswa
                        ->where('day_of_week', $todayName)
                        ->orderBy('pickup_time', 'asc')
                        ->get();

        // 3. Ambil TRIP HARI INI (Ambil terpisah untuk menghindari error relasi)
        $todaysTrips = Trip::withCount('passengers')
                        ->whereDate('date', Carbon::today())
                        ->get();

        return view('admin_dashboard.index', compact(
            'totalStudents', 
            'totalDrivers', 
            'totalRoutes', 
            'activeShuttles',
            'todaysSchedules',
            'todaysTrips' // Kirim data trip terpisah
        ));
    }
}