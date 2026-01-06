@extends('layouts.driver')

@section('content')
<style>
    /* --- CSS CLEAN & SAFE --- */
    
    /* 1. Header Hero (Safe Container) */
    .hero-card {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 20px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.25);
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Pola background halus */
    .hero-bg-pattern {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background-image: radial-gradient(circle at 100% 0%, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    /* 2. Progress Bar Refresh (Di dalam Hero) */
    .refresh-track {
        position: absolute; bottom: 0; left: 0; width: 100%; height: 4px;
        background: rgba(0,0,0,0.1);
    }
    .refresh-bar {
        height: 100%; background: rgba(255,255,255,0.8); width: 0%;
        transition: width 1s linear;
        box-shadow: 0 0 10px rgba(255,255,255,0.5);
    }

    /* 3. Card Task (Lebih Rapi) */
    .card-task {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .card-task:active { transform: scale(0.99); }

    /* Header Card Task (Warna Tipe) */
    .task-header {
        padding: 1rem 1.25rem;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #f8fafc;
    }
    .header-pickup { background-color: #fffbeb; color: #b45309; }
    .header-dropoff { background-color: #f0f9ff; color: #0369a1; }

    /* Body Card Task */
    .task-body { padding: 1.25rem; }

    /* 4. Time Display */
    .time-big { font-size: 2rem; font-weight: 800; line-height: 1; letter-spacing: -1px; color: #1e293b; }
    .time-label { font-size: 0.75rem; color: #64748b; font-weight: 500; text-transform: uppercase; }

    /* 5. Route Box */
    .route-box {
        background: #f8fafc; border-radius: 12px; padding: 1rem;
        display: flex; align-items: center; gap: 1rem;
        margin-top: 1rem; margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
    }
    .route-icon {
        width: 40px; height: 40px; background: white; color: #2563eb;
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* 6. Button Style */
    .btn-action {
        width: 100%; padding: 0.8rem; border-radius: 12px;
        font-weight: 700; font-size: 0.9rem; border: none;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        transition: 0.2s;
    }
    .btn-start { background: #2563eb; color: white; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }
    .btn-continue { background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
    .btn-history { background: white; color: #64748b; border: 1px solid #cbd5e1; }
</style>

<div class="container py-4">
    
    {{-- 1. HERO SECTION (CARD AMAN) --}}
    <div class="hero-card">
        <div class="hero-bg-pattern"></div>
        
        <div class="d-flex justify-content-between align-items-center position-relative z-1">
            {{-- Info Driver --}}
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 fw-normal px-2 py-1" style="font-size: 0.65rem;">
                        <i class="bi bi-person-badge-fill me-1"></i> DRIVER
                    </span>
                </div>
                <h4 class="fw-bold mb-0 text-white">{{ Auth::user()->name }}</h4>
            </div>

            {{-- Jam & Tanggal --}}
            <div class="text-end">
                <div class="fw-bold text-white fs-2 lh-1 mb-1" id="realtimeClock">--:--</div>
                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">
                    {{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D MMM') }}
                </small>
            </div>
        </div>

        {{-- Progress Bar (Menempel di bawah card) --}}
        <div class="refresh-track">
            <div class="refresh-bar" id="refreshBar"></div>
        </div>
    </div>

    {{-- 2. SECTION PENGUMUMAN --}}
    @if(isset($announcements) && $announcements->count() > 0)
    <div class="mb-4">
        <h6 class="fw-bold text-secondary mb-3 ps-2 border-start border-primary border-4" style="line-height: 1;">
            Informasi & Pengumuman
        </h6>
        <div class="row g-3">
            @foreach($announcements as $info)
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                    <div class="card-body py-3 position-relative">
                        <div class="position-absolute top-0 start-0 bottom-0 bg-primary" style="width: 4px;"></div>
                        <div class="ps-2">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-primary mb-0">{{ $info->title }}</h6>
                                <span class="badge bg-light text-secondary border rounded-pill fw-normal" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $info->created_at->translatedFormat('d M, H:i') }}
                                </span>
                            </div>
                            <p class="text-dark small mb-0 opacity-75" style="line-height: 1.6; white-space: pre-line;">{{ $info->content }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 3. DAFTAR TUGAS --}}
    @if($schedules->isEmpty())
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3 text-secondary">
                <i class="bi bi-mug-hot display-4"></i>
            </div>
            <h6 class="fw-bold text-dark">Jadwal Kosong</h6>
            <p class="text-muted small">Hari ini tidak ada perjalanan.</p>
        </div>
    @else
        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
            <h6 class="fw-bold text-secondary text-uppercase mb-0 small ls-1">Jadwal Hari Ini</h6>
            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $schedules->count() }} Trip</span>
        </div>
    @endif

    @foreach($schedules as $schedule)
        <div class="card-task">
            {{-- A. HEADER CARD --}}
            <div class="task-header {{ $schedule->type == 'pickup' ? 'header-pickup' : 'header-dropoff' }}">
                <div class="d-flex align-items-center gap-2">
                    @if($schedule->type == 'pickup')
                        <i class="bi bi-sun-fill"></i> <span class="fw-bold small">JEMPUT</span>
                    @else
                        <i class="bi bi-moon-stars-fill"></i> <span class="fw-bold small">ANTAR</span>
                    @endif
                </div>
                
                @if($schedule->today_trip)
                    @if($schedule->today_trip->status == 'finished')
                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Selesai</span>
                    @elseif($schedule->today_trip->status == 'active')
                        <span class="badge bg-primary"><span class="spinner-grow spinner-grow-sm" style="width:0.5rem;height:0.5rem;"></span> Jalan</span>
                    @endif
                @else
                    <span class="badge bg-secondary">Menunggu</span>
                @endif
            </div>

            <div class="task-body">
                {{-- B. JAM & TUJUAN --}}
                <div class="row align-items-center">
                    <div class="col-4 border-end">
                        <div class="text-center">
                            <div class="time-big">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</div>
                            <div class="time-label">Berangkat</div>
                        </div>
                    </div>
                    <div class="col-8 ps-4">
                        <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Rute Tujuan</small>
                        <div class="fw-bold text-dark text-truncate">{{ $schedule->route->name }}</div>
                        <div class="small text-secondary text-truncate">
                            <i class="bi bi-car-front me-1"></i> {{ $schedule->shuttle->plate_number }}
                        </div>
                    </div>
                </div>

                {{-- C. TOMBOL AKSI --}}
                <div class="mt-4">
                    @if($schedule->today_trip)
                        @if($schedule->today_trip->status == 'finished')
                            <a href="{{ route('driver.trip.history', $schedule->today_trip->id) }}" class="btn-action btn-history">
                                <i class="bi bi-journal-text"></i> Lihat Laporan
                            </a>
                        @else
                            <a href="{{ route('driver.trip.process', $schedule->today_trip->id) }}" class="btn-action btn-continue">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Lanjutkan Perjalanan
                            </a>
                        @endif
                    @else
                        {{-- FORM MULAI PERJALANAN (UPDATED) --}}
                        {{-- 1. Berikan ID Unik pada form berdasarkan ID Schedule --}}
                        <form id="form-start-{{ $schedule->id }}" action="{{ route('trips.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="driver_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="shuttle_id" value="{{ $schedule->shuttle_id }}">
                            <input type="hidden" name="route_id" value="{{ $schedule->route_id }}">
                            <input type="hidden" name="date" value="{{ $todayDate }}">
                            <input type="hidden" name="type" value="{{ $schedule->type }}">
                            
                            {{-- 2. Button Type jadi Button, panggil fungsi onclick custom --}}
                            <button type="button" class="btn-action btn-start" onclick="confirmStart('{{ $schedule->id }}', '{{ $schedule->route->name }}')">
                                <i class="bi bi-play-circle-fill"></i> Mulai Perjalanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <div style="height: 60px;"></div>
</div>

{{-- SCRIPT: JAM, REFRESH & VALIDASI SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Fungsi Validasi Mulai Perjalanan
    function confirmStart(scheduleId, routeName) {
        Swal.fire({
            title: 'Mulai Perjalanan?',
            text: "Pastikan Anda siap berangkat untuk rute: " + routeName,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // Warna Biru
            cancelButtonColor: '#64748b',  // Warna Abu-abu
            confirmButtonText: 'Ya, Berangkat!',
            cancelButtonText: 'Nanti Dulu',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading sebentar sebelum submit (opsional, untuk efek visual)
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Selamat bekerja!',
                    timer: 1000,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    }
                }).then(() => {
                    // Cari form berdasarkan ID unik dan submit
                    document.getElementById('form-start-' + scheduleId).submit();
                });
            }
        })
    }

    // 2. Logic Clock & Refresh
    document.addEventListener('DOMContentLoaded', function() {
        const refreshTime = 60; 
        let timeLeft = refreshTime;
        const progressBar = document.getElementById('refreshBar');

        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
            const el = document.getElementById('realtimeClock');
            if(el) el.textContent = timeString;
        }

        function startAutoRefresh() {
            const timer = setInterval(() => {
                timeLeft--;
                if (progressBar) {
                    progressBar.style.width = ((refreshTime - timeLeft) / refreshTime) * 100 + '%';
                }
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    sessionStorage.setItem('scrollPos', window.scrollY);
                    window.location.reload();
                }
            }, 1000);
        }

        const scrollPos = sessionStorage.getItem('scrollPos');
        if (scrollPos) {
            window.scrollTo(0, parseInt(scrollPos));
            sessionStorage.removeItem('scrollPos');
        }

        setInterval(updateClock, 1000);
        updateClock();
        startAutoRefresh();
    });
</script>
@endsection