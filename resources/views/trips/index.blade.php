@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">

    {{-- 1. HEADER & FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                <div class="mb-3 mb-md-0">
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Perjalanan
                    </h3>
                    <p class="text-muted mb-0">Filter dan cari data perjalanan armada sekolah.</p>
                </div>
                
                {{-- Tombol Reset Filter (Muncul jika user sedang melakukan filter) --}}
                @if(request('route_id') || request('filter_date') || request('filter_month'))
                <div>
                    <a href="{{ route('trips.index') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 transition-hover">
                        <i class="bi bi-x-circle me-1"></i> Reset Filter
                    </a>
                </div>
                @endif
            </div>

            {{-- FORM FILTER --}}
            <form action="{{ route('trips.index') }}" method="GET">
                <div class="row g-3">
                    
                    {{-- Filter Rute --}}
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-bold text-uppercase">Pilih Rute</label>
                        <select name="route_id" class="form-select border-0 bg-light py-2" onchange="this.form.submit()">
                            <option value="">Semua Rute</option>
                            @foreach($routes as $route)
                                <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                                    {{ $route->name }} ({{ $route->start_point }} - {{ $route->end_point }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tanggal Spesifik --}}
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Tanggal Spesifik</label>
                        <input type="date" name="filter_date" class="form-control border-0 bg-light py-2" 
                               value="{{ request('filter_date') }}" 
                               onchange="this.form.submit()">
                    </div>

                    {{-- Filter Bulan (Alternatif) --}}
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Atau Pilih Bulan</label>
                        <input type="month" name="filter_month" class="form-control border-0 bg-light py-2" 
                               value="{{ request('filter_month') }}"
                               {{ request('filter_date') ? 'disabled' : '' }} 
                               onchange="this.form.submit()">
                        @if(request('filter_date'))
                            <div class="form-text text-muted fst-italic" style="font-size: 0.7rem">*Matikan tanggal untuk filter bulan</div>
                        @endif
                    </div>

                    {{-- Tombol Cari Manual (Optional) --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold shadow-sm">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. TABEL CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            
            {{-- Wrapper Responsive --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Waktu & Tipe</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Rute Perjalanan</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Driver & Armada</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Status</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trips as $trip)
                        <tr>
                            {{-- Waktu & Tipe --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark d-flex align-items-center">
                                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                                        {{ \Carbon\Carbon::parse($trip->date)->translatedFormat('d M Y') }}
                                    </span>
                                    <div class="mt-1 ms-4">
                                        @if($trip->type == 'pickup')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2 fw-normal">
                                                <i class="bi bi-sun-fill me-1"></i> Pagi (Jemput)
                                            </span>
                                        @else
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-2 fw-normal">
                                                <i class="bi bi-moon-stars-fill me-1"></i> Sore (Antar)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Rute --}}
                            <td>
                                <span class="badge bg-light text-dark border fw-normal px-3 py-2 rounded-pill">
                                    <i class="bi bi-map-fill me-1 text-secondary"></i> {{ $trip->route->name ?? 'Rute Dihapus' }}
                                </span>
                            </td>

                            {{-- Driver & Armada --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-white border rounded-circle p-1 me-2 d-flex align-items-center justify-content-center text-secondary" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark small">{{ $trip->driver->name ?? 'Driver Dihapus' }}</span>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-bus-front me-1"></i> {{ $trip->shuttle->plate_number ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($trip->status == 'scheduled')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill fw-normal">
                                        <i class="bi bi-clock me-1"></i> Terjadwal
                                    </span>
                                @elseif($trip->status == 'active')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-normal blink-soft">
                                        <span class="spinner-grow spinner-grow-sm me-1" style="width: 0.5rem; height: 0.5rem;"></span> Sedang Jalan
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-normal">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('trips.show', $trip->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    {{-- FORM HAPUS DENGAN VALIDASI SWEETALERT --}}
                                    <form action="{{ route('trips.destroy', $trip->id) }}" method="POST" id="delete-form-{{ $trip->id }}">
                                        @csrf 
                                        @method('DELETE')
                                        {{-- Type button, onclick memanggil fungsi JS --}}
                                        <button type="button" onclick="confirmDeleteTrip('{{ $trip->id }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Riwayat">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                    <div class="bg-light rounded-circle p-3 mb-3">
                                        <i class="bi bi-search display-4 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold text-secondary">Data Tidak Ditemukan</h5>
                                    <p class="text-muted small mb-0">Tidak ada perjalanan yang cocok dengan filter tanggal/rute Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Check --}}
            @if($trips->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $trips->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* Spacing tabel */
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .transition-hover:hover {
        background-color: #dc3545;
        color: white;
    }
    
    /* Animasi kedip halus untuk status aktif */
    @keyframes blink-soft {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    .blink-soft { animation: blink-soft 2s infinite; }

    /* SweetAlert Custom Font */
    .swal2-popup {
        font-family: inherit !important;
        border-radius: 16px !important;
    }
</style>

<script>
    function confirmDeleteTrip(id) {
        Swal.fire({
            title: 'Hapus Riwayat?',
            text: "Data presensi siswa di dalamnya akan ikut terhapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Merah
            cancelButtonColor: '#6c757d',  // Abu-abu
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection