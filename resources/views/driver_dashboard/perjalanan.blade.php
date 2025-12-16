@extends('layouts.driver')

@section('content')
<style>
    body { background-color: #f1f5f9; font-family: 'Poppins', sans-serif; }
    
    /* 1. Sticky Header */
    .sticky-header {
        position: sticky; top: 0; z-index: 1020;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        margin-left: -12px; margin-right: -12px;
        padding: 1.25rem 1.5rem;
    }

    /* 2. Loading Bar */
    .refresh-track {
        position: absolute; top: 0; left: 0; width: 100%; height: 4px;
        background: #f1f5f9;
    }
    .refresh-bar {
        height: 100%; background: #2563eb; width: 0%;
        transition: width 1s linear;
    }

    /* 3. Card Siswa */
    .card-student {
        background: white; border: none; border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 1rem; position: relative; overflow: hidden;
        transition: transform 0.2s;
        border: 1px solid #f1f5f9;
    }
    .card-student:active { transform: scale(0.99); }

    /* Indikator Status */
    .status-stripe { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; }
    .stripe-pending { background: #cbd5e1; }
    .stripe-waiting { background: #eab308; } /* Kuning Gelap */
    .stripe-active { background: #f59e0b; } /* Orange */
    .stripe-done { background: #10b981; }
    .stripe-skip { background: #ef4444; }

    /* Background Card */
    .bg-done { background-color: #f0fdf4; border-color: #bbf7d0; }
    .bg-skip { background-color: #fef2f2; border-color: #fecaca; opacity: 0.8; }
    .bg-active { background-color: #fffbeb; border-color: #fde68a; }
    .bg-waiting { background-color: #fffde7; border-color: #fef08a; }

    /* Avatar */
    .avatar-circle {
        width: 45px; height: 45px;
        background: #f1f5f9; color: #64748b;
        border-radius: 50%; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Tombol Aksi */
    .btn-action {
        width: 100%; border: none; border-radius: 10px;
        padding: 12px; font-weight: 700; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-transform: uppercase; letter-spacing: 0.5px;
        transition: 0.2s;
    }
    
    .btn-pickup { background: #f59e0b; color: white; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2); }
    .btn-pickup:active { background: #d97706; transform: translateY(2px); }

    .btn-waiting { background: #eab308; color: white; box-shadow: 0 4px 10px rgba(234, 179, 8, 0.2); }
    .btn-waiting:active { background: #ca8a04; transform: translateY(2px); }

    .btn-dropoff { background: #10b981; color: white; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }
    .btn-dropoff:active { background: #059669; transform: translateY(2px); }

    .btn-skip { background: white; color: #ef4444; border: 1px solid #fee2e2; }
    .btn-skip:active { background: #fef2f2; }

    /* Tombol Selesai Header */
    .btn-finish-header {
        background: #1e293b; color: white;
        border-radius: 50px; padding: 0.6rem 1.5rem;
        font-weight: 600; font-size: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
        border: none;
    }
    .btn-finish-header:active { transform: scale(0.95); }

    /* Area Klik Info Siswa */
    .clickable-area {
        transition: background-color 0.2s;
        border-radius: 12px;
    }
    .clickable-area:active {
        background-color: rgba(0,0,0,0.03);
    }
    
    /* Style untuk Modal Detail */
    .detail-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }
    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }
    .detail-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>

{{-- Kalkulasi Progress --}}
@php
    $total = $passengers->count();
    $done = $passengers->filter(function($p) {
        return $p->status != 'pending' && $p->status != 'waiting';
    })->count();
    
    $percent = $total > 0 ? ($done/$total)*100 : 0;
@endphp

<div class="container pb-5">

    {{-- 1. STICKY HEADER --}}
    <div class="sticky-header mb-4">
        <div class="refresh-track"><div class="refresh-bar" id="refreshBar"></div></div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div style="flex: 1; min-width: 0; margin-right: 15px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    @if($trip->type == 'pickup')
                        <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.65rem;">
                            <i class="bi bi-sun-fill me-1"></i> PAGI
                        </span>
                    @else
                        <span class="badge bg-info text-white rounded-pill" style="font-size: 0.65rem;">
                            <i class="bi bi-moon-fill me-1"></i> SORE
                        </span>
                    @endif
                    <span class="text-secondary small fw-bold" id="realtimeClock">--:--</span>
                </div>
                <h5 class="fw-bold text-dark mb-0 text-truncate">{{ $trip->route->name ?? 'Nama Rute' }}</h5>
            </div>
            
            <form action="{{ route('driver.trip.finish', $trip->id) }}" method="POST" onsubmit="return confirm('Selesaikan seluruh perjalanan ini?');">
                @csrf
                <button type="submit" class="btn-finish-header">
                    <i class="bi bi-flag-fill text-warning"></i> Selesai
                </button>
            </form>
        </div>

        {{-- Progress Bar --}}
        <div class="d-flex align-items-center gap-2 mt-2">
            <div class="progress flex-grow-1" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
            </div>
            <small class="fw-bold text-muted" style="font-size: 0.75rem;">{{ $done }}/{{ $total }} Siswa</small>
        </div>
    </div>

    {{-- 2. LIST KARTU SISWA --}}
    <div class="pb-5 mb-5">
        @forelse($passengers as $p)
            @php
                // Logika Warna berdasarkan Status
                $stripeClass = 'stripe-pending';
                $cardBg = 'bg-white';
                
                if($p->status == 'waiting') {
                    $stripeClass = 'stripe-waiting'; $cardBg = 'bg-waiting';
                }
                elseif($p->status == 'picked_up') {
                    $stripeClass = 'stripe-active';
                    if($trip->type != 'pickup') $cardBg = 'bg-active'; 
                } 
                elseif($p->status == 'dropped_off') {
                    $stripeClass = 'stripe-done'; $cardBg = 'bg-done'; 
                }
                elseif($p->status == 'skipped') {
                    $stripeClass = 'stripe-skip'; $cardBg = 'bg-skip'; 
                }
            @endphp

            <div class="card-student p-3 {{ $cardBg }}">
                <div class="status-stripe {{ $stripeClass }}"></div>
                
                {{-- INFO SISWA (KLIK UNTUK MODAL) --}}
                <div class="d-flex align-items-center mb-3 ps-2 clickable-area" 
                     style="cursor: pointer;"
                     data-bs-toggle="modal" 
                     data-bs-target="#studentModal-{{ $p->id }}">
                     
                    <div class="me-3 position-relative">
                        @if($p->student->photo)
                            <img src="{{ asset('storage/'.$p->student->photo) }}" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="avatar-circle" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                {{ substr($p->student->name, 0, 1) }}
                            </div>
                        @endif
                        {{-- Icon Info kecil --}}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info border border-white p-1">
                            <i class="bi bi-search" style="font-size: 0.6rem;"></i>
                        </span>
                    </div>
                    
                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $p->student->name }}</h6>
                            
                            {{-- Badge Status --}}
                            @if($p->status == 'waiting')
                                <span class="badge bg-warning text-dark rounded-pill" style="font-size:0.6rem;">MENUNGGU</span>
                            @elseif($p->status == 'picked_up')
                                <span class="badge bg-primary rounded-pill" style="font-size:0.6rem;">NAIK</span>
                            @elseif($p->status == 'dropped_off')
                                <span class="badge bg-success rounded-pill" style="font-size:0.6rem;">SAMPAI</span>
                            @elseif($p->status == 'skipped')
                                <span class="badge bg-danger rounded-pill" style="font-size:0.6rem;">SKIP</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-geo-alt-fill text-danger me-1" style="font-size: 0.7rem;"></i>
                            <span class="text-truncate fw-bold">
                                {{ $p->student->complex->name ?? 'Umum' }}
                            </span>
                            <span class="mx-1">•</span>
                            <span class="text-primary fw-bold" style="font-size: 0.7rem;">Detail Lengkap</span>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="ps-2">
                    @if($p->status == 'pending')
                        <div class="row g-2">
                            <div class="col-8">
                                @if($trip->type == 'pickup')
                                    <form action="{{ route('driver.passenger.waiting', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-waiting">
                                            <i class="bi bi-geo-alt-fill fs-5"></i> SAMPAI TITIK
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('driver.passenger.pickup', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-pickup">
                                            <i class="bi bi-box-arrow-in-right fs-5"></i> SISWA NAIK
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="col-4">
                                <form action="{{ route('driver.passenger.skip', $p->id) }}" method="POST" onsubmit="return confirm('Lewati siswa ini?');">
                                    @csrf
                                    <button type="submit" class="btn-action btn-skip">SKIP</button>
                                </form>
                            </div>
                        </div>

                    @elseif($p->status == 'waiting')
                         <div class="row g-2">
                            <div class="col-8">
                                <form action="{{ route('driver.passenger.pickup', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-pickup">
                                        <i class="bi bi-box-arrow-in-right fs-5"></i> SISWA NAIK
                                    </button>
                                </form>
                            </div>
                            <div class="col-4">
                                <form action="{{ route('driver.passenger.skip', $p->id) }}" method="POST" onsubmit="return confirm('Lewati siswa ini?');">
                                    @csrf
                                    <button type="submit" class="btn-action btn-skip">SKIP</button>
                                </form>
                            </div>
                        </div>

                    @elseif($p->status == 'picked_up' && $trip->type != 'pickup') 
                        <form action="{{ route('driver.passenger.dropoff', $p->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-action btn-dropoff">
                                <i class="bi bi-house-check-fill fs-5"></i> TURUN (SAMPAI)
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- MODAL DETAIL SISWA LENGKAP                 --}}
            {{-- ========================================== --}}
            <div class="modal fade" id="studentModal-{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow">
                        
                        {{-- Header Modal --}}
                        <div class="modal-header border-bottom-0 pb-0 bg-white sticky-top">
                            <h5 class="modal-title fw-bold">Detail Lengkap Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Foto & Nama Utama --}}
                            <div class="text-center mb-4">
                                @if($p->student->photo)
                                    <img src="{{ asset('storage/'.$p->student->photo) }}" class="rounded-circle shadow mb-3" style="width: 110px; height: 110px; object-fit: cover; border: 4px solid #fff;">
                                @else
                                    <div class="avatar-circle mx-auto shadow mb-3" style="width: 110px; height: 110px; font-size: 2.5rem; border: 4px solid #fff;">
                                        {{ substr($p->student->name, 0, 1) }}
                                    </div>
                                @endif
                                <h3 class="fw-bold mb-0 text-dark">{{ $p->student->name }}</h3>
                                <p class="text-muted small">Status: 
                                    @if($p->status == 'pending') <span class="badge bg-secondary">Belum Dijemput</span>
                                    @elseif($p->status == 'waiting') <span class="badge bg-warning text-dark">Driver Menunggu</span>
                                    @elseif($p->status == 'picked_up') <span class="badge bg-primary">Di Dalam Mobil</span>
                                    @elseif($p->status == 'dropped_off') <span class="badge bg-success">Selesai</span>
                                    @endif
                                </p>
                            </div>

                            {{-- BOX 1: LOKASI & ALAMAT --}}
                            <div class="detail-box">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="me-3">
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-geo-alt-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="detail-label">Komplek Perumahan</span>
                                        <div class="detail-value text-danger">{{ $p->student->complex->name ?? 'Tidak ada data komplek' }}</div>
                                    </div>
                                </div>

                                <div>
                                    <span class="detail-label"><i class="bi bi-signpost-2 me-1"></i> Catatan Alamat / Rumah</span>
                                    <div class="p-2 bg-white border rounded text-dark mt-1">
                                        @if(!empty($p->student->address_note))
                                            {{ $p->student->address_note }}
                                        @else
                                            <span class="text-muted fst-italic small">Tidak ada catatan alamat.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- BOX 2: INFORMASI ORANG TUA --}}
                            <div class="detail-box">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-people-fill fs-5"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="detail-label">Wali Murid (Orang Tua)</span>
                                        <div class="detail-value">{{ $p->student->parent->name ?? 'Data tidak tersedia' }}</div>
                                    </div>
                                </div>
                                
                                {{-- Tombol Kontak Ortu --}}
                                @if(!empty($p->student->parent->phone))
                                    <div class="row g-2 mt-2">
                                        <div class="col-6">
                                            <a href="tel:{{ $p->student->parent->phone }}" class="btn btn-outline-dark w-100 btn-sm fw-bold">
                                                <i class="bi bi-telephone-fill me-1"></i> Telepon
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            @php
                                                // Format nomor HP ke format WA (62...)
                                                $waNum = $p->student->parent->phone;
                                                if(substr($waNum, 0, 1) == '0') {
                                                    $waNum = '62' . substr($waNum, 1);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $waNum }}?text=Halo%20saya%20Driver%20Jemputan%20{{ $p->student->name }}" target="_blank" class="btn btn-success w-100 btn-sm fw-bold text-white">
                                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted small fst-italic border-top pt-2">
                                        <i class="bi bi-telephone-x me-1"></i> Nomor telepon orang tua tidak tersedia.
                                    </div>
                                @endif
                            </div>

                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-secondary w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- AKHIR MODAL --}}

        @empty
            <div class="text-center py-5">
                <i class="bi bi-people text-muted display-1 opacity-25"></i>
                <p class="text-muted mt-3">Tidak ada data penumpang.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const refreshTime = 5; 
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
                // Jangan refresh jika modal sedang terbuka
                if(document.querySelector('.modal.show')) {
                    return; 
                }

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