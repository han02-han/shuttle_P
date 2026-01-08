@extends('layouts.parent')

@section('content')
<div class="container py-4">
    
    {{-- WELCOME HEADER --}}
    <div class="welcome-header mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <p class="text-muted mb-1 small fw-semibold">Selamat Datang,</p>
                <h2 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h2>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge-date">
                    <i class="bi bi-calendar-event me-2"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- SECTION PENGUMUMAN --}}
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

    {{-- SECTION STATUS --}}
    <div class="section-title mb-4">
        <div class="d-flex align-items-center">
            <div class="title-indicator"></div>
            <h5 class="fw-bold text-dark mb-0">Status Anak Hari Ini</h5>
            {{-- MODIFIKASI: Loading indicator dihapus dari sini --}}
        </div>
    </div>

    {{-- CONTAINER AUTO REFRESH --}}
    <div id="auto-refresh-container">
        <div class="row">
            @forelse($students as $student)
            <div class="col-12 mb-4">
                <div class="student-card">
                    
                    {{-- Student Header --}}
                    <div class="student-header">
                        <div class="d-flex align-items-center">
                            <div class="student-avatar me-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="student-name mb-1">{{ $student->name }}</h4>
                                <div class="student-location">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    {{ $student->complex->name ?? 'Komplek Tidak Diketahui' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Student Body (Status Trip) --}}
                    <div class="student-body">
                        
                        {{-- Desktop View --}}
                        <div class="d-none d-md-block">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="trip-section border-end-desktop">
                                        <h6 class="trip-title">
                                            <i class="bi bi-sunrise-fill me-2"></i>
                                            Berangkat (Sekolah)
                                        </h6>
                                        @include('parent_dashboard.partials.trip_status', [
                                            'tripData' => $student->trip_pagi, 
                                            'scheduleData' => $student->schedule_pagi ?? null,
                                            'type' => 'Pagi'
                                        ])
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="trip-section">
                                        <h6 class="trip-title trip-title-afternoon">
                                            <i class="bi bi-sunset-fill me-2"></i>
                                            Pengantaran (Pulang)
                                        </h6>
                                        @include('parent_dashboard.partials.trip_status', [
                                            'tripData' => $student->trip_sore, 
                                            'scheduleData' => $student->schedule_sore ?? null,
                                            'type' => 'Sore'
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile View --}}
                        <div class="d-md-none">
                            <ul class="nav nav-tabs nav-fill student-tabs mb-3" id="tabs-{{ $student->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active tab-trigger" 
                                            id="pagi-tab-{{ $student->id }}" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#pagi-{{ $student->id }}" 
                                            data-student-id="{{ $student->id }}"
                                            data-type="pagi"
                                            type="button" role="tab">
                                        <i class="bi bi-sunrise-fill me-1"></i> Berangkat
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-trigger" 
                                            id="sore-tab-{{ $student->id }}" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#sore-{{ $student->id }}" 
                                            data-student-id="{{ $student->id }}"
                                            data-type="sore"
                                            type="button" role="tab">
                                        <i class="bi bi-sunset-fill me-1"></i> Pulang
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pagi-{{ $student->id }}" role="tabpanel">
                                    @include('parent_dashboard.partials.trip_status', [
                                        'tripData' => $student->trip_pagi, 
                                        'scheduleData' => $student->schedule_pagi ?? null,
                                        'type' => 'Pagi'
                                    ])
                                </div>
                                <div class="tab-pane fade" id="sore-{{ $student->id }}" role="tabpanel">
                                    @include('parent_dashboard.partials.trip_status', [
                                        'tripData' => $student->trip_sore, 
                                        'scheduleData' => $student->schedule_sore ?? null,
                                        'type' => 'Sore'
                                    ])
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-people"></i></div>
                    <h5 class="fw-bold text-dark mb-2">Data Anak Kosong</h5>
                    <p class="text-muted mb-0">Anda belum terhubung dengan data siswa manapun.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <div style="height: 80px;"></div>
</div>

<style>
    /* Welcome Header */
    .welcome-header { padding: 2rem; background: white; border-radius: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04); position: relative; overflow: hidden; transition: all 0.3s ease; }
    .welcome-header::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); }
    .welcome-header:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); }
    .badge-date { display: inline-block; background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1; padding: 0.6rem 1.5rem; border-radius: 50px; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15); transition: all 0.3s ease; }
    .badge-date:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(14, 165, 233, 0.25); }
    .section-title { padding-left: 0.5rem; }
    .title-indicator { width: 4px; height: 28px; background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 4px; margin-right: 1rem; }
    .student-card { background: white; border-radius: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04); overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; }
    .student-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); transform: scaleX(0); transition: transform 0.4s ease; }
    .student-card:hover::before { transform: scaleX(1); }
    .student-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12); }
    .student-header { padding: 2rem 2.5rem; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-bottom: 1px solid rgba(37, 99, 235, 0.1); }
    .student-avatar { width: 80px; height: 80px; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); border: 3px solid white; transition: all 0.3s ease; }
    .student-card:hover .student-avatar { transform: scale(1.05) rotate(3deg); }
    .student-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, #dbeafe, #bfdbfe); display: flex; align-items: center; justify-content: center; color: #2563eb; font-size: 2.5rem; }
    .student-name { font-size: 1.6rem; font-weight: 800; color: #1e293b; margin: 0; line-height: 1.2; }
    .student-location { color: #64748b; font-size: 0.95rem; font-weight: 500; margin-top: 0.3rem; }
    .student-location i { color: #2563eb; }
    .student-body { padding: 2.5rem; }
    .trip-section { padding-right: 1.5rem; }
    .border-end-desktop { border-right: 2px solid #e0f2fe; }
    .trip-title { font-size: 1rem; font-weight: 700; text-transform: uppercase; color: #2563eb; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 2px solid #e0f2fe; letter-spacing: 0.5px; }
    .trip-title i { color: #2563eb; font-size: 1.1rem; }
    .trip-title-afternoon { color: #0ea5e9; }
    .trip-title-afternoon i { color: #0ea5e9; }
    .student-tabs { border-bottom: 2px solid #e0f2fe; margin-bottom: 1.5rem; }
    .student-tabs .nav-link { border: none; border-bottom: 3px solid transparent; color: #64748b; font-weight: 600; padding: 0.8rem 1rem; transition: all 0.3s; position: relative; }
    .student-tabs .nav-link:hover { color: #2563eb; background: transparent; }
    .student-tabs .nav-link.active { color: #2563eb; border-bottom-color: #2563eb; background: transparent; }
    .empty-state { background: white; border-radius: 24px; padding: 5rem 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04); position: relative; overflow: hidden; }
    .empty-state::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); }
    .empty-icon { width: 120px; height: 120px; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 3.5rem; color: #94a3b8; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.1); }
    @media (max-width: 767.98px) { .welcome-header { padding: 1.5rem; } .student-header { padding: 1.5rem; } .student-body { padding: 1.5rem; } .student-avatar { width: 65px; height: 65px; border-radius: 16px; } .student-name { font-size: 1.3rem; } .badge-date { font-size: 0.85rem; padding: 0.5rem 1rem; } .empty-state { padding: 3.5rem 1.5rem; } .empty-icon { width: 90px; height: 90px; font-size: 2.8rem; } .trip-title { font-size: 0.9rem; } .border-end-desktop { border-right: none; } }
    @media (max-width: 575.98px) { .student-tabs .nav-link { font-size: 0.9rem; padding: 0.7rem 0.8rem; } .trip-title { font-size: 0.85rem; } .student-name { font-size: 1.15rem; } .student-avatar { width: 60px; height: 60px; } }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const REFRESH_INTERVAL = 3000; 
        const containerId = 'auto-refresh-container';
        // Variabel loadingIndicator dihapus agar silent refresh
        let activeTabs = {};

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('tab-trigger')) {
                const studentId = e.target.getAttribute('data-student-id');
                const type = e.target.getAttribute('data-type');
                activeTabs[studentId] = type;
            }
        });

        setInterval(() => {
            // Indikator visual dihapus, langsung fetch data
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById(containerId);
                    const currentContainer = document.getElementById(containerId);

                    if (newContent && currentContainer) {
                        currentContainer.innerHTML = newContent.innerHTML;
                        
                        // Kembalikan status tab yang aktif agar tidak reset ke Pagi saat refresh
                        Object.keys(activeTabs).forEach(studentId => {
                            const type = activeTabs[studentId];
                            if(type) {
                                const tabBtn = document.getElementById(`${type}-tab-${studentId}`);
                                const tabPane = document.getElementById(`${type}-${studentId}`);
                                if(tabBtn && tabPane) {
                                    const container = tabBtn.closest('.student-body');
                                    if(container) {
                                        container.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
                                        container.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));
                                    }
                                    tabBtn.classList.add('active');
                                    tabPane.classList.add('show', 'active');
                                }
                            }
                        });
                    }
                })
                .catch(err => console.error('Auto refresh error:', err));
                // .finally block dihapus karena tidak ada indikator yang perlu di-hide
        }, REFRESH_INTERVAL);
    });
</script>
@endsection