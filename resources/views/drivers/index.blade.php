@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">
    
    {{-- 1. HEADER & SEARCH BAR --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-person-badge text-primary me-2"></i>Data Driver
            </h3>
            <p class="text-muted mb-0">Kelola data supir, kontak, dan nomor lisensi berkendara.</p>
        </div>

        <div class="d-flex gap-2">
            {{-- Form Pencarian (Hanya Nama atau ID) --}}
            <form action="{{ route('drivers.index') }}" method="GET" class="d-flex">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                    <input type="text" name="search" class="form-control border-0 ps-4 bg-white" 
                           placeholder="Cari ID atau Nama..." 
                           value="{{ request('search') }}" 
                           aria-label="Cari Driver">
                    <button class="btn btn-white border-0 text-primary px-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('drivers.index') }}" class="btn btn-white border-0 text-danger px-3" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('drivers.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold d-flex align-items-center">
                <i class="bi bi-plus-lg me-2"></i> Tambah
            </a>
        </div>
    </div>

    {{-- ALERT SUKSES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. TABEL CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            
            {{-- Wrapper Responsive --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Nama Driver & ID</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Kontak</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Nomor SIM</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                        <tr>
                            {{-- Nama & ID --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    {{-- Avatar Placeholder --}}
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <span class="fw-bold">{{ strtoupper(substr($driver->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $driver->name }}</span>
                                        {{-- Tampilan ID --}}
                                        <span class="text-muted small" style="font-size: 0.75rem;">
                                            <i class="bi bi-hash me-1"></i>ID: {{ $driver->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Kontak --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark small mb-1">
                                        <i class="bi bi-envelope me-2 text-muted"></i>{{ $driver->email }}
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bi bi-telephone me-2 text-muted"></i>{{ $driver->phone ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Nomor SIM --}}
                            <td>
                                @if(isset($driver->driverProfile->license_number))
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-normal">
                                        <i class="bi bi-card-heading me-1 text-secondary"></i> 
                                        {{ $driver->driverProfile->license_number }}
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill fw-normal" style="font-size: 0.7rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i> Belum Ada SIM
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- FORM HAPUS DENGAN VALIDASI SWEETALERT --}}
                                    {{-- 1. Beri ID unik pada form --}}
                                    <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST" class="d-inline" id="delete-form-{{ $driver->id }}">
                                        @csrf @method('DELETE')
                                        {{-- 2. Ubah type jadi button dan tambah onclick --}}
                                        <button type="button" onclick="confirmDeleteDriver('{{ $driver->id }}', '{{ $driver->name }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Driver">
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
                                        <i class="bi bi-person-x display-4 text-secondary"></i>
                                    </div>
                                    @if(request('search'))
                                        <h5 class="fw-bold text-secondary">Driver tidak ditemukan</h5>
                                        <p class="text-muted small mb-0">Tidak ada hasil untuk pencarian "{{ request('search') }}"</p>
                                    @else
                                        <h5 class="fw-bold text-secondary">Belum ada data Driver</h5>
                                        <p class="text-muted small mb-0">Silakan tambahkan driver baru untuk memulai.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($drivers->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $drivers->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* Agar tampilan tabel lebih lega */
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    
    /* Efek hover baris tabel */
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
    // FUNGSI KONFIRMASI HAPUS DRIVER
    function confirmDeleteDriver(id, name) {
        Swal.fire({
            title: 'Hapus Driver?',
            text: `Apakah Anda yakin ingin menghapus driver "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
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