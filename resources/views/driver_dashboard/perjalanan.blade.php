@extends('layouts.driver')

@section('content')
{{-- Library SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        transition: all 0.3s ease;
    }

    /* 2. Loading Bar Kecil (Indikator Update Background) */
    .update-indicator {
        position: absolute; top: 0; left: 0; width: 100%; height: 3px;
        background: transparent; overflow: hidden;
    }
    .update-indicator .bar {
        width: 100%; height: 100%; background: #3b82f6; 
        transform: translateX(-100%);
        animation: loading 1.5s infinite;
        display: none; /* Muncul hanya saat fetching data */
    }
    @keyframes loading {
        100% { transform: translateX(100%); }
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

    /* Indikator Status (Garis Kiri) */
    .status-stripe { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; }
    .stripe-pending { background: #cbd5e1; }
    .stripe-waiting { background: #eab308; } 
    .stripe-active { background: #f59e0b; } 
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
        cursor: pointer;
    }
    
    .btn-pickup { background: #f59e0b; color: white; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2); }
    .btn-waiting { background: #eab308; color: white; box-shadow: 0 4px 10px rgba(234, 179, 8, 0.2); }
    .btn-dropoff { background: #10b981; color: white; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }
    .btn-skip { background: white; color: #ef4444; border: 1px solid #fee2e2; }

    /* Tombol Selesai Header */
    .btn-finish-header {
        background: #1e293b; color: white;
        border-radius: 50px; padding: 0.6rem 1.5rem;
        font-weight: 600; font-size: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
        border: none;
        cursor: pointer;
    }

    /* Area Klik Info Siswa */
    .clickable-area { transition: background-color 0.2s; border-radius: 12px; }
    .clickable-area:active { background-color: rgba(0,0,0,0.03); }
    
    /* Style Modal */
    .detail-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 4px; display: block; }
    .detail-value { font-size: 1rem; font-weight: 600; color: #1e293b; }
    .detail-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; }
</style>

{{-- Kalkulasi Progress (Hitung Awal) --}}
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
        {{-- Indikator loading halus (muncul saat update background) --}}
        <div class="update-indicator"><div class="bar" id="loadingIndicator"></div></div>

        {{-- Bagian Header yang akan di-refresh AJAX (ID: header-content) --}}
        <div id="header-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="flex: 1; min-width: 0; margin-right: 15px;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($trip->type == 'pickup')
                            <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.65rem;"><i class="bi bi-sun-fill me-1"></i> PAGI</span>
                        @else
                            <span class="badge bg-info text-white rounded-pill" style="font-size: 0.65rem;"><i class="bi bi-moon-fill me-1"></i> SORE</span>
                        @endif
                        <span class="text-secondary small fw-bold" id="realtimeClock">--:--</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-0 text-truncate">{{ $trip->route->name ?? 'Nama Rute' }}</h5>
                </div>
                
                {{-- Form Selesai Perjalanan (Tombol Hitam) --}}
                <form id="form-finish-trip" action="{{ route('driver.trip.finish', $trip->id) }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmFinishTrip()" class="btn-finish-header">
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
    </div>

    {{-- 2. LIST KARTU SISWA (ID: passenger-list-container untuk AJAX) --}}
    <div id="passenger-list-container" class="pb-5 mb-5">
        @forelse($passengers as $p)
            @php
                // Logika Warna Status
                $stripeClass = 'stripe-pending';
                $cardBg = 'bg-white';
                if($p->status == 'waiting') { $stripeClass = 'stripe-waiting'; $cardBg = 'bg-waiting'; }
                elseif($p->status == 'picked_up') { $stripeClass = 'stripe-active'; if($trip->type != 'pickup') $cardBg = 'bg-active'; } 
                elseif($p->status == 'dropped_off') { $stripeClass = 'stripe-done'; $cardBg = 'bg-done'; }
                elseif($p->status == 'skipped') { $stripeClass = 'stripe-skip'; $cardBg = 'bg-skip'; }
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
                    </div>
                    
                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $p->student->name }}</h6>
                            @if($p->status == 'waiting') <span class="badge bg-warning text-dark rounded-pill" style="font-size:0.6rem;">MENUNGGU</span>
                            @elseif($p->status == 'picked_up') <span class="badge bg-primary rounded-pill" style="font-size:0.6rem;">NAIK</span>
                            @elseif($p->status == 'dropped_off') <span class="badge bg-success rounded-pill" style="font-size:0.6rem;">SAMPAI</span>
                            @elseif($p->status == 'skipped') <span class="badge bg-danger rounded-pill" style="font-size:0.6rem;">SKIP</span>
                            @endif
                        </div>
                        <div class="text-muted small text-truncate">{{ $p->student->complex->name ?? 'Umum' }}</div>
                    </div>
                </div>

                {{-- TOMBOL AKSI DENGAN VALIDASI SWEETALERT --}}
                <div class="ps-2">
                    @if($p->status == 'pending')
                        <div class="row g-2">
                            <div class="col-8">
                                @if($trip->type == 'pickup')
                                    {{-- TOMBOL MENUNGGU (Jemputan) --}}
                                    <form id="form-waiting-{{ $p->id }}" action="{{ route('driver.passenger.waiting', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="confirmWaiting('{{ $p->id }}', '{{ $p->student->name }}')" class="btn-action btn-waiting">
                                            <i class="bi bi-geo-alt-fill fs-5"></i> SAMPAI TITIK
                                        </button>
                                    </form>
                                @else
                                    {{-- TOMBOL NAIK (Antaran Sore - Langsung Naik) --}}
                                    <form id="form-pickup-{{ $p->id }}" action="{{ route('driver.passenger.pickup', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="confirmPickup('{{ $p->id }}', '{{ $p->student->name }}')" class="btn-action btn-pickup">
                                            <i class="bi bi-box-arrow-in-right fs-5"></i> SISWA NAIK
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="col-4">
                                <form id="form-skip-{{ $p->id }}" action="{{ route('driver.passenger.skip', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="confirmSkip('{{ $p->id }}', '{{ $p->student->name }}')" class="btn-action btn-skip">SKIP</button>
                                </form>
                            </div>
                        </div>

                    @elseif($p->status == 'waiting')
                        <div class="row g-2">
                            <div class="col-8">
                                <form id="form-pickup-{{ $p->id }}" action="{{ route('driver.passenger.pickup', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="confirmPickup('{{ $p->id }}', '{{ $p->student->name }}')" class="btn-action btn-pickup">
                                        <i class="bi bi-box-arrow-in-right fs-5"></i> SISWA NAIK
                                    </button>
                                </form>
                            </div>
                            <div class="col-4">
                                <form id="form-skip-{{ $p->id }}" action="{{ route('driver.passenger.skip', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="confirmSkip('{{ $p->id }}', '{{ $p->student->name }}')" class="btn-action btn-skip">SKIP</button>
                                </form>
                            </div>
                        </div>

                    @elseif($p->status == 'picked_up' && $trip->type != 'pickup') 
                        <form id="form-dropoff-{{ $p->id }}" action="{{ route('driver.passenger.dropoff', $p->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmDropoff('{{ $p->id }}', '{{ $p->student->name }}')" class="btn-action btn-dropoff">
                                <i class="bi bi-house-check-fill fs-5"></i> TURUN (SAMPAI)
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- MODAL DETAIL SISWA --}}
            <div class="modal fade" id="studentModal-{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-bottom-0 pb-0 bg-white sticky-top">
                            <h5 class="modal-title fw-bold">Detail Lengkap Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Konten Modal (Foto, Nama, dll) --}}
                            <div class="text-center mb-4">
                                @if($p->student->photo)
                                    <img src="{{ asset('storage/'.$p->student->photo) }}" class="rounded-circle shadow mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="avatar-circle mx-auto shadow mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">{{ substr($p->student->name, 0, 1) }}</div>
                                @endif
                                <h3 class="fw-bold mb-0 text-dark">{{ $p->student->name }}</h3>
                            </div>
                            
                            {{-- Info Komplek --}}
                            <div class="detail-box">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <i class="bi bi-geo-alt-fill text-danger fs-3"></i>
                                    <div>
                                        <span class="detail-label">Alamat / Komplek</span>
                                        <div class="detail-value">{{ $p->student->complex->name ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="bg-white border rounded p-2 text-muted small mt-2">
                                    {{ $p->student->address_note ?? 'Tidak ada catatan alamat' }}
                                </div>
                            </div>

                            {{-- Info Wali Murid --}}
                            <div class="detail-box">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <i class="bi bi-people-fill text-primary fs-3"></i>
                                    <div>
                                        <span class="detail-label">Orang Tua</span>
                                        <div class="detail-value">{{ $p->student->parent->name ?? '-' }}</div>
                                    </div>
                                </div>
                                @if(!empty($p->student->parent->phone))
                                    @php
                                        $waNum = $p->student->parent->phone;
                                        if(substr($waNum, 0, 1) == '0') $waNum = '62' . substr($waNum, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $waNum }}?text=Halo" target="_blank" class="btn btn-success w-100 btn-sm fw-bold text-white mt-2">
                                        <i class="bi bi-whatsapp me-1"></i> Hubungi WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-secondary w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Modal --}}

        @empty
            <div class="text-center py-5">
                <i class="bi bi-people text-muted display-1 opacity-25"></i>
                <p class="text-muted mt-3">Tidak ada data penumpang.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    // --- 1. VALIDASI SWEETALERT2 ---

    // Validasi Selesai Trip
    function confirmFinishTrip() {
        Swal.fire({
            title: 'Selesaikan Perjalanan?',
            text: "Semua siswa sudah diantar/jemput?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-finish-trip').submit();
        });
    }

    // Validasi Sampai Titik (Waiting)
    function confirmWaiting(id, name) {
        Swal.fire({
            title: 'Sudah di Lokasi?',
            text: "Konfirmasi sampai jemputan: " + name,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#eab308',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Sampai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-waiting-' + id).submit();
        });
    }

    // Validasi Siswa Naik (Pickup)
    function confirmPickup(id, name) {
        Swal.fire({
            title: 'Siswa Naik?',
            text: name + " sudah masuk ke mobil?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Naik',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-pickup-' + id).submit();
        });
    }

    // Validasi Siswa Turun (Dropoff)
    function confirmDropoff(id, name) {
        Swal.fire({
            title: 'Siswa Turun?',
            text: name + " sudah sampai tujuan?",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-dropoff-' + id).submit();
        });
    }

    // Validasi Skip
    function confirmSkip(id, name) {
        Swal.fire({
            title: 'Lewati Siswa?',
            text: "Anda yakin melewati " + name + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Lewati',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-skip-' + id).submit();
        });
    }

    // --- 2. LOGIC AUTO REFRESH (AJAX) ---
    document.addEventListener('DOMContentLoaded', function() {
        const AUTO_REFRESH_INTERVAL = 5000; // 5 Detik

        // Clock Update
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(/\./g, ':');
            const el = document.getElementById('realtimeClock');
            if(el) el.textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Silent Refresh Logic
        setInterval(() => {
            // Cek kondisi agar tidak ganggu user
            if(document.querySelector('.modal.show')) return; // Jika modal buka, jangan refresh
            if(Swal.isVisible()) return; // Jika alert buka, jangan refresh

            const loadingBar = document.getElementById('loadingIndicator');
            if(loadingBar) loadingBar.style.display = 'block';

            // Ambil konten halaman yang sama di background
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Update List Siswa
                    const newList = doc.getElementById('passenger-list-container');
                    const currentList = document.getElementById('passenger-list-container');
                    if(newList && currentList) {
                        currentList.innerHTML = newList.innerHTML;
                    }

                    // 2. Update Header Content (Progress Bar)
                    const newHeader = doc.getElementById('header-content');
                    const currentHeader = document.getElementById('header-content');
                    if(newHeader && currentHeader) {
                        currentHeader.innerHTML = newHeader.innerHTML;
                    }

                    if(loadingBar) loadingBar.style.display = 'none';
                })
                .catch(err => {
                    console.error('Auto refresh error:', err);
                    if(loadingBar) loadingBar.style.display = 'none';
                });

        }, AUTO_REFRESH_INTERVAL);
    });
</script>
@endsection