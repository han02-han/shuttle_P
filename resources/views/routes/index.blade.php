@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">

    {{-- 1. HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-map-fill text-primary me-2"></i>Data Rute & Wilayah
            </h3>
            <p class="text-muted mb-0">Daftar rute penjemputan dan cakupan area komplek.</p>
        </div>
        <a href="{{ route('routes.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold">
            <i class="bi bi-plus-lg me-2"></i> Tambah Rute
        </a>
    </div>

    {{-- 2. TABEL CARD --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            
            {{-- Wrapper Responsive --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Nama Rute</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold" style="min-width: 300px;">Wilayah / Komplek</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Armada</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    {{-- Icon Map --}}
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-signpost-split-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $route->name }}</div>
                                        <small class="text-muted">ID: #{{ $route->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse($route->complexes as $complex)
                                        <span class="badge bg-white text-secondary border shadow-sm fw-normal py-2 px-3 rounded-pill">
                                            <i class="bi bi-building me-1 text-primary"></i> {{ $complex->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted fst-italic small">
                                            <i class="bi bi-exclamation-circle me-1"></i> Belum ada wilayah
                                        </span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @if(isset($route->shuttles_count) && $route->shuttles_count > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-normal">
                                        <i class="bi bi-bus-front-fill me-1"></i> {{ $route->shuttles_count }} Mobil
                                    </span>
                                @else
                                    <span class="text-muted small">
                                        <i class="bi bi-dash-circle me-1"></i> 0 Mobil
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Edit Rute">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- FORM HAPUS DENGAN VALIDASI SWEETALERT --}}
                                    {{-- 1. Beri ID unik pada form --}}
                                    <form action="{{ route('routes.destroy', $route->id) }}" method="POST" class="d-inline" id="delete-form-{{ $route->id }}">
                                        @csrf @method('DELETE')
                                        {{-- 2. Ubah type jadi button dan tambah onclick --}}
                                        <button type="button" onclick="confirmDeleteRoute('{{ $route->id }}', '{{ $route->name }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Rute">
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
                                        <i class="bi bi-map display-4 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold text-secondary">Data Rute Kosong</h5>
                                    <p class="text-muted small mb-0">Silakan tambahkan rute perjalanan baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($routes, 'hasPages') && $routes->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $routes->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* Styling agar baris tabel nyaman dibaca */
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
    // FUNGSI KONFIRMASI HAPUS RUTE
    function confirmDeleteRoute(id, name) {
        Swal.fire({
            title: 'Hapus Rute?',
            text: `Apakah Anda yakin ingin menghapus rute "${name}"? Data terkait seperti jadwal mungkin akan terpengaruh.`,
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