<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Monitoring Anak - Shuttle Sekolah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* PERUBAHAN WARNA: Konsisten Biru */
            --primary: #2563eb;       /* Royal Blue */
            --primary-dark: #1e40af;  /* Dark Blue */
            --secondary: #0dcaf0;     /* Cyan/Light Blue */
            --dark: #1e293b;          /* Slate 800 */
            --light: #f8fafc;         /* Slate 50 */
            --gray: #64748b;          /* Slate 500 */
            --success: #10b981;       /* Emerald */
            --danger: #ef4444;        /* Red */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            /* Background Gradient Biru Lembut */
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            padding-top: 85px;
            color: var(--dark);
            line-height: 1.6;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            padding: 0.8rem 0; 
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--dark) !important;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.6rem;
            transition: transform 0.2s;
            padding: 0; 
        }

        /* LOGO STYLING (RESPONSIVE) */
        .navbar-brand img {
            height: 50px; /* Ukuran Default Desktop */
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        /* User Avatar */
        .user-avatar {
            width: 38px; 
            height: 38px; 
            background-color: var(--secondary);
            color: var(--dark); /* Text tetap gelap agar kontras dengan Cyan */
            border-radius: 50%;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: bold;
            font-size: 0.9rem;
            object-fit: cover;
            transition: transform 0.2s;
            /* Update Shadow ke warna Cyan/Biru */
            box-shadow: 0 4px 12px rgba(13, 202, 240, 0.3);
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        /* Desktop Navigation */
        @media (min-width: 992px) {
            .nav-link {
                padding: 8px 20px !important;
                border-radius: 50px;
                margin-left: 5px;
                font-weight: 500;
                color: var(--gray) !important;
                transition: all 0.3s ease;
                position: relative;
            }

            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: 5px;
                left: 50%;
                background: var(--primary);
                transition: all 0.3s ease;
                transform: translateX(-50%);
            }

            .nav-link:hover::after {
                width: 60%;
            }

            .nav-link:hover {
                color: var(--primary) !important;
            }

            .nav-link.active {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white !important;
                box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            }

            .nav-link.active::after {
                display: none;
            }

            .nav-link i {
                margin-right: 0.3rem;
            }

            .dropdown-toggle {
                background: transparent;
                border: none;
                padding: 0.5rem 1rem !important;
                transition: all 0.3s;
                border-radius: 10px;
            }

            .dropdown-toggle:hover {
                background-color: var(--light);
            }

            .dropdown-menu {
                border: none;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                border-radius: 12px;
                padding: 0.5rem;
                margin-top: 0.8rem !important;
                min-width: 220px;
            }

            .dropdown-item {
                border-radius: 8px;
                padding: 0.7rem 1rem;
                margin-bottom: 0.2rem;
                font-weight: 500;
                transition: all 0.2s;
            }

            .dropdown-item:hover {
                background-color: var(--light);
                color: var(--primary);
                transform: translateX(5px);
            }

            .dropdown-item.text-danger:hover {
                background-color: #fee2e2;
                color: var(--danger) !important;
            }

            .user-info {
                line-height: 1.2;
            }

            .user-info .user-name {
                font-size: 0.9rem;
                font-weight: 700;
                color: var(--dark);
            }

            .user-info .user-role {
                font-size: 0.7rem;
                color: var(--gray);
            }
        }

        /* Mobile Navigation */
        @media (max-width: 991.98px) {
            body {
                padding-top: 70px;
            }

            .navbar {
                padding: 8px 0;
            }

            .navbar-brand img {
                height: 40px; 
            }

            .navbar-toggler {
                border: none;
                padding: 0.5rem;
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
            }

            .navbar-collapse {
                background-color: white;
                position: absolute;
                top: 60px;
                left: 0;
                right: 0;
                padding: 20px;
                border-bottom: 1px solid #ddd;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
                z-index: 1000;
                border-radius: 0 0 12px 12px;
            }

            .nav-item {
                border-bottom: 1px solid var(--light);
                padding: 5px 0;
            }

            .nav-item:last-child {
                border-bottom: none;
            }

            .nav-link {
                font-size: 1rem;
                padding: 12px 0 !important;
                color: var(--gray) !important;
                display: flex;
                align-items: center;
                font-weight: 500;
                transition: all 0.3s;
            }

            .nav-link:hover {
                color: var(--primary) !important;
                padding-left: 10px !important;
            }

            .nav-link i {
                font-size: 1.2rem;
                margin-right: 10px;
                width: 25px;
                text-align: center;
            }

            .nav-link.active {
                color: var(--primary) !important;
                font-weight: bold;
            }

            .btn-logout-mobile {
                width: 100%;
                margin-top: 15px;
                padding: 12px;
                border-radius: 50px;
                font-weight: 600;
                background: linear-gradient(135deg, var(--danger), #dc2626);
                border: none;
                box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
                transition: all 0.3s;
            }

            .btn-logout-mobile:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            }
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-weight: 500;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert i {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        /* Container Spacing */
        .container {
            max-width: 1200px;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            /* Update Hover Scrollbar jadi Nuansa Biru */
            background: linear-gradient(135deg, var(--primary-dark), #0ea5e9);
        }

        /* SweetAlert Custom Font */
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
            border-radius: 16px !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container">
            
            <a class="navbar-brand d-flex align-items-center" href="{{ route('parents.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="ShuttleApp">
            </a>

            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#parentNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="parentNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parents.dashboard') ? 'active' : '' }}" href="{{ route('parents.dashboard') }}">
                            <i class="bi bi-house-heart-fill"></i> Monitor Anak
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parents.my_children') ? 'active' : '' }}" href="{{ route('parents.my_children') }}">
                            <i class="bi bi-people-fill"></i> Data Anak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parents.history') ? 'active' : '' }}" href="{{ route('parents.history') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </li>

                    <li class="nav-item d-lg-none">
                        <a class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                            <i class="bi bi-person-circle"></i> Profil Saya
                        </a>
                    </li>

                    {{-- LOGOUT MOBILE --}}
                    <li class="nav-item d-lg-none">
                        {{-- ID FORM DITAMBAHKAN --}}
                        <form action="{{ route('logout') }}" method="POST" id="logoutFormMobile">
                            @csrf
                            {{-- Type button agar tidak langsung submit, onclick ditambahkan --}}
                            <button type="button" onclick="confirmLogout('logoutFormMobile')" class="btn btn-danger btn-logout-mobile">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </button>
                        </form>
                    </li>

                    <li class="nav-item dropdown d-none d-lg-block ms-3">
                        <a class="nav-link dropdown-toggle p-0 d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <div class="user-info text-end">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-role">Wali Murid</div>
                            </div>
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profil" class="user-avatar">
                            @else
                                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            {{-- LOGOUT DESKTOP --}}
                            <li>
                                {{-- ID FORM DITAMBAHKAN --}}
                                <form action="{{ route('logout') }}" method="POST" id="logoutFormDesktop">
                                    @csrf
                                    {{-- Type button agar tidak langsung submit, onclick ditambahkan --}}
                                    <button type="button" onclick="confirmLogout('logoutFormDesktop')" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3 py-2 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3 py-2 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Auto close mobile menu when clicking a link
        document.querySelectorAll('.navbar-collapse .nav-link:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', function() {
                const navbarToggler = document.querySelector('.navbar-toggler');
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (window.getComputedStyle(navbarToggler).display !== 'none') {
                    navbarCollapse.classList.remove('show');
                }
            });
        });

        // --- SCRIPT VALIDASI LOGOUT SWEETALERT ---
        function confirmLogout(formId) {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: "Apakah Anda yakin ingin mengakhiri sesi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Merah sesuai tema
                cancelButtonColor: '#2563eb', // Biru sesuai tema
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form spesifik berdasarkan ID (Mobile atau Desktop)
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</body>
</html>