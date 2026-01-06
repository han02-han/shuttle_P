@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">

    {{-- 1. HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-bus-front-fill text-primary me-2"></i>Data Armada Mobil
            </h3>
            <p class="text-muted mb-0">Manajemen kendaraan operasional dan status ketersediaan.</p>
        </div>
        
        {{-- AREA TOMBOL & PENCARIAN --}}
        <div class="d-flex gap-2 align-items-center">
            
            {{-- FORM PENCARIAN --}}
            <form action="{{ route('shuttles.index') }}" method="GET" class="d-flex position-relative">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control border-end-0 rounded-start-pill ps-3" 
                           placeholder="Cari Plat / Model..." 
                           value="{{ request('search') }}"
                           style="max-width: 200px;">
                    <button class="btn btn-white border border-start-0 rounded-end-pill text-secondary" type="submit" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                {{-- TOMBOL RESET (Hanya muncul jika sedang mencari) --}}
                @if(request('search'))
                    <a href="{{ route('shuttles.index') }}" class="btn btn-light border text-danger rounded-pill ms-2" title="Reset Pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            {{-- TOMBOL TAMBAH --}}
            <a href="{{ route('shuttles.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> Tambah Armada
            </a>
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
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Identitas Kendaraan</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Kapasitas</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Status Operasional</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shuttles as $shuttle)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    {{-- Icon Mobil --}}
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $shuttle->plate_number }}</div>
                                        <small class="text-muted">{{ $shuttle->car_model }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-normal">
                                    <i class="bi bi-people-fill me-1 text-secondary"></i> {{ $shuttle->capacity }} Kursi
                                </span>
                            </td>
                            <td>
                                @if($shuttle->status == 'maintenance')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">
                                        <i class="bi bi-tools me-1"></i> Perbaikan (Maintenance)
                                    </span>
                                @elseif($shuttle->schedules_count > 0)
                                    {{-- BADGE STATUS DIGUNAKAN --}}
                                    <div class="mb-1">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">
                                            <i class="bi bi-calendar-check me-1"></i> Digunakan ({{ $shuttle->schedules_count }} Jadwal)
                                        </span>
                                    </div>

                                    {{-- LOGIKA MENAMPILKAN NAMA DRIVER --}}
                                    @php
                                        // Mengambil nama driver dari jadwal, unique agar tidak duplikat, lalu digabung koma
                                        $driverNames = $shuttle->schedules->map(function($schedule) {
                                            return $schedule->driver->name ?? 'Tanpa Driver';
                                        })->unique()->implode(', ');
                                    @endphp
                                    
                                    <small class="text-muted d-block ms-1">
                                        <i class="bi bi-steering-wheel me-1"></i> {{ $driverNames }}
                                    </small>

                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Siap Pakai (Standby)
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('shuttles.edit', $shuttle->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- FORM HAPUS DENGAN VALIDASI SWEETALERT --}}
                                    <form action="{{ route('shuttles.destroy', $shuttle->id) }}" method="POST" class="d-inline" id="delete-form-{{ $shuttle->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDeleteShuttle('{{ $shuttle->id }}', '{{ $shuttle->plate_number }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Armada">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                    <div class="bg-light rounded-circle p-3 mb-3">
                                        @if(request('search'))
                                            <i class="bi bi-search display-4 text-secondary"></i>
                                        @else
                                            <i class="bi bi-bus-front display-4 text-secondary"></i>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold text-secondary">
                                        @if(request('search'))
                                            Data tidak ditemukan
                                        @else
                                            Belum ada armada
                                        @endif
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        @if(request('search'))
                                            Tidak ada armada dengan kata kunci "<strong>{{ request('search') }}</strong>". <br>
                                            <a href="{{ route('shuttles.index') }}" class="text-decoration-none fw-bold">Reset Pencarian</a>
                                        @else
                                            Silakan tambahkan data kendaraan baru.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Check --}}
            @if(method_exists($shuttles, 'hasPages') && $shuttles->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $shuttles->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* Spacing tabel agar tidak terlalu padat */
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* SweetAlert Custom Font */
    .swal2-popup {
        font-family: inherit !important;
        border-radius: 16px !important;
    }
</style>

<script>
    // FUNGSI KONFIRMASI HAPUS ARMADA
    function confirmDeleteShuttle(id, plate) {
        Swal.fire({
            title: 'Hapus Armada?',
            text: `Apakah Anda yakin ingin menghapus data armada dengan plat nomor "${plate}"?`,
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