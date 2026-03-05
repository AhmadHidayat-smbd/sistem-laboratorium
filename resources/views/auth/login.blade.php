<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIAKAD Lab Informatika</title>
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/61c258cde3.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --blue: #2563EB;
            --blue-light: #EFF6FF;
            --blue-mid: #BFDBFE;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            background: #F0F5FF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Soft background blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 {
            width: 600px; height: 600px;
            background: rgba(219, 234, 254, 0.8);
            top: -200px; left: -200px;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .blob-2 {
            width: 500px; height: 500px;
            background: rgba(186, 230, 253, 0.6);
            bottom: -150px; right: -150px;
            animation: drift 18s ease-in-out infinite alternate-reverse;
        }
        .blob-3 {
            width: 300px; height: 300px;
            background: rgba(237, 233, 254, 0.5);
            top: 40%; left: 40%;
            animation: drift 22s ease-in-out infinite alternate;
        }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, 30px) scale(1.05); }
        }

        /* ── Wrapper ── */
        .page-wrap {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1060px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Main card ── */
        .main-card {
            width: 100%;
            display: flex;
            border-radius: 2.5rem;
            overflow: hidden;
            background: white;
            box-shadow:
                0 0 0 1px rgba(37, 99, 235, 0.06),
                0 32px 80px rgba(37, 99, 235, 0.10),
                0 8px 24px rgba(0, 0, 0, 0.06);
            animation: slideUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(28px);
        }
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Left panel (image) ── */
        .left-panel {
            flex: 4;
            position: relative;
            overflow: hidden;
            display: none;
            min-height: 560px;
        }
        @media (min-width: 1024px) { .left-panel { display: block; } }

        .left-img {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("images/login-bg.webp") }}');
            background-size: cover;
            background-position: center;
            transform: scale(1.07);
            transition: transform 10s ease-out;
        }
        .left-panel:hover .left-img { transform: scale(1.13); }

        .left-grad {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                160deg,
                rgba(34, 93, 170, 0.15) 0%,
                rgba(22, 72, 182, 0.55) 60%,
                rgba(30, 58, 138, 0.92) 100%
            );
        }

        .left-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .left-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2.5rem;
        }

        .left-bottom h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 0.85rem;
        }
        .left-bottom p {
            color: rgba(255,255,255,0.65);
            font-size: 0.83rem;
            line-height: 1.75;
            max-width: 300px;
        }

        .stats-row {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
            padding-top: 1.75rem;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
        }
        .stat-lbl {
            font-size: 0.63rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.45);
            margin-top: 2px;
        }

        .dots-row {
            display: flex;
            gap: 0.35rem;
            margin-top: 1.5rem;
        }
        .dot { height: 3px; border-radius: 99px; background: white; }

        /* ── Right panel ── */
        .right-panel {
            width: 100%;
            padding: 2rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }
        @media (min-width: 1024px) {
            .right-panel { flex: 3; flex-shrink: 0; }
        }

        .logo-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .logo-img {
            width: 70px; height: 70px;
            object-fit: contain;
            mix-blend-mode: multiply;
        }
        .logo-main {
            font-family: 'Syne', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }
        .logo-sub {
            font-size: 0.62rem;
            color: #2563EB;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-weight: 600;
            margin-top: 1px;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
            line-height: 1.25;
            margin-bottom: 0.35rem;
        }
        .form-title span {
            color: #2563EB;
            font-style: italic;
        }
        .form-subtitle {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-bottom: 1.25rem;
        }

        .err-box {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 0.875rem;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
            color: #dc2626;
            margin-bottom: 1.5rem;
        }

        .field { margin-bottom: 0.9rem; }
        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 0.4rem;
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .field-wrap:focus-within .field-icon { color: #2563EB; }

        .field-input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.6rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.875rem;
            font-size: 0.875rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
        }
        .field-input::placeholder { color: #cbd5e1; }
        .field-input:focus {
            background: white;
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }

        .field-error {
            font-size: 0.72rem;
            color: #ef4444;
            margin-top: 0.35rem;
            margin-left: 0.2rem;
        }

        .toggle-pw {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #cbd5e1;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
            font-size: 0.85rem;
        }
        .toggle-pw:hover { color: #64748b; }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .remember-lbl {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #64748b;
            cursor: pointer;
            user-select: none;
        }
        .remember-lbl input {
            accent-color: #2563EB;
            width: 14px; height: 14px;
            cursor: pointer;
        }
        .forgot-lnk {
            font-size: 0.78rem;
            font-weight: 600;
            color: #2563EB;
            text-decoration: none;
        }
        .forgot-lnk:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: #2563EB;
            border: none;
            border-radius: 0.875rem;
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 4px 24px rgba(37, 99, 235, 0.3);
            transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            background: #1d4ed8;
            box-shadow: 0 8px 32px rgba(37, 99, 235, 0.4);
        }
        .btn-login:active { transform: scale(0.98); }

        /* Shimmer */
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -80%;
            width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
            transform: skewX(-20deg);
            transition: left 0.55s ease;
        }
        .btn-login:hover::before { left: 160%; }

        .divider {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .secure-lbl {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            color: #94a3b8;
        }
        .secure-lbl svg { width: 14px; height: 14px; color: #22c55e; }
        .copy { font-size: 0.68rem; color: #cbd5e1; }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="page-wrap">
        <div class="main-card">

            <!-- LEFT PANEL -->
            <div class="left-panel">
                <div class="left-img"></div>
                <div class="left-grad"></div>
                <div class="left-pattern"></div>
                <div class="left-content">
                    <div class="left-bottom">
                        <h3>Sistem Akademik<br>Laboratorium<br>Informatika</h3>
                        <p>Platform terintegrasi untuk mengelola kegiatan Praktikum secara efisien, transparan, dan real-time.</p>
                        <div class="stats-row">
                            <div>
                                <div class="stat-num">RFID</div>
                                <div class="stat-lbl">Terintegrasi</div>
                            </div>
                        </div>
                        <div class="dots-row">
                            <div class="dot" style="width:70px;"></div>
                            <div class="dot" style="width:8px; opacity:0.8;"></div>
                            <div class="dot" style="width:8px; opacity:0.5;"></div>
                            <div class="dot" style="width:8px; opacity:0.3;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel">

                <div class="logo-row">
                    <img src="{{ asset('images/logoit.png') }}" alt="Logo IT" class="logo-img">
                    <div>
                        <div class="logo-main">SIAKAD Praktikum</div>
                        <div class="logo-sub">Informatika</div>
                    </div>
                </div>

                <h2 class="form-title">Sistem Informasi Akademik<br>Praktikum Program Studi<br><span>Informatika</span></h2>
                <p class="form-subtitle">Masuk untuk mengakses sistem akademik laboratorium.</p>

                <form method="POST" action="{{ url('/login') }}" novalidate>
                    @csrf

                    <div class="field">
                        <label class="field-label">Username</label>
                        <div class="field-wrap">
                            <i class="fa-regular fa-user field-icon"></i>
                            <input type="text" name="identifier" value="{{ old('identifier') }}" required
                                   class="field-input" placeholder="Masukkan username">
                        </div>
                        @error('identifier')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label class="field-label">Password</label>
                        <div class="field-wrap">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input type="password" name="password" id="password" required
                                   class="field-input" style="padding-right:3rem;" placeholder="••••••••">
                            <button type="button" id="togglePassword" class="toggle-pw">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="meta-row">
                        <label class="remember-lbl">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn-login">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk
                    </button>
                </form>

                <div class="divider">
                    <div class="secure-lbl">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Koneksi Aman & Terenkripsi
                    </div>
                    <span class="copy">&copy; {{ date('Y') }} Lab Informatika</span>
                </div>
            </div>

        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            if (isPassword) {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>