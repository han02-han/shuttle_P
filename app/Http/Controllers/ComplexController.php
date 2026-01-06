<?php

namespace App\Http\Controllers;

use App\Models\Complex;
use App\Models\Route; 
use Illuminate\Http\Request;

class ComplexController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi query dengan relation route
        $query = Complex::with('route');

        // 2. Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Nama Komplek
                $q->where('name', 'like', "%{$search}%")
                  // ATAU Cari berdasarkan Nama Rute (Relasi)
                  ->orWhereHas('route', function($qRoute) use ($search) {
                      $qRoute->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Ambil data (urutkan berdasarkan nama komplek)
        $complexes = $query->orderBy('name')->get();
        
        return view('complexes.index', compact('complexes'));
    }

    public function create()
    {
        $routes = Route::orderBy('name')->get(); 
        return view('complexes.create', compact('routes')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
        ]);

        Complex::create($request->all());

        return redirect()->route('complexes.index')->with('success', 'Komplek berhasil ditambahkan.');
    }

    public function edit(Complex $complex)
    {
        $routes = Route::orderBy('name')->get();
        return view('complexes.edit', compact('complex', 'routes'));
    }

    public function update(Request $request, Complex $complex)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
        ]);

        $complex->update($request->all());

        return redirect()->route('complexes.index')->with('success', 'Komplek berhasil diperbarui.');
    }

    public function destroy(Complex $complex)
    {
        $complex->delete();
        return redirect()->route('complexes.index')->with('success', 'Komplek dihapus.');
    }
}