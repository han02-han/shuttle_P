<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use App\Models\Student;
use App\Models\TripPassenger;
use App\Models\Trip;
use App\Models\Announcement; 
use Carbon\Carbon;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::user();
        if ($parent->role !== 'parent') return redirect('/')->with('error', 'Akses khusus Wali Murid.');

        // --- AMBIL PENGUMUMAN ---
        $announcements = Announcement::where('is_active', true)
                            ->whereIn('target_role', ['all', 'parent'])
                            ->whereDate('created_at', Carbon::today())
                            ->latest()
                            ->get();

        // --- LOGIKA DATA SISWA & TRIP ---
        $students = Student::where('parent_id', $parent->id)->get();
        $today = Carbon::today();
        $dayName = $today->format('l'); // Mengambil nama hari (Monday, Tuesday, dst)

        foreach ($students as $student) {
            // 1. Cek Trip Pagi (Real-time)
            $student->trip_pagi = TripPassenger::where('student_id', $student->id)
                ->whereHas('trip', function($q) use ($today) {
                    $q->where('type', 'pickup')
                      ->whereDate('date', $today)
                      ->whereIn('status', ['active', 'finished', 'scheduled']);
                })
                ->with(['trip.driver', 'trip.shuttle', 'trip.route'])
                ->latest()
                ->first();

            // JIKA TIDAK ADA TRIP PAGI -> AMBIL JADWAL (SCHEDULE)
            if (!$student->trip_pagi) {
                $student->schedule_pagi = $student->schedules()
                    ->where('day_of_week', $dayName)
                    ->whereNotNull('pickup_time') // Pastikan ada jam jemput
                    ->with(['driver', 'shuttle', 'route'])
                    ->first();
            }

            // 2. Cek Trip Sore (Real-time)
            $student->trip_sore = TripPassenger::where('student_id', $student->id)
                ->whereHas('trip', function($q) use ($today) {
                    $q->where('type', 'dropoff')
                      ->whereDate('date', $today)
                      ->whereIn('status', ['active', 'finished', 'scheduled']);
                })
                ->with(['trip.driver', 'trip.shuttle', 'trip.route'])
                ->latest()
                ->first();

            // JIKA TIDAK ADA TRIP SORE -> AMBIL JADWAL (SCHEDULE)
            if (!$student->trip_sore) {
                $student->schedule_sore = $student->schedules()
                    ->where('day_of_week', $dayName)
                    ->whereNotNull('dropoff_time') // Pastikan ada jam antar
                    ->with(['driver', 'shuttle', 'route'])
                    ->first();
            }
        }

        return view('parent_dashboard.index', compact('students', 'announcements'));
    }

    // ... (SISA METHOD LAIN TETAP SAMA SEPERTI FILE ASLI) ...
    
    public function ajaxStatus($studentId)
    {
        $parent = Auth::user();
        $student = Student::where('id', $studentId)->where('parent_id', $parent->id)->firstOrFail();
        $today = Carbon::today();

        $pagi = TripPassenger::where('student_id', $studentId)
                ->whereHas('trip', function($q) use ($today) {
                    $q->where('type', 'pickup')->whereDate('date', $today)->whereIn('status', ['active', 'finished', 'scheduled']);
                })->first();

        $sore = TripPassenger::where('student_id', $studentId)
                ->whereHas('trip', function($q) use ($today) {
                    $q->where('type', 'dropoff')->whereDate('date', $today)->whereIn('status', ['active', 'finished', 'scheduled']);
                })->first();

        return response()->json([
            'pagi' => [
                'status' => $pagi ? $pagi->status : 'waiting',
                'time'   => $pagi && $pagi->dropped_at ? Carbon::parse($pagi->dropped_at)->format('H:i') . ' WIB' : '-'
            ],
            'sore' => [
                'status' => $sore ? $sore->status : 'waiting',
                'time'   => $sore && $sore->dropped_at ? Carbon::parse($sore->dropped_at)->format('H:i') . ' WIB' : '-'
            ]
        ]);
    }

    public function myChildren()
    {
        $parent = Auth::user();
        $students = Student::where('parent_id', $parent->id)->with('complex')->get();
        return view('parent_dashboard.children', compact('students'));
    }

    public function showTripDetail($passengerId)
    {
        $passenger = TripPassenger::with(['trip.driver', 'trip.shuttle', 'trip.route', 'student'])->findOrFail($passengerId);
        if (intval($passenger->student->parent_id) != intval(Auth::id())) {
            return redirect()->route('parents.dashboard')->with('error', 'Akses ditolak.');
        }
        return view('parent_dashboard.trip_detail', compact('passenger'));
    }

    public function setAbsent(Request $request, $studentId)
    {
        $today = Carbon::today();
        $passenger = TripPassenger::where('student_id', $studentId)
                    ->whereHas('trip', function($q) use ($today) {
                        $q->whereDate('date', $today)->where('status', '!=', 'finished');
                    })
                    ->where('status', 'pending')
                    ->first();

        if ($passenger) {
            $passenger->update(['status' => 'absent']);
            return back()->with('success', 'Status anak berhasil diubah menjadi Izin.');
        }
        return back()->with('error', 'Tidak bisa izin saat ini.');
    }

    public function history(Request $request)
    {
        $parent = Auth::user();
        $childIds = Student::where('parent_id', $parent->id)->pluck('id');
        
        $query = TripPassenger::whereIn('student_id', $childIds)
            ->with(['trip.route', 'trip.driver', 'student'])
            ->whereHas('trip', function($q) { $q->where('status', 'finished'); });

        if ($request->filled('date')) {
            $query->whereHas('trip', function($q) use ($request) { $q->whereDate('date', $request->date); });
        } elseif ($request->filled('month') && $request->filled('year')) {
            $query->whereHas('trip', function($q) use ($request) {
                $q->whereMonth('date', $request->month)->whereYear('date', $request->year);
            });
        } else {
            $query->whereHas('trip', function($q) {
                $q->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year);
            });
        }
        
        $histories = $query->join('trips', 'trip_passengers.trip_id', '=', 'trips.id')
                           ->select('trip_passengers.*')->orderBy('trips.date', 'desc')
                           ->paginate(10)->withQueryString();
                           
        return view('parent_dashboard.history', compact('histories'));
    }

    public function editChild($id)
    {
        $student = Student::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        return view('parent_dashboard.edit_child', compact('student'));
    }

    public function updateChild(Request $request, $id)
    {
        $student = Student::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        $request->validate([
            'address_note' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);
        $data = ['address_note' => $request->address_note];
        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $path = $request->file('photo')->store('student-photos', 'public');
            $data['photo'] = $path;
        }
        $student->update($data);
        return redirect()->route('parents.my_children')->with('success', 'Data anak berhasil diperbarui.');
    }
}