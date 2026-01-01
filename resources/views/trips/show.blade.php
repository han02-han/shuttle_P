@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- HEADER: JUDUL & NAVIGASI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Detail Perjalanan</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trips.index') }}">Riwayat Perjalanan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#TRIP-{{ $trip->id }}</li>
                </ol>
            </nav>
        </div>
        
        {{-- MODIFIKASI 1: Tambah ID 'live-status-badge' --}}
        <div id="live-status-badge">
            @if($trip->status == 'active')
                {{-- Indikator Visual Bahwa Halaman Ini Live Refreshing --}}
                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fs-6 blink-soft">
                    <i class="bi bi-broadcast me-2"></i>Live Tracking
                </span>
            @elseif($trip->status == 'finished')
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill fs-6">
                    <i class="bi bi-check-circle-fill me-2"></i>Selesai
                </span>
            @else
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill fs-6">
                    <i class="bi bi-clock me-2"></i>Terjadwal
                </span>
            @endif
        </div>
    </div>

    {{-- INFO ROW: CARD RINGKASAN --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Info Rute --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="bi bi-map-fill fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-bold ls-1">Rute & Waktu</small>
                            <h5 class="fw-bold mb-0 text-dark">{{ $trip->route->name }}</h5>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 text-muted small">
                            <i class="bi bi-calendar-event me-2"></i> {{ \Carbon\Carbon::parse($trip->date)->translatedFormat('l, d F Y') }}
                        </li>
                        <li class="text-muted small">
                            @if($trip->type == 'pickup')
                                <span class="badge bg-warning text-dark"><i class="bi bi-sunrise-fill me-1"></i> Jemput Pagi</span>
                            @else
                                <span class="badge bg-info text-dark"><i class="bi bi-sunset-fill me-1"></i> Antar Sore</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Card 2: Info Driver & Shuttle --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-dark bg-opacity-10 text-dark rounded-circle p-3 me-3">
                            <i class="bi bi-person-badge-fill fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-bold ls-1">Driver & Armada</small>
                            <h5 class="fw-bold mb-0 text-dark">{{ $trip->driver->name }}</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center bg-light rounded-3 p-2 border">
                        <div>
                            <small class="d-block text-muted" style="font-size: 0.7rem;">MOBIL</small>
                            <span class="fw-bold text-dark">{{ $trip->shuttle->car_model ?? 'Shuttle' }}</span>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-muted" style="font-size: 0.7rem;">PLAT NOMOR</small>
                            <span class="badge bg-white border text-dark font-monospace">{{ $trip->shuttle->police_number ?? $trip->shuttle->plate_number }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Statistik Penumpang --}}
        {{-- MODIFIKASI 2: Tambah ID 'live-stats-card' agar angka progress bar update real-time --}}
        <div class="col-md-4" id="live-stats-card">
            @php
                $total = $passengers->count();
                $selesai = $passengers->whereIn('status', ['dropped_off', 'absent'])->count(); // Absent dihitung selesai
                $onboard = $passengers->where('status', 'picked_up')->count();
                $waiting = $passengers->where('status', 'pending')->count();
                
                // Persentase
                $percent = $total > 0 ? ($selesai / $total) * 100 : 0;
                $processed = $passengers->where('status', '!=', 'pending')->count();
                $progressPercent = $total > 0 ? ($processed / $total) * 100 : 0;
            @endphp
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50 text-uppercase mb-1">Total Siswa</h6>
                            <h2 class="fw-bold mb-0">{{ $total }} <span class="fs-6 text-white-50">Anak</span></h2>
                        </div>
                        <div class="text-end">
                            <h6 class="text-white-50 text-uppercase mb-1">Selesai</h6>
                            <h2 class="fw-bold mb-0">{{ $processed }}</h2>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between text-white-50 small mb-1">
                            <span>Progress</span>
                            <span>{{ round($progressPercent) }}%</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT: DAFTAR SISWA --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">Manifest Penumpang</h5>
            
            {{-- MODIFIKASI 3: Tambah ID 'live-header-actions' agar tombol Admin hilang jika trip selesai --}}
            <div id="live-header-actions">
                @if($trip->status != 'finished')
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-danger dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear-fill me-1"></i> Admin Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><h6 class="dropdown-header">Force Update Status</h6></li>
                        <li>
                            <form action="{{ route('trips.finish', $trip->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyelesaikan paksa perjalanan ini?')">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-flag-fill me-2"></i> Selesaikan Perjalanan
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>

        {{-- MODIFIKASI 4: Tambah ID 'live-passenger-table' untuk target update tabel --}}
        <div class="table-responsive" id="live-passenger-table">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Siswa & Lokasi</th>
                        <th class="py-3">Kontak Ortu</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Waktu Naik</th>
                        <th class="py-3 text-center">Waktu Turun</th>
                        <th class="py-3 text-end pe-4">Aksi Manual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passengers as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center text-primary fw-bold border" style="width: 45px; height: 45px;">
                                    {{ substr($p->student->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $p->student->name }}</h6>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $p->student->complex->name ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-telephone me-2"></i> {{ $p->student->parent->phone ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if($p->status == 'pending')
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 rounded-pill">Menunggu</span>
                            @elseif($p->status == 'waiting')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 rounded-pill">Driver Tiba</span>
                            @elseif($p->status == 'picked_up')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 rounded-pill">Di Mobil</span>
                            @elseif($p->status == 'dropped_off')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Selesai</span>
                            @elseif($p->status == 'absent' || $p->status == 'skipped')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Absen/Skip</span>
                            @endif
                        </td>
                        
                        {{-- JAM NAIK --}}
                        <td class="text-center">
                            @if($p->picked_at)
                                <span class="fw-bold text-dark font-monospace">{{ \Carbon\Carbon::parse($p->picked_at)->format('H:i') }}</span>
                                <i class="bi bi-check-all text-primary ms-1"></i>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- JAM TURUN --}}
                        <td class="text-center">
                            @if($p->dropped_at)
                                <span class="fw-bold text-dark font-monospace">{{ \Carbon\Carbon::parse($p->dropped_at)->format('H:i') }}</span>
                                <i class="bi bi-check-all text-success ms-1"></i>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- AKSI MANUAL (ADMIN OVERRIDE) --}}
                        <td class="text-end pe-4">
                            @if($trip->status != 'finished')
                                <div class="btn-group">
                                    {{-- JIKA TRIP JEMPUT PAGI --}}
                                    @if($trip->type == 'pickup')
                                        @if($p->status == 'pending' || $p->status == 'waiting')
                                            <form action="{{ route('passengers.pickup', $p->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success rounded-start" title="Manual: Siswa Naik">
                                                    <i class="bi bi-box-arrow-in-right"></i> Naik
                                                </button>
                                            </form>
                                            <form action="{{ route('passengers.absent', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai siswa ini absen/skip?')">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger rounded-end" title="Manual: Skip/Absen">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @elseif($p->status == 'picked_up')
                                            <button class="btn btn-sm btn-success disabled" disabled><i class="bi bi-check2"></i> Onboard</button>
                                        @endif
                                    
                                    {{-- JIKA TRIP ANTAR SORE --}}
                                    @elseif($trip->type == 'dropoff')
                                        @if($p->status == 'pending')
                                            <form action="{{ route('passengers.pickup', $p->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-primary" title="Manual: Siswa Masuk Mobil">
                                                    <i class="bi bi-person-check"></i> Absen Naik
                                                </button>
                                            </form>
                                        @elseif($p->status == 'picked_up')
                                            <form action="{{ route('passengers.dropoff', $p->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success" title="Manual: Siswa Sampai Rumah">
                                                    <i class="bi bi-house-door"></i> Sampai
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <span class="text-muted small">Trip Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center opacity-50">
                                <i class="bi bi-people display-4 text-muted mb-3"></i>
                                <h6 class="text-muted">Tidak ada data penumpang pada perjalanan ini.</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top py-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Halaman ini adalah tampilan <strong>Administrator</strong>. Perubahan status yang dilakukan di sini akan langsung terlihat oleh Driver dan Orang Tua.
            </small>
        </div>
    </div>
</div>

<style>
    /* CSS Tambahan khusus halaman ini */
    .ls-1 { letter-spacing: 1px; }
    .blink-soft { animation: blink-soft 2s infinite; }
    @keyframes blink-soft {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
</style>

<script>
    // 1. AJAX LIVE REFRESH (3 DETIK)
    // Menggunakan Fetch API untuk update konten tanpa reload halaman
    function refreshTripDetail() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Helper untuk update elemen jika ditemukan
                const updateElement = (id) => {
                    const newContent = doc.getElementById(id);
                    const currentContent = document.getElementById(id);
                    if (newContent && currentContent) {
                        currentContent.innerHTML = newContent.innerHTML;
                    }
                };

                // Update 4 Bagian Penting
                updateElement('live-status-badge');   // Badge Status di Header
                updateElement('live-stats-card');     // Card Statistik (Biru)
                updateElement('live-header-actions'); // Tombol Aksi Admin (Header Table)
                updateElement('live-passenger-table'); // Tabel Penumpang
            })
            .catch(error => console.log('Gagal refresh data:', error));
    }

    // Jalankan setiap 3 detik
    setInterval(refreshTripDetail, 3000);

    // Hapus script scroll position lama karena sudah tidak diperlukan (tidak ada reload)
</script>
@endsection