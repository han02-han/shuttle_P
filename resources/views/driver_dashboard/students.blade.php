@extends('layouts.driver')

@section('content')
<div class="container py-4">
    
    <div class="page-header mb-4">
        <div class="d-flex align-items-center">
            <div class="header-icon me-3">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-1">Data Penumpang</h3>
                <p class="text-muted mb-0">Total {{ $students->count() }} siswa terdaftar dalam tanggungan Anda.</p>
            </div>
        </div>
    </div>

    <div class="route-card mb-4">
        <div class="route-header">
            <div class="d-flex align-items-center">
                <span class="route-badge me-2">
                    <i class="bi bi-list-check me-1"></i> DAFTAR
                </span>
                <h5 class="fw-bold text-dark mb-0">Siswa Langganan</h5>
            </div>
        </div>
        
        <div class="route-body">
            <div class="students-list">
                
                @forelse($students as $student)
                    <div class="student-item">
                        <div class="d-flex align-items-center">
                            
                            <div class="student-avatar me-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow-1 student-info">
                                <h6 class="student-name">{{ $student->name }}</h6>
                                
                                <div class="student-address">
                                    <i class="bi bi-geo-alt-fill me-1 text-warning"></i>
                                    {{-- Nama Komplek --}}
                                    <strong>{{ $student->complex->name ?? 'Komplek ?' }}</strong>
                                    
                                    {{-- Detail Alamat --}}
                                    @if($student->address_note)
                                        <span class="address-note"> - {{ $student->address_note }}</span>
                                    @endif
                                </div>
                                
                                <div class="student-parent">
                                    <i class="bi bi-person-circle me-1"></i>
                                    Wali: {{ $student->parent->name ?? 'Tidak ada data' }}
                                </div>
                            </div>

                            <div>
                                @if($student->parent && $student->parent->phone)
                                    @php
                                        // Bersihkan nomor dari spasi/strip
                                        $phone = preg_replace('/[^0-9]/', '', $student->parent->phone);
                                        // Ubah 08.. jadi 628..
                                        if(substr($phone, 0, 1) == '0') {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $phone }}" target="_blank" class="btn-whatsapp" title="Hubungi via WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                @else
                                    <button class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; opacity:0.5;" disabled>
                                        <i class="bi bi-telephone-x fs-4"></i>
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    {{-- Jika Kosong --}}
                    <div class="empty-students">
                        <i class="bi bi-inbox"></i>
                        <p>Belum ada siswa yang ditugaskan kepada Anda.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    <div style="height: 80px;"></div>
</div>

<style>
    /* Page Header */
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #fbbf24);
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #2563eb;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
    }

    /* Route Card */
    .route-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .route-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #fbbf24);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .route-card:hover::before {
        transform: scaleX(1);
    }

    .route-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    /* Route Header */
    .route-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-bottom: 2px solid rgba(37, 99, 235, 0.1);
    }

    .route-badge {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Route Body */
    .route-body {
        padding: 0;
    }

    .students-list {
        /* No padding, handled by items */
    }

    /* Student Item */
    .student-item {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f0f9ff;
        transition: all 0.3s ease;
    }

    .student-item:last-child {
        border-bottom: none;
    }

    .student-item:hover {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        padding-left: 2.5rem;
    }

    /* Student Avatar */
    .student-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 3px solid white;
        flex-shrink: 0;
    }

    .student-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
    }

    /* Student Info */
    .student-info {
        min-width: 0; /* Allow text truncation */
    }

    .student-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .student-address {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 0.2rem;
        font-weight: 500;
    }

    .student-address i {
        color: #fbbf24;
    }

    .address-note {
        color: #94a3b8;
        font-style: italic;
    }

    .student-parent {
        font-size: 0.85rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* WhatsApp Button */
    .btn-whatsapp {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
    }

    .btn-whatsapp:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    /* Empty Students */
    .empty-students {
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }

    .empty-students i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-students p {
        margin: 0;
        font-size: 0.95rem;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 24px;
        padding: 5rem 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #fbbf24);
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 3.5rem;
        color: #94a3b8;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.1);
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            font-size: 1.6rem;
            border-radius: 16px;
        }

        .route-header {
            padding: 1.2rem 1.5rem;
        }

        .route-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
        }

        .student-item {
            padding: 1.2rem 1.5rem;
        }

        .student-item:hover {
            padding-left: 1.5rem;
        }

        .student-avatar {
            width: 50px;
            height: 50px;
        }

        .avatar-placeholder {
            font-size: 1.2rem;
        }

        .student-name {
            font-size: 1rem;
        }

        .student-address {
            font-size: 0.85rem;
        }

        .student-parent {
            font-size: 0.8rem;
        }

        .btn-whatsapp {
            width: 45px;
            height: 45px;
            font-size: 1.3rem;
        }

        .empty-state {
            padding: 3.5rem 1.5rem;
        }

        .empty-icon {
            width: 90px;
            height: 90px;
            font-size: 2.8rem;
        }
    }
</style>
@endsection