@extends('layouts.admin')

@section('content')
{{-- Load SweetAlert2 Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">

    {{-- 1. HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-buildings-fill text-primary me-2"></i>Data Perumahan
            </h3>
            <p class="text-muted mb-0">Manajemen lokasi tempat tinggal siswa yang terdaftar.</p>
        </div>

        {{-- AREA TOMBOL & PENCARIAN --}}
        <div class="d-flex gap-2 align-items-center">
            
            {{-- FORM PENCARIAN --}}
            <form action="{{ route('complexes.index') }}" method="GET" class="d-flex position-relative">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control border-end-0 rounded-start-pill ps-3" 
                           placeholder="Cari Komplek / Rute..." 
                           value="{{ request('search') }}"
                           style="max-width: 200px;">
                    <button class="btn btn-white border border-start-0 rounded-end-pill text-secondary" type="submit" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                {{-- TOMBOL RESET (Hanya muncul jika sedang mencari) --}}
                @if(request('search'))
                    <a href="{{ route('complexes.index') }}" class="btn btn-light border text-danger rounded-pill ms-2" title="Reset Pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            <a href="{{ route('complexes.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> Tambah Komplek
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
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Nama Komplek</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Rute Wilayah</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complexes as $complex)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    {{-- Icon Bangunan sebagai Avatar --}}
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $complex->name }}</div>
                                        <small class="text-muted">ID: #{{ $complex->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($complex->route)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill fw-normal">
                                        <i class="bi bi-map-fill me-1"></i> {{ $complex->route->name }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic small">
                                        <i class="bi bi-exclamation-circle me-1"></i> Belum ada rute
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('complexes.edit', $complex->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- FORM DELETE DENGAN ID UNIK --}}
                                    <form id="delete-form-{{ $complex->id }}" action="{{ route('complexes.destroy', $complex->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        {{-- Tombol type="button" memanggil fungsi JS --}}
                                        <button type="button" onclick="confirmDelete('{{ $complex->id }}', '{{ $complex->name }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Komplek">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                    <div class="bg-light rounded-circle p-3 mb-3">
                                        @if(request('search'))
                                            <i class="bi bi-search display-4 text-secondary"></i>
                                        @else
                                            <i class="bi bi-buildings display-4 text-secondary"></i>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold text-secondary">
                                        @if(request('search'))
                                            Data tidak ditemukan
                                        @else
                                            Data Kosong
                                        @endif
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        @if(request('search'))
                                            Tidak ada komplek/rute dengan kata kunci "<strong>{{ request('search') }}</strong>". <br>
                                            <a href="{{ route('complexes.index') }}" class="text-decoration-none fw-bold">Reset Pencarian</a>
                                        @else
                                            Belum ada data komplek perumahan.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($complexes, 'hasPages') && $complexes->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $complexes->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* Styling agar baris tabel nyaman dibaca */
    .table > :not(caption) > * > * { padding: 1rem 0.5rem; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    /* SweetAlert Custom Font */
    .swal2-popup { font-family: inherit !important; border-radius: 16px !important; }
</style>

{{-- SCRIPT VALIDASI DELETE --}}
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Komplek?',
            text: "Anda akan menghapus: " + name + ". Data siswa di komplek ini mungkin akan kehilangan referensi alamat!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form secara programatis jika user klik Ya
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

@endsection