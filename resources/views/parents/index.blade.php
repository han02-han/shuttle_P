@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">

    {{-- 1. HEADER & PENCARIAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-people-fill text-primary me-2"></i>Data Wali Murid
            </h3>
            <p class="text-muted mb-0">Manajemen data orang tua dan kontak darurat siswa.</p>
        </div>

        <div class="d-flex gap-2">
            {{-- Form Pencarian --}}
            <form action="{{ route('parents.index') }}" method="GET" class="d-flex">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                    <input type="text" name="search" class="form-control border-0 ps-4 bg-white" 
                           placeholder="Cari ID atau Nama..." 
                           value="{{ request('search') }}" 
                           aria-label="Cari Wali Murid">
                    <button class="btn btn-white border-0 text-primary px-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('parents.index') }}" class="btn btn-white border-0 text-danger px-3" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('parents.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold d-flex align-items-center">
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
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Nama Wali & ID</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Kontak & Akun</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">No. WhatsApp</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parents as $parent)
                        <tr>
                            {{-- Nama & ID --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    {{-- Avatar Inisial --}}
                                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <span class="fw-bold">{{ strtoupper(substr($parent->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $parent->name }}</span>
                                        <span class="text-muted small" style="font-size: 0.75rem;">
                                            <i class="bi bi-hash me-1"></i>ID: {{ $parent->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Kontak --}}
                            <td>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-envelope me-2"></i> {{ $parent->email }}
                                </div>
                            </td>

                            {{-- WA --}}
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-normal">
                                    <i class="bi bi-whatsapp me-1"></i> {{ $parent->phone }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- FORM HAPUS DENGAN VALIDASI SWEETALERT --}}
                                    {{-- 1. Beri ID unik pada form --}}
                                    <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" class="d-inline" id="delete-form-{{ $parent->id }}">
                                        @csrf @method('DELETE')
                                        {{-- 2. Ubah type jadi button dan tambah onclick --}}
                                        <button type="button" onclick="confirmDeleteParent('{{ $parent->id }}', '{{ $parent->name }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Data">
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
                                        <i class="bi bi-people display-4 text-secondary"></i>
                                    </div>
                                    @if(request('search'))
                                        <h5 class="fw-bold text-secondary">Wali Murid tidak ditemukan</h5>
                                        <p class="text-muted small mb-0">Tidak ada hasil untuk pencarian "{{ request('search') }}"</p>
                                    @else
                                        <h5 class="fw-bold text-secondary">Data Kosong</h5>
                                        <p class="text-muted small mb-0">Belum ada data wali murid yang terdaftar.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Check --}}
            @if($parents->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $parents->links() }}
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
    // FUNGSI KONFIRMASI HAPUS WALI MURID
    function confirmDeleteParent(id, name) {
        Swal.fire({
            title: 'Hapus Wali Murid?',
            text: `Apakah Anda yakin ingin menghapus data Wali Murid "${name}"? Data yang dihapus tidak dapat dikembalikan.`,
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