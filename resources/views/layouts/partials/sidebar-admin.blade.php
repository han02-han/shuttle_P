<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <div class="brand-wrapper d-flex align-items-center justify-content-start">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo" style="height: 60px; width: auto; object-fit: contain;">
        </div>
        
        <button class="btn btn-toggle-sidebar d-none d-md-flex" id="sidebar-toggle-desktop">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    {{-- Class 'sidebar-content' penting untuk target Script Javascript Scroll --}}
    <div class="sidebar-content custom-scrollbar d-flex flex-column h-100">
        
        {{-- Profile Section --}}
        <div class="px-3 py-4">
            <div class="profile-card text-center">
                <div class="position-relative d-inline-block mb-2">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile" class="profile-img">
                    @else
                        <div class="profile-img-placeholder">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="status-indicator"></span>
                </div>
                <div class="profile-info">
                    <h6 class="mb-0 fw-bold text-dark text-truncate">{{ Auth::user()->name ?? 'Admin' }}</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-shield-check text-indigo me-1"></i>Administrator
                    </small>
                </div>
            </div>
        </div>

        {{-- Menu List --}}
        <div class="list-group list-group-flush px-3 pb-4">
            
            @php
                $isOperasionalActive = request()->routeIs('students.search', 'schedules.*', 'trips.*');
                $isDataMasterActive  = request()->routeIs('routes.*', 'shuttles.*', 'complexes.*');
                $isPenggunaActive    = request()->routeIs('drivers.*', 'parents.*', 'students.*');
            @endphp

            {{-- 1. Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            {{-- [BARU] 2. PENGUMUMAN --}}
            <a href="{{ route('announcements.index') }}" class="list-group-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}" title="Pengumuman">
                <i class="bi bi-megaphone-fill"></i>
                <span class="menu-text">Pengumuman</span>
            </a>

            {{-- 3. OPERASIONAL --}}
            <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#submenuOperasional" 
               aria-expanded="{{ $isOperasionalActive ? 'true' : 'false' }}" 
               class="list-group-item d-flex justify-content-between align-items-center {{ $isOperasionalActive ? 'active-parent' : '' }}" 
               title="Operasional">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-gear-wide-connected"></i>
                    <span class="menu-text">Operasional</span>
                </div>
                <span class="menu-text">
                    <i class="bi bi-chevron-down chevron-icon transition-icon"></i>
                </span>
            </a>

            <div class="collapse {{ $isOperasionalActive ? 'show' : '' }}" id="submenuOperasional">
                <div class="sub-menu-container mt-1 mb-2">
                    <a href="{{ route('students.search') }}" class="list-group-item sub-item {{ request()->routeIs('students.search') ? 'active' : '' }}" title="Cari Siswa">
                        <i class="bi bi-search"></i> <span class="menu-text">Cari Siswa</span>
                    </a>
                    <a href="{{ route('schedules.index') }}" class="list-group-item sub-item {{ request()->routeIs('schedules.*') ? 'active' : '' }}" title="Master Jadwal">
                        <i class="bi bi-calendar-week"></i> <span class="menu-text">Master Jadwal</span>
                    </a>
                    <a href="{{ route('trips.index') }}" class="list-group-item sub-item {{ request()->routeIs('trips.*') ? 'active' : '' }}" title="Riwayat Trip">
                        <i class="bi bi-clipboard-check"></i> <span class="menu-text">Riwayat Trip</span>
                    </a>
                </div>
            </div>

            {{-- 4. DATA MASTER --}}
            <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#submenuDataMaster" 
               aria-expanded="{{ $isDataMasterActive ? 'true' : 'false' }}" 
               class="list-group-item d-flex justify-content-between align-items-center {{ $isDataMasterActive ? 'active-parent' : '' }}" 
               title="Data Master">
                 <div class="d-flex align-items-center gap-2">
                     <i class="bi bi-database-fill"></i>
                     <span class="menu-text">Data Master</span>
                 </div>
                 <span class="menu-text">
                     <i class="bi bi-chevron-down chevron-icon transition-icon"></i>
                 </span>
             </a>
 
             <div class="collapse {{ $isDataMasterActive ? 'show' : '' }}" id="submenuDataMaster">
                 <div class="sub-menu-container mt-1 mb-2">
                    <a href="{{ route('routes.index') }}" class="list-group-item sub-item {{ request()->routeIs('routes.*') ? 'active' : '' }}" title="Data Rute">
                        <i class="bi bi-map"></i> <span class="menu-text">Data Rute</span>
                    </a>
                    <a href="{{ route('shuttles.index') }}" class="list-group-item sub-item {{ request()->routeIs('shuttles.*') ? 'active' : '' }}" title="Data Armada">
                        <i class="bi bi-bus-front"></i> <span class="menu-text">Data Armada</span>
                    </a>
                    <a href="{{ route('complexes.index') }}" class="list-group-item sub-item {{ request()->routeIs('complexes.*') ? 'active' : '' }}" title="Data Komplek">
                        <i class="bi bi-buildings"></i> <span class="menu-text">Data Komplek</span>
                    </a>
                 </div>
             </div>

            {{-- 5. PENGGUNA --}}
            <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#submenuPengguna" 
               aria-expanded="{{ $isPenggunaActive ? 'true' : 'false' }}" 
               class="list-group-item d-flex justify-content-between align-items-center {{ $isPenggunaActive ? 'active-parent' : '' }}" 
               title="Pengguna">
                 <div class="d-flex align-items-center gap-2">
                     <i class="bi bi-people-fill"></i>
                     <span class="menu-text">Pengguna</span>
                 </div>
                 <span class="menu-text">
                     <i class="bi bi-chevron-down chevron-icon transition-icon"></i>
                 </span>
             </a>
 
             <div class="collapse {{ $isPenggunaActive ? 'show' : '' }}" id="submenuPengguna">
                 <div class="sub-menu-container mt-1 mb-2">
                    <a href="{{ route('drivers.index') }}" class="list-group-item sub-item {{ request()->routeIs('drivers.*') ? 'active' : '' }}" title="Data Driver">
                        <i class="bi bi-person-badge"></i> <span class="menu-text">Data Driver</span>
                    </a>
                    <a href="{{ route('parents.index') }}" class="list-group-item sub-item {{ request()->routeIs('parents.*') ? 'active' : '' }}" title="Wali Murid">
                        <i class="bi bi-people"></i> <span class="menu-text">Wali Murid</span>
                    </a>
                    <a href="{{ route('students.index') }}" class="list-group-item sub-item {{ request()->routeIs('students.*') ? 'active' : '' }}" title="Data Siswa">
                        <i class="bi bi-mortarboard-fill"></i> <span class="menu-text">Data Siswa</span>
                    </a>
                 </div>
             </div>

        </div>
    </div>

    {{-- MODIFIKASI DIMULAI DARI SINI --}}
    <div class="sidebar-footer p-3 border-top bg-white">
        {{-- Menambahkan ID pada form --}}
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            {{-- Mengubah type="button" dan menambah onclick --}}
            <button type="button" onclick="confirmLogout()" class="btn btn-logout w-100 d-flex align-items-center justify-content-center gap-2" title="Keluar Aplikasi">
                <i class="bi bi-box-arrow-left"></i> 
                <span class="menu-text">Keluar</span>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.querySelector('.sidebar-content');
        if (sidebar) {
            const savedPos = localStorage.getItem('sidebarScrollPos');
            if (savedPos) {
                sidebar.scrollTop = savedPos;
            }
            sidebar.addEventListener('scroll', function() {
                localStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
            });
            const links = sidebar.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    localStorage.setItem('sidebarScrollPos', sidebar.scrollTop);
                });
            });
        }
    });

    // --- FUNGSI BARU UNTUK KONFIRMASI LOGOUT ---
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Keluar',
            text: "Apakah Anda yakin ingin mengakhiri sesi ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Warna Merah sesuai tombol logout
            cancelButtonColor: '#4f46e5', // Warna Indigo/Primary
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            reverseButtons: true, // Tombol batal di kiri, hapus di kanan (opsional)
            borderRadius: '12px',
            customClass: {
                popup: 'my-swal-popup', // Class custom jika ingin styling tambahan
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form secara manual jika user klik Ya
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>

<style>
    /* Variabel Warna */
    :root {
        --indigo-primary: #4f46e5;
        --indigo-light: #e0e7ff;
    }
    .text-indigo { color: var(--indigo-primary); }

    /* --- 1. Header & Logo --- */
    .sidebar-heading {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
        background: white;
    }
    .brand-wrapper { flex-grow: 1; }
    .brand-logo { transition: all 0.3s ease; }

    .btn-toggle-sidebar {
        width: 32px; height: 32px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        background: white; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        transition: 0.3s; font-size: 0.9rem; cursor: pointer; flex-shrink: 0;
    }
    .btn-toggle-sidebar:hover {
        background: var(--indigo-light); color: var(--indigo-primary); border-color: var(--indigo-light);
    }

    /* --- 2. Profile Card --- */
    .profile-card {
        background: #f8fafc; border-radius: 16px; padding: 1.5rem 1rem;
        border: 1px solid #f1f5f9; transition: 0.3s;
    }
    .profile-img, .profile-img-placeholder {
        width: 64px; height: 64px; border-radius: 50%;
        object-fit: cover; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .profile-img-placeholder {
        background: var(--indigo-light); color: var(--indigo-primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: bold;
    }
    .status-indicator {
        position: absolute; bottom: 5px; right: 5px; width: 14px; height: 14px;
        background: #22c55e; border: 2px solid white; border-radius: 50%;
    }

    /* --- 3. Menu Items --- */
    .list-group-item {
        border: none; padding: 0.8rem 1rem; margin-bottom: 4px;
        border-radius: 12px !important; color: #64748b; font-weight: 500;
        display: flex; align-items: center; gap: 12px;
        transition: all 0.2s ease; background: transparent; white-space: nowrap; 
    }
    .list-group-item i {
        font-size: 1.25rem; min-width: 24px; text-align: center; transition: 0.2s;
    }
    .list-group-item:hover {
        background-color: #f1f5f9; color: var(--indigo-primary); transform: translateX(4px);
    }
    
    /* Active State for Normal Links */
    .list-group-item.active {
        background: linear-gradient(135deg, var(--indigo-primary), #4338ca);
        color: white; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
    }
    .list-group-item.active i { color: white; }

    /* Styling Parent Dropdown saat Active/Open */
    .active-parent {
        background-color: #f1f5f9;
        color: var(--indigo-primary);
        font-weight: 600;
    }

    /* Sub Menu Styling */
    .sub-menu-container {
        background: #f8fafc; border-radius: 12px; padding: 5px;
        border-left: 2px solid #e2e8f0; margin-left: 10px;
    }
    .list-group-item.sub-item {
        font-size: 0.9rem; padding: 0.6rem 1rem;
    }
    .list-group-item.sub-item i {
        font-size: 1rem;
    }
    .list-group-item.sub-item:hover {
        transform: translateX(2px);
    }

    /* Animasi Panah Dropdown */
    .chevron-icon {
        font-size: 0.8rem !important; transition: transform 0.3s ease;
    }
    [aria-expanded="true"] .chevron-icon { transform: rotate(180deg); }

    /* --- 4. Scrollbar --- */
    .sidebar-content { overflow-y: auto; overflow-x: hidden; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    /* --- 5. Logout Button --- */
    .btn-logout {
        border: 1px solid #fee2e2; background: #fef2f2; color: #dc2626;
        border-radius: 12px; padding: 10px; font-weight: 600; transition: 0.3s;
    }
    .btn-logout:hover {
        background: #dc2626; color: white; border-color: #dc2626;
    }

    /* =========================================
       MINIMIZED STATE LOGIC (Desktop Only)
       ========================================= */
    body.sidebar-minimized .menu-text,
    body.sidebar-minimized .sidebar-label,
    body.sidebar-minimized .profile-info {
        display: none !important;
    }
    body.sidebar-minimized .brand-wrapper { display: none !important; }
    body.sidebar-minimized .sidebar-heading { justify-content: center; padding: 0; }
    body.sidebar-minimized .list-group-item { justify-content: center; padding-left: 0; padding-right: 0; }
    body.sidebar-minimized .profile-card { background: transparent; border: none; padding: 0; margin-bottom: 1rem; }
    body.sidebar-minimized .profile-img, 
    body.sidebar-minimized .profile-img-placeholder {
        width: 40px; height: 40px; font-size: 1rem; border-width: 2px;
    }
    body.sidebar-minimized .status-indicator { width: 10px; height: 10px; }
    body.sidebar-minimized .list-group-item:hover { transform: none; }
    
    body.sidebar-minimized .list-group-item:hover::after {
        content: attr(title); position: absolute; left: 100%; top: 50%; transform: translateY(-50%);
        background: #1e293b; color: white; padding: 6px 12px; border-radius: 6px;
        font-size: 0.85rem; z-index: 9999; margin-left: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        white-space: nowrap; pointer-events: none;
    }

    body.sidebar-minimized .collapse.show { display: none !important; }
    body.sidebar-minimized .btn-toggle-sidebar i { transform: rotate(180deg); }
    body.sidebar-minimized .btn-logout { width: 40px; height: 40px; border-radius: 50%; padding: 0; }

    /* Styling Tambahan untuk SweetAlert agar fontnya pas */
    .swal2-popup {
        font-family: inherit !important;
        border-radius: 16px !important;
    }
    .swal2-title {
        font-size: 1.25rem !important;
    }
</style>