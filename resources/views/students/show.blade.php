@extends('layouts.admin')

@section('content')
<div class="container py-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">📋 Detail Informasi Siswa</h3>
            <p class="text-muted mb-0">ID Sistem: <span class="badge bg-secondary">#{{ $student->id }}</span></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('students.search') }}" class="btn btn-light border shadow-sm rounded-pill px-4">
                <i class="bi bi-search me-1"></i> Cari Lain
            </a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning fw-bold shadow-sm rounded-pill px-4">
                <i class="bi bi-pencil-square me-1"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="row g-4">
        
        {{-- KOLOM KIRI: BIODATA & ORTU --}}
        <div class="col-lg-4">
            
            {{-- Card Profil --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 text-center p-4 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 h-25 bg-light"></div>
                <div class="position-relative">
                    <div class="mb-3 d-inline-block">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle shadow border border-4 border-white" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold mx-auto border border-4 border-white shadow" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $student->name }}</h4>
                    <span class="badge bg-white text-dark border px-3 py-2 rounded-pill mt-2 shadow-sm">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $student->complex->name ?? 'Tanpa Komplek' }}
                    </span>
                </div>
            </div>

            {{-- Card Wali Murid --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle me-2">
                        <i class="bi bi-person-heart"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-dark">Data Wali Murid</h6>
                 </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Nama Orang Tua</label>
                        <div class="fw-bold text-dark fs-5">{{ $student->parent->name ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Kontak / HP</label>
                        <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded-3 border border-light">
                            <span class="fw-bold text-dark ps-2">{{ $student->parent->phone ?? '-' }}</span>
                            @if($student->parent && $student->parent->phone)
                                <a href="https://wa.me/{{ $student->parent->phone }}" target="_blank" class="btn btn-success btn-sm rounded-circle" title="Chat WA">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="small text-muted text-uppercase fw-bold mb-1">Detail Alamat</label>
                        <div class="bg-light p-3 rounded-3 border border-light small text-secondary">
                            <i class="bi bi-pin-map-fill me-1 text-danger"></i>
                            {{ $student->address_note ?? 'Belum ada catatan alamat detail.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: JADWAL OPERASIONAL --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-2">
                            <i class="bi bi-bus-front-fill"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Jadwal & Driver Langganan</h6>
                    </div>
                    @if(isset($schedules) && $schedules->count() > 0)
                        <span class="badge bg-light text-secondary border">{{ $schedules->count() }} Jadwal Aktif</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    
                    @if($schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Hari</th>
                                        <th>Rute</th>
                                        <th>Driver</th>
                                        <th>Mobil</th>
                                        <th class="text-end pe-4">Jam Layanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $indoDays = [
                                            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                                            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
                                        ];
                                    @endphp
                                    @foreach($schedules as $sched)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-primary">{{ $indoDays[$sched->day_of_week] ?? $sched->day_of_week }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $sched->route->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-1 me-2 text-center" style="width: 30px; height: 30px;">
                                                    <i class="bi bi-person-fill small text-secondary"></i>
                                                </div>
                                                {{ $sched->driver->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="lh-1">
                                                <div class="fw-bold small">{{ $sched->shuttle->plate_number ?? '-' }}</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $sched->shuttle->car_model ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex flex-column gap-1 align-items-end">
                                                @if($sched->pickup_time)
                                                    <span class="badge bg-warning text-dark border border-warning" title="Jemput">
                                                        <i class="bi bi-sun me-1"></i> {{ \Carbon\Carbon::parse($sched->pickup_time)->format('H:i') }}
                                                    </span>
                                                @endif
                                                @if($sched->dropoff_time)
                                                    <span class="badge bg-info text-white border border-info" title="Antar">
                                                        <i class="bi bi-moon me-1"></i> {{ \Carbon\Carbon::parse($sched->dropoff_time)->format('H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="bi bi-calendar-x text-muted display-4 opacity-25"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Belum Ada Jadwal</h5>
                            <p class="text-muted small mb-4">Siswa ini belum dimasukkan ke dalam jadwal rute manapun.</p>
                            <a href="{{ route('schedules.create') }}" class="btn btn-primary px-4 rounded-pill">Buat Jadwal Sekarang</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection