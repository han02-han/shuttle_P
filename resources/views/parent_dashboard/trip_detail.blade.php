@extends('layouts.parent')

@section('content')
<div class="container py-3">
    
    <div class="mb-4 text-center">
        <h6 class="text-muted text-uppercase ls-1 mb-2">Detail Perjalanan</h6>
        <h2 class="fw-bold text-dark mb-3">{{ \Carbon\Carbon::parse($passenger->trip->date)->format('d M Y') }}</h2>
        
        @if($passenger->status == 'pending')
            <span class="badge bg-warning text-dark px-4 py-2 rounded-pill fs-6 shadow-sm">⏳ MENUNGGU JEMPUTAN</span>
        @elseif($passenger->status == 'picked_up')
            <span class="badge bg-primary px-4 py-2 rounded-pill fs-6 shadow-sm">🚌 SEDANG DIJALAN</span>
        @elseif($passenger->status == 'dropped_off')
            <span class="badge bg-success px-4 py-2 rounded-pill fs-6 shadow-sm">✅ SUDAH SAMPAI</span>
        @elseif($passenger->status == 'absent')
            <span class="badge bg-danger px-4 py-2 rounded-pill fs-6 shadow-sm">❌ TIDAK HADIR</span>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header border-0 py-3 bg-warning bg-opacity-10">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-vcard-fill fs-5 text-warning"></i>
                <h6 class="fw-bold mb-0 text-dark">Informasi Driver & Armada</h6>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-3 text-center">
                    @if($passenger->trip->driver->photo)
                        <img src="{{ asset('storage/' . $passenger->trip->driver->photo) }}" 
                             class="rounded-circle object-fit-cover shadow-sm" 
                             style="width: 65px; height: 65px; border: 3px solid white;">
                    @else
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                             style="width: 65px; height: 65px; font-size: 1.5rem; border: 3px solid white;">
                            {{ substr($passenger->trip->driver->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <div class="col-9 ps-3">
                    <h5 class="fw-bold mb-1 text-dark">{{ $passenger->trip->driver->name }}</h5>
                    <div class="mb-2 text-muted small">Driver Resmi</div>
                    
                    {{-- LOGIKA FORMAT NOMOR WA (TANPA UBAH MODEL) --}}
                    @php
                        $driverPhone = $passenger->trip->driver->phone;
                        // Hapus karakter selain angka
                        $driverPhone = preg_replace('/[^0-9]/', '', $driverPhone);
                        // Jika diawali angka 0, ganti dengan 62
                        if (substr($driverPhone, 0, 1) == '0') {
                            $driverPhone = '62' . substr($driverPhone, 1);
                        }
                    @endphp

                    <a href="https://wa.me/{{ $driverPhone }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                        <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
                    </a>
                </div>
            </div>

            <hr class="border-light my-4">

            <div class="row g-3">
                <div class="col-6 border-end">
                    <small class="text-muted d-block mb-1">KENDARAAN</small>
                    <div class="fw-bold text-dark fs-5">{{ $passenger->trip->shuttle->plate_number }}</div>
                    <div class="small text-secondary">{{ $passenger->trip->shuttle->car_model }}</div>
                </div>
                <div class="col-6 ps-4">
                    <small class="text-muted d-block mb-1">RUTE / TUJUAN</small>
                    <div class="fw-bold text-primary">{{ $passenger->trip->route->name }}</div>
                    <div class="small text-secondary">
                        {{ $passenger->trip->type == 'pickup' ? 'Menuju Sekolah' : 'Menuju Rumah' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-secondary mb-4">Kronologi Waktu</h6>
            
            <div class="d-flex mb-4 position-relative">
                <div class="me-3 text-center" style="width: 50px;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm
                        {{ $passenger->picked_at ? 'bg-primary text-white' : 'bg-light text-muted border' }}" 
                        style="width: 45px; height: 45px;">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                    </div>
                    <div class="vr position-absolute start-0 ms-4 mt-1" style="height: 100%; left: 22px; opacity: 0.2; z-index: 0;"></div>
                </div>
                <div class="ps-2">
                    <small class="text-muted fw-bold d-block">WAKTU NAIK / JEMPUT</small>
                    @if($passenger->picked_at)
                        <h4 class="fw-bold text-dark mb-0">{{ \Carbon\Carbon::parse($passenger->picked_at)->format('H:i') }} <span class="fs-6 text-muted">WIB</span></h4>
                        <small class="text-success"><i class="bi bi-check-all"></i> Terkonfirmasi</small>
                    @else
                        <h5 class="text-muted mb-0 opacity-50">-- : --</h5>
                        <small class="text-secondary fst-italic">Belum dijemput</small>
                    @endif
                </div>
            </div>

            <div class="d-flex position-relative z-1">
                <div class="me-3 text-center" style="width: 50px;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm
                        {{ $passenger->dropped_at ? 'bg-success text-white' : 'bg-light text-muted border' }}" 
                        style="width: 45px; height: 45px;">
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                </div>
                <div class="ps-2">
                    <small class="text-muted fw-bold d-block">WAKTU TURUN / SAMPAI</small>
                    @if($passenger->dropped_at)
                        <h4 class="fw-bold text-dark mb-0">{{ \Carbon\Carbon::parse($passenger->dropped_at)->format('H:i') }} <span class="fs-6 text-muted">WIB</span></h4>
                        <small class="text-success"><i class="bi bi-check-all"></i> Terkonfirmasi</small>
                    @else
                        <h5 class="text-muted mb-0 opacity-50">-- : --</h5>
                        <small class="text-secondary fst-italic">Belum sampai</small>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="d-grid mb-5">
        <a href="{{ route('parents.dashboard') }}" class="btn btn-light py-3 rounded-3 fw-bold text-secondary shadow-sm border">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection