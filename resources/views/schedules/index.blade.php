@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">

    {{-- 1. HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-calendar-week text-primary me-2"></i>Master Jadwal Rutin
            </h3>
            <p class="text-muted mb-0">Kelola jadwal operasional, driver, dan manifest penumpang.</p>
        </div>
        <a href="{{ route('schedules.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold">
            <i class="bi bi-plus-lg me-2"></i> Buat Jadwal Baru
        </a>
    </div>

    @php
        $indoDays = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
    @endphp

    {{-- 2. LIST JADWAL (ACCORDION) --}}
    <div class="accordion custom-accordion" id="scheduleAccordion">
        @forelse($schedules as $routeId => $routeSchedules)
            @php 
                $firstItem = $routeSchedules->first(); 
                // Urutkan jadwal berdasarkan hari (Senin-Minggu)
                $schedulesInRoute = $routeSchedules->sortBy(function($s) {
                    return array_search($s->day_of_week, ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
                });
                $routeName = $firstItem->route->name ?? 'Rute';
            @endphp

            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                {{-- HEADER WRAPPER --}}
                <div class="card-header bg-white p-0 border-0">
                    <div class="d-flex flex-wrap flex-md-nowrap align-items-stretch">
                        
                        {{-- TOMBOL TRIGGER ACCORDION --}}
                        <button class="accordion-button collapsed shadow-none bg-white p-4 flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $routeId }}" aria-expanded="false">
                            <div class="d-flex align-items-center gap-3 w-100 me-3">
                                {{-- Icon Rute --}}
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                    <i class="bi bi-signpost-2-fill fs-4"></i>
                                </div>
                                
                                {{-- Info Rute --}}
                                <div class="text-start flex-grow-1">
                                    <h5 class="fw-bold text-dark mb-1">{{ $routeName }}</h5>
                                    
                                    <div class="d-flex align-items-center text-muted small gap-3 flex-wrap">
                                        {{-- Driver Info --}}
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-badge text-secondary me-1"></i> 
                                            <span>{{ $firstItem->driver->name ?? 'Tanpa Driver' }}</span>
                                        </div>
                                        
                                        {{-- Divider --}}
                                        <div class="vr d-none d-sm-block text-secondary opacity-25"></div>

                                        {{-- Shuttle Info --}}
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bus-front text-secondary me-1"></i>
                                            <span>{{ $firstItem->shuttle->plate_number ?? 'Tanpa Mobil' }}</span>
                                        </div>

                                        {{-- Divider --}}
                                        <div class="vr d-none d-sm-block text-secondary opacity-25"></div>

                                        {{-- Active Days Badge --}}
                                        <span class="badge bg-light text-secondary border fw-normal">
                                            {{ $schedulesInRoute->count() }} Hari Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </button>

                        {{-- TOMBOL AKSI RANGKAIAN (Desktop: Kanan, Mobile: Bawah) --}}
                        <div class="p-3 d-flex align-items-center border-start border-light bg-light bg-opacity-10 gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('schedules.editBulk', $firstItem->id) }}" class="btn btn-warning text-dark fw-bold shadow-sm text-nowrap rounded-pill px-3" title="Edit seluruh hari">
                                <i class="bi bi-pencil-square me-1"></i> <span class="d-none d-lg-inline">Edit Rangkaian</span><span class="d-lg-none">Edit</span>
                            </a>

                            {{-- [MODIFIKASI] Tombol Hapus Rangkaian (Bulk) --}}
                            <form action="{{ route('schedules.destroyBulk', $firstItem->id) }}" method="POST" id="form-bulk-{{ $firstItem->id }}">
                                @csrf
                                @method('DELETE')
                                {{-- Type button, onclick confirmDeleteBulk --}}
                                <button type="button" onclick="confirmDeleteBulk('{{ $firstItem->id }}', '{{ $routeName }}')" class="btn btn-danger text-white fw-bold shadow-sm text-nowrap rounded-pill px-3" title="Hapus seluruh hari">
                                    <i class="bi bi-trash me-1"></i> <span class="d-none d-lg-inline">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- BODY ACCORDION (Tabel) --}}
                <div id="collapse{{ $routeId }}" class="accordion-collapse collapse" data-bs-parent="#scheduleAccordion">
                    <div class="accordion-body p-0 border-top">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle text-nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold" width="15%">Hari</th>
                                        <th class="py-3 text-secondary text-uppercase small fw-bold" width="30%">Jam Operasional</th>
                                        <th class="py-3 text-secondary text-uppercase small fw-bold" width="40%">Daftar Penumpang</th>
                                        <th class="py-3 text-secondary text-uppercase small fw-bold text-end pe-4" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedulesInRoute as $s)
                                    @php $hariIndo = $indoDays[$s->day_of_week] ?? $s->day_of_week; @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark badge bg-light border text-uppercase px-3 py-2">
                                                {{ $hariIndo }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                @if($s->pickup_time)
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill me-2 px-2 fw-normal" style="width: 85px;">
                                                            <i class="bi bi-sun-fill me-1"></i> Pagi
                                                        </span>
                                                        <span class="fw-bold text-dark font-monospace">{{ \Carbon\Carbon::parse($s->pickup_time)->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                                @if($s->dropoff_time)
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill me-2 px-2 fw-normal" style="width: 85px;">
                                                            <i class="bi bi-moon-stars-fill me-1"></i> Sore
                                                        </span>
                                                        <span class="fw-bold text-dark font-monospace">{{ \Carbon\Carbon::parse($s->dropoff_time)->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2 text-secondary d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                                                    <i class="bi bi-people-fill"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark">{{ $s->students->count() }} Siswa</span>
                                                    <div class="text-muted small text-truncate" style="max-width: 300px;">
                                                        {{ $s->students->take(3)->pluck('name')->join(', ') }}
                                                        @if($s->students->count() > 3)
                                                            , +{{ $s->students->count() - 3 }} lainnya
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('schedules.edit', $s->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-start" title="Edit Hari Ini">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                {{-- [MODIFIKASI] Tombol Hapus Single --}}
                                                <form action="{{ route('schedules.destroy', $s->id) }}" method="POST" id="form-single-{{ $s->id }}">
                                                    @csrf @method('DELETE')
                                                    {{-- Type button, onclick confirmDeleteSingle --}}
                                                    <button type="button" onclick="confirmDeleteSingle('{{ $s->id }}', '{{ $hariIndo }}')" class="btn btn-sm btn-light text-danger border shadow-sm rounded-end border-start-0" title="Hapus Jadwal">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                    <div class="bg-light rounded-circle p-4 mb-3">
                        <i class="bi bi-calendar-range display-4 text-secondary"></i>
                    </div>
                    <h5 class="fw-bold text-secondary">Belum ada jadwal rutin</h5>
                    <p class="text-muted small mb-3">Silakan buat jadwal baru untuk memulai operasional.</p>
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary rounded-pill px-4">
                        Buat Jadwal Sekarang
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Styling Accordion Custom */
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: var(--bs-dark);
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.05);
    }
    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23adb5bd'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transform: scale(0.8);
        transition: transform 0.2s ease-in-out;
    }
    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .accordion-button:hover {
        background-color: #fff;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }

    /* SweetAlert Custom Font */
    .swal2-popup {
        font-family: inherit !important;
        border-radius: 16px !important;
    }
</style>

<script>
    // 1. VALIDASI HAPUS RANGKAIAN (BULK DELETE)
    function confirmDeleteBulk(id, routeName) {
        Swal.fire({
            title: 'Hapus Rangkaian Jadwal?',
            html: `Anda akan menghapus SELURUH jadwal untuk <b>${routeName}</b>.<br><br>Semua jadwal (Senin-Minggu) pada rute ini akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Warna Merah
            cancelButtonColor: '#6c757d',  // Warna Abu-abu
            confirmButtonText: 'Ya, Hapus Semuanya!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-bulk-' + id).submit();
            }
        });
    }

    // 2. VALIDASI HAPUS SATUAN (SINGLE DELETE)
    function confirmDeleteSingle(id, dayName) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: `Apakah Anda yakin ingin menghapus jadwal hari ${dayName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-single-' + id).submit();
            }
        });
    }
</script>
@endsection