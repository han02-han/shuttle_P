<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Shuttle - Antar Jemput Aman & Nyaman</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- AOS Animation CSS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        :root {
            /* Palette Warna */
            --primary: #4361ee; 
            --primary-light: #4895ef;
            --secondary: #4cc9f0;
            --accent: #f72585;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --card-bg: rgba(255, 255, 255, 0.85);
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background-color: #fdfdfd;
        }

        /* --- Navbar --- */
        .navbar {
            padding: 1rem 0;
            transition: all 0.4s ease;
            background: rgba(255, 255, 255, 0.0);
            backdrop-filter: blur(0px);
            z-index: 999;
        }
        
        .navbar.scrolled {
            padding: 0.8rem 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-link {
            font-weight: 500;
            color: var(--gray) !important;
            margin: 0 0.8rem;
            position: relative;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease-in-out;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }

        /* Tombol Login (Biru) - Style Referensi */
        .btn-login {
            background: linear-gradient(45deg, var(--primary), var(--primary-light));
            color: white;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
            filter: brightness(1.1);
        }

        /* --- Hero Section --- */
        .hero-section {
            padding: 160px 0 100px;
            position: relative;
            background: radial-gradient(circle at 10% 20%, #eff6ff 0%, #fff 80%);
            overflow: hidden;
        }

        .shape-blob {
            position: absolute;
            background: linear-gradient(45deg, rgba(67, 97, 238, 0.15), rgba(72, 149, 239, 0.15));
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob-bounce 10s infinite alternate;
            z-index: 0;
        }
        
        .blob-1 { top: -10%; right: -5%; width: 600px; height: 600px; }
        .blob-2 { bottom: 10%; left: -10%; width: 400px; height: 400px; animation-delay: 2s; background: rgba(76, 201, 240, 0.1); }

        @keyframes blob-bounce {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; transform: translate(0, 0); }
            100% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; transform: translate(-20px, 20px); }
        }

        .hero-content { position: relative; z-index: 2; }

        .hero-title {
            font-size: 3.8rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.15rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            line-height: 1.8;
            max-width: 520px;
        }

        .btn-hero-primary {
            background: var(--primary);
            color: white;
            padding: 1rem 2.8rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
        }

        .btn-hero-secondary {
            background: white;
            color: var(--dark);
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }

        .btn-hero-primary:hover, .btn-hero-secondary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.2);
        }

        .floating-img { animation: float 6s ease-in-out infinite; }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
            100% { transform: translateY(0px); }
        }

        /* --- Wave Separator --- */
        .wave-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .wave-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .wave-bottom .shape-fill { fill: #ffffff; }

        /* --- Features Section --- */
        .features-section {
            padding: 60px 0 100px;
            background-color: #fff;
            position: relative;
        }

        .feature-card {
            background: var(--card-bg);
            padding: 2.5rem 2rem;
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .feature-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 24px; 
            padding: 2px; 
            background: linear-gradient(45deg, transparent, rgba(67, 97, 238, 0.1), transparent); 
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); 
            -webkit-mask-composite: xor; 
            mask-composite: exclude; 
            opacity: 0.5;
            transition: 0.5s;
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 50px -10px rgba(67, 97, 238, 0.15);
        }
        
        .feature-card:hover::before {
            background: linear-gradient(45deg, var(--primary), var(--secondary)); 
            opacity: 1;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: transform 0.5s;
        }
        
        .feature-card:hover .icon-box { transform: rotateY(360deg); }

        .icon-blue { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
        .icon-cyan { background: rgba(76, 201, 240, 0.1); color: #00b4d8; }
        .icon-pink { background: rgba(247, 37, 133, 0.1); color: var(--accent); }

        /* --- CTA Section --- */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, #3a0ca3 100%);
            position: relative;
            overflow: hidden;
            color: white;
            padding: 80px 0;
        }
        
        .cta-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
        }

        /* --- BUTTON KONTAK KUNING (Gaya persis seperti tombol Masuk Aplikasi) --- */
        .btn-contact-yellow {
            background: linear-gradient(45deg, #ffc107, #ff9800); /* Warna Kuning Gradient */
            color: white;
            padding: 0.8rem 2.2rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px; /* Jarak icon dan text */
            font-size: 1rem;
            min-width: 160px; /* Supaya seragam lebarnya */
        }

        .btn-contact-yellow:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 152, 0, 0.6);
            color: white;
            filter: brightness(1.1);
        }

        .btn-contact-yellow i {
            font-size: 1.2rem; /* Ukuran icon sedikit lebih besar */
        }
        
        /* --- Footer --- */
        footer {
            background-color: var(--dark);
            color: #94a3b8;
            padding: 80px 0 30px;
            position: relative;
        }
        
        .footer-link {
            text-decoration: none;
            color: #94a3b8;
            display: block;
            margin-bottom: 12px;
            transition: 0.3s;
            font-size: 0.95rem;
        }
        
        .footer-link:hover { color: var(--secondary); padding-left: 5px; }

        .social-btn {
            width: 40px; 
            height: 40px; 
            border-radius: 50%; 
            background: rgba(255,255,255,0.05); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            color: white; 
            transition: 0.3s;
        }
        
        .social-btn:hover { background: var(--primary); transform: translateY(-3px); }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .hero-section { padding-top: 120px; text-align: center; }
            .hero-title { font-size: 2.8rem; }
            .hero-desc { margin: 0 auto 2rem; }
            .d-flex.gap-3 { justify-content: center; }
            .hero-img-wrapper { margin-top: 4rem; }
            .blob-1 { right: -20%; width: 300px; height: 300px; }
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                margin-top: 15px;
            }
        }

        @media (max-width: 576px) {
            .hero-title { font-size: 2.2rem; }
            .btn-hero-primary, .btn-hero-secondary { width: 100%; margin-bottom: 10px; }
            .d-flex.gap-3 { flex-direction: column; gap: 0.5rem !important; }
            /* Agar tombol full width di HP */
            .btn-contact-yellow { width: 100%; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            {{-- LOGO SAJA (TANPA TEKS) --}}
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; width: auto; object-fit: contain;">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur & Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Hubungi Kami</a></li>
                </ul>
                <div class="d-flex gap-2 justify-content-center mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-login w-100">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login px-4">
                                Masuk Aplikasi <i class="bi bi-arrow-right-short ms-1 fs-5 align-middle"></i>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section id="beranda" class="hero-section">
        <div class="shape-blob blob-1"></div>
        <div class="shape-blob blob-2"></div>
        
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-duration="1000">
                    <div class="d-inline-flex align-items-center bg-white px-3 py-1 rounded-pill shadow-sm border mb-4">
                        <span class="badge bg-primary rounded-pill me-2">HEPIGO</span>
                        <span class="small fw-medium text-secondary">Monitoring Antar Jemput Sekolah</span>
                    </div>
                    
                    <h1 class="hero-title">
                        Jemput Sekolah <br>
                        <span style="color: var(--primary);">Lebih Aman</span> & Nyaman
                    </h1>
                    
                    <p class="hero-desc">
                        Solusi digital untuk orang tua modern. Pantau Proses menuju sekolah anak secara real-time, terima notifikasi instan, dan pastikan perjalanan sekolah yang aman.
                    </p>
                    
                    <div class="d-flex gap-3 align-items-center">
                        <a href="{{ route('login') }}" class="btn btn-hero-primary shadow-lg">
                            Mulai Sekarang
                        </a>
                        <a href="#fitur" class="btn btn-hero-secondary">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 hero-img-wrapper mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                    <div class="position-relative text-center">
                        {{-- GAMBAR BANNER UTAMA --}}
                        <img src="{{ asset('images/baner.jpg') }}" 
                             alt="Banner Shuttle School" 
                             class="img-fluid rounded-4 shadow-lg floating-img position-relative z-2"
                             style="width: 100%; max-width: 500px; border: 6px solid rgba(255,255,255,0.9); object-fit: cover;">
                        
                        {{-- Widget Floating --}}
                        <div class="position-absolute top-0 end-0 translate-middle p-3 bg-white rounded-4 shadow-lg d-none d-md-block floating-img" 
                             style="animation-delay: 1s; z-index: 3; right: 5% !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 rounded-circle text-primary bg-primary bg-opacity-10">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SVG Curve Separator --}}
        <div class="wave-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section id="fitur" class="features-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="zoom-in">
                <span class="text-primary fw-bold text-uppercase letter-spacing-2 small">Kenapa Memilih Kami?</span>
                <h2 class="fw-bold mt-2 display-6">Fitur Unggulan ShuttleApp</h2>
                <div class="mx-auto mt-3 bg-primary" style="width: 60px; height: 4px; border-radius: 2px;"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="icon-box icon-cyan">
                            <i class="bi bi-chat-square-dots-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Notifikasi Pintar</h4>
                        <p class="text-muted mb-0">Terima notifikasi otomatis saat anak dijemput, sedang dalam perjalanan, hingga tiba dengan selamat di sekolah.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="icon-box icon-blue">
                            <i class="bi bi-map-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Riwayat Lengkap</h4>
                        <p class="text-muted mb-0">Akses data riwayat perjalanan siswa secara transparan dan tercatat rapi untuk keamanan dan evaluasi layanan.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="icon-box icon-pink">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Driver Terverifikasi</h4>
                        <p class="text-muted mb-0">Keamanan prioritas kami. Seluruh driver memiliki lisensi resmi, identitas jelas, dan terlatih dalam keselamatan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section id="kontak" class="cta-section">
        <div class="cta-pattern"></div>
        <div class="container position-relative z-2 text-center" data-aos="zoom-in">
            <h2 class="fw-bold mb-3 display-5">Siap Bergabung dengan Kami?</h2>
            <p class="mb-5 opacity-75 fs-5 mx-auto" style="max-width: 600px;">
                Tingkatkan keamanan perjalanan sekolah anak Anda dengan teknologi terkini. Daftar sekarang dan rasakan ketenangannya.
            </p>
            
            {{-- BAGIAN KONTAK CTA (TOMBOL KUNING - GAYA PERSIS LOGIN) --}}
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                
                {{-- 1. Instagram --}}
                <a href="https://instagram.com/suttleapp_official" target="_blank" class="btn-contact-yellow">
                    <i class="bi bi-instagram"></i> @hepigo.app
                </a>

                {{-- 2. WhatsApp / No HP --}}
                <a href="https://wa.me/6281234567890" target="_blank" class="btn-contact-yellow">
                    <i class="bi bi-whatsapp"></i> 08xx-xxx-xxx
                </a>

                {{-- 3. Email --}}
                <a href="mailto:support@hepigo.com" class="btn-contact-yellow">
                    <i class="bi bi-envelope"></i> hepigo@gmail.com
                </a>

            </div>
            {{-- END KONTAK --}}
        </div>
        
        {{-- Top Wave for Footer Transition --}}
        <div class="wave-bottom" style="transform: rotate(0deg); bottom: -1px;">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#0f172a"></path>
            </svg>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <div class="row gy-5">
                {{-- KOLOM 1: Brand & Logo --}}
                <div class="col-lg-5 col-md-6">
                    <div class="mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; filter: brightness(0) invert(1);">
                    </div>
                    <p class="text-secondary pe-lg-5">
                        Platform manajemen transportasi sekolah modern yang menghubungkan Sekolah, Driver, dan Orang Tua untuk perjalanan yang lebih aman.
                    </p>
                    <div class="d-flex gap-2 mt-4">
                        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                
                {{-- KOLOM 2: Navigasi --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4 fw-semibold">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li><a href="#beranda" class="footer-link">Beranda</a></li>
                        <li><a href="#fitur" class="footer-link">Fitur & Layanan</a></li>
                        <li><a href="#" class="footer-link">Tentang Kami</a></li>
                        <li><a href="#" class="footer-link">Bantuan</a></li>
                    </ul>
                </div>
                
                {{-- KOLOM 3: Akses Pengguna --}}
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4 fw-semibold">Akses Pengguna</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}" class="footer-link">Login Orang Tua</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link">Login Driver</a></li>
                        <li><a href="{{ route('admin.login') }}" class="footer-link">Login Administrator</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-top border-secondary border-opacity-25 mt-5 pt-4 text-center">
                <small class="text-secondary">&copy; {{ date('Y') }} HepiGo System.</small>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- AOS Animation JS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({ once: false, mirror: true, duration: 800, offset: 50 });
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>