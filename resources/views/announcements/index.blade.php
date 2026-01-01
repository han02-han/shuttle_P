@extends('layouts.admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-megaphone-fill text-primary me-2"></i>Pengumuman</h3>
            <p class="text-muted mb-0">Kelola informasi untuk Driver dan Wali Murid.</p>
        </div>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Buat Pengumuman
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Judul & Isi</th>
                            <th class="py-3">Target</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $item)
                        <tr>
                            <td class="ps-4 py-3" style="max-width: 400px;">
                                <div class="fw-bold text-dark">{{ $item->title }}</div>
                                <div class="text-muted small text-truncate">{{ Str::limit($item->content, 80) }}</div>
                                <div class="text-secondary small mt-1"><i class="bi bi-clock me-1"></i> {{ $item->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($item->target_role == 'all')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 rounded-pill">Semua</span>
                                @elseif($item->target_role == 'driver')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 rounded-pill">Hanya Driver</span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 rounded-pill">Hanya Wali Murid</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('announcements.toggle', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3" style="font-size: 0.75rem;">
                                        {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                {{-- PERUBAHAN DI SINI: Form diberi ID unik dan onsubmit dihapus --}}
                                <form id="delete-form-{{ $item->id }}" action="{{ route('announcements.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    {{-- Tombol diubah jadi type="button" dan memanggil fungsi JS --}}
                                    <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada pengumuman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="p-3">{{ $announcements->links() }}</div>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK SWEETALERT --}}
{{-- Pastikan section ini dirender di layout utama Anda, misal @stack('scripts') atau taruh sebelum @endsection --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Pengumuman?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true // Membalik posisi tombol agar tombol batal di kiri (opsional)
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form berdasarkan ID dan submit
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

@endsection