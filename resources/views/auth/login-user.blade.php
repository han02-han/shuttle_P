<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna - Shuttle Sekolah</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* TEMA USER: Biru Cerah (Friendly & Trustworthy) */
            --user-primary: #2563eb; 
            --user-dark: #1e40af;
            --user-accent: #60a5fa;
            --text-dark: #1e293b;
            --text-gray: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Background cerah untuk user */
            background: radial-gradient(circle at 50% 50%, #f0f9ff 0%, #e0f2fe 100%);
            overflow: hidden;
            position: relative;
        }

        /* Background Animation - Warna Biru Langit */
        .bg-blob {
            position: absolute;
            width: 550px;
            height: 550px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(96, 165, 250, 0.15));
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: float 10s infinite alternate;
        }
        
        .blob-1 { top: -10%; left: -10%; }
        .blob-2 { bottom: -10%; right: -10%; width: 500px; height: 500px; animation-delay: 2s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(20px, 40px) scale(1.05); }
        }

        /* Card Login Style - Konsisten dengan Admin tapi nuansa Biru */
        .card-login {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px rgba(37, 99, 235, 0.08); /* Shadow Biru */
            animation: zoomIn 0.6s ease forwards;
            position: relative;
            overflow: hidden;
        }

        /* Strip Atas Biru */
        .card-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--user-primary), var(--user-accent));
        }

        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .login-logo {
            height: 65px;
            width: auto;
            object-fit: contain;
            margin-bottom: 0.5rem;
        }

        /* User Badge */
        .badge-user {
            background: rgba(37, 99, 235, 0.1);
            color: var(--user-primary);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 5px 15px;
            border-radius: 50px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        /* Form Controls */
        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.95rem;
            padding: 12px;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--user-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-group-text {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: white;
            color: var(--user-primary);
            border-left: none;
        }
        
        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: none;
        }
        .input-group .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Button User - Gradient Biru */
        .btn-user {
            background: linear-gradient(135deg, var(--user-primary), var(--user-dark));
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .btn-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
            background: linear-gradient(135deg, var(--user-dark), var(--user-primary));
        }

        .link-admin {
            color: var(--text-gray);
            font-size: 0.9rem;
            transition: 0.3s;
            text-decoration: none;
        }
        .link-admin:hover {
            color: var(--user-primary);
        }

        /* Hilangkan spinner input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        @media (max-width: 576px) {
            .card-login {
                max-width: 90%;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    {{-- Background Blobs --}}
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <div class="container d-flex justify-content-center">
        <div class="card card-login">
            <div class="text-center">
                {{-- Logo --}}
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">
                
                <h4 class="fw-bold text-dark mb-1">Selamat Datang</h4>
                {{-- Badge Khusus User --}}
                <span class="badge-user"><i class="bi bi-people-fill me-1"></i> Wali Murid & Driver</span>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center py-2 small mb-4 rounded-3 border-0 shadow-sm" role="alert" style="background-color: #fff1f2; color: #be123c;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf 
                
                {{-- Input Phone --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary ms-1">Nomor WhatsApp / HP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 ps-3" style="border-radius: 12px 0 0 12px; border-right: none;">
                            <i class="bi bi-telephone-fill text-primary" style="color: var(--user-primary) !important;"></i>
                        </span>
                        {{-- Input Telp --}}
                        <input type="tel" name="phone" class="form-control form-control-lg bg-light border-start-0 ps-2" 
                               placeholder="Contoh: 0812xxx" required autofocus autocomplete="username"
                               style="border-radius: 0 12px 12px 0; font-size: 1rem;">
                    </div>
                </div>

                {{-- Input Password --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary ms-1">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 ps-3" style="border-radius: 12px 0 0 12px; border-right: none;">
                            <i class="bi bi-lock-fill text-primary" style="color: var(--user-primary) !important;"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput" class="form-control form-control-lg bg-light border-start-0 border-end-0 ps-2" 
                               placeholder="••••••••" required autocomplete="current-password"
                               style="border-radius: 0; font-size: 1rem;">
                        <span class="input-group-text bg-white border-start-0 pe-3" style="cursor: pointer; border-radius: 0 12px 12px 0;" onclick="togglePassword()">
                            <i class="bi bi-eye-slash text-muted" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-secondary" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-user w-100 text-white shadow-sm">
                    MASUK PENGGUNA <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }
    </script>
</body>
</html>