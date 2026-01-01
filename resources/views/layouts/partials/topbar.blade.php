<nav class="navbar navbar-expand navbar-light bg-white border-bottom shadow-sm d-md-none sticky-top px-3" style="height: 70px;">
    <div class="container-fluid p-0 d-flex justify-content-between align-items-center h-100">
        
        <button class="btn btn-light border-0 p-2 me-2" id="sidebar-toggle-mobile">
            <i class="bi bi-list fs-2 text-dark"></i>
        </button>

        {{-- class mx-auto memastikan logo berada di tengah-tengah persis --}}
        <a class="navbar-brand d-flex align-items-center mx-auto" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
        </a>

        <div class="dropdown ms-2">
            <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                {{-- Logika Foto Profil --}}
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User" class="rounded-circle border border-2 border-light shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                         style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); font-size: 1rem;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                @endif
            </a>

            {{-- Dropdown Menu --}}
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-3 animate-slide-down p-2">
                <li class="px-3 py-2 border-bottom mb-2">
                    <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <small class="text-muted" style="font-size: 0.75rem;">Administrator</small>
                </li>
                <li><hr class="dropdown-divider my-2"></li>
                
                {{-- MODIFIKASI LOGOUT DI SINI --}}
                <li>
                    {{-- 1. Tambahkan ID unik: logoutFormMobileNav --}}
                    <form action="{{ route('logout') }}" method="POST" id="logoutFormMobileNav">
                        @csrf
                        {{-- 2. Ubah type="submit" jadi type="button" dan tambah onclick --}}
                        <button type="button" onclick="confirmLogoutMobile()" class="dropdown-item py-2 text-danger rounded-3">
                            <i class="bi bi-box-arrow-right me-2 bg-danger bg-opacity-10 p-1 rounded"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>

<style>
    /* Navbar height fix & centering */
    .navbar {
        min-height: 70px; /* Tinggi fix agar stabil */
    }

    /* Dropdown Item Hover */
    .dropdown-item:hover {
        background-color: var(--light);
        color: var(--primary);
    }

    /* Animasi Dropdown Halus */
    .animate-slide-down {
        animation: slideDownFade 0.25s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        transform-origin: top right;
    }

    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-15px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    /* Custom style untuk popup SweetAlert agar fontnya sama */
    .swal2-popup {
        font-family: inherit !important;
        border-radius: 16px !important;
    }
</style>

<script>
    // FUNGSI KONFIRMASI LOGOUT
    function confirmLogoutMobile() {
        Swal.fire({
            title: 'Konfirmasi Keluar',
            text: "Apakah Anda yakin ingin mengakhiri sesi?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Warna Merah
            cancelButtonColor: '#6c757d',  // Warna Abu-abu/Secondary
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form berdasarkan ID
                document.getElementById('logoutFormMobileNav').submit();
            }
        });
    }
</script>