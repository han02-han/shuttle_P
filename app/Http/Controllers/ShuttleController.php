<?php

namespace App\Http\Controllers;

use App\Models\Shuttle;
use Illuminate\Http\Request;

class ShuttleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi query dasar
        // PERBAIKAN: Tambahkan with(['schedules.driver']) agar data driver terambil untuk tampilan index
        $query = Shuttle::with(['schedules.driver'])->withCount('schedules');

        // 2. Logika Pencarian: Cek apakah ada input 'search'
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Filter berdasarkan Plat Nomor ATAU Model Mobil
            $query->where(function($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('car_model', 'like', "%{$search}%");
            });
        }

        // 3. Eksekusi query (menggunakan latest() agar data baru muncul paling atas)
        // Gunakan get() sesuai kode Anda (atau paginate(10) jika ingin pembagian halaman)
        $shuttles = $query->latest()->get();

        return view('shuttles.index', compact('shuttles'));
    }

    public function create()
    {
        return view('shuttles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20|unique:shuttles',
            'car_model' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
        ]);

        Shuttle::create($request->all());
        return redirect()->route('shuttles.index')->with('success', 'Armada berhasil ditambahkan.');
    }

    public function edit(Shuttle $shuttle)
    {
        return view('shuttles.edit', compact('shuttle'));
    }

    public function update(Request $request, Shuttle $shuttle)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20|unique:shuttles,plate_number,' . $shuttle->id,
            'car_model' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,maintenance',
        ]);

        $shuttle->update($request->all());
        return redirect()->route('shuttles.index')->with('success', 'Armada berhasil diperbarui.');
    }

    public function destroy(Shuttle $shuttle)
    {
        $shuttle->delete();
        return redirect()->route('shuttles.index')->with('success', 'Armada berhasil dihapus.');
    }
}