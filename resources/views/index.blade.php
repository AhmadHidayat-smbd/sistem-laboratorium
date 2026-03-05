<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal — SIAKAD Lab Informatika</title>
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            width: 100%; height: 100%;
            overflow: hidden;
            font-family: 'Sora', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ══ BG ══ */
        .bg { position: fixed; inset: 0; z-index: 0; }
        .bg-photo {
            position: absolute; inset: 0;
            background: url('{{ asset("images/login-bg.webp") }}') center center / cover no-repeat;
        }
        .bg-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(
                160deg,
                rgba(4, 8, 26, 0.90) 0%,
                rgba(10, 22, 72, 0.84) 50%,
                rgba(4, 8, 26, 0.93) 100%
            );
        }
        .bg-grid {
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(116,143,252,0.12) 1px, transparent 1px);
            background-size: 42px 42px;
        }
        .blob {
            position: absolute; border-radius: 50%;
            filter: blur(110px); pointer-events: none;
        }
        .blob-1 {
            width: 650px; height: 650px;
            background: radial-gradient(circle, rgba(59,91,219,0.2), transparent 65%);
            top: -220px; left: -180px;
            animation: drift 16s ease-in-out infinite alternate;
        }
        .blob-2 {
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(116,143,252,0.12), transparent 65%);
            bottom: -180px; right: -140px;
            animation: drift 20s ease-in-out infinite alternate-reverse;
        }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(22px, 28px) scale(1.06); }
        }

        /* ══ CONTENT ══ */
        .content {
            position: relative; z-index: 1;
            display: flex; flex-direction: column; align-items: center; text-align: center;
            width: min(520px, calc(100vw - 48px));
        }

        /* ══ LOGO — NO CARD, FLOATING ══ */
        .logo-wrapper {
            position: relative;
            width: 200px; height: 200px;
            margin-bottom: 28px;
            display: flex; align-items: center; justify-content: center;
            animation: fadeUp 0.7s 0.05s cubic-bezier(0.22,1,0.36,1) both;
        }

        /* Soft ambient glow di belakang logo */
        .logo-wrapper::before {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(116,143,252,0.18) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(18px);
            animation: ambient 4s ease-in-out infinite;
        }

        @keyframes ambient {
            0%,100% { opacity: 0.5; transform: scale(1); }
            50%      { opacity: 1;   transform: scale(1.25); }
        }

        .logo-wrapper img {
            width: 200px; height: 200px;
            object-fit: contain;
            position: relative; z-index: 1;
            filter: drop-shadow(0 4px 24px rgba(116,143,252,0.35))
                    drop-shadow(0 0 8px rgba(165,180,252,0.2));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }

        /* ══ Sisa elemen tetap sama ══ */
        

        /* Eyebrow */
        .eyebrow {
            display: inline-flex; align-items: center; gap: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px; font-weight: 500;
            color: rgba(116,143,252,0.7);
            letter-spacing: 0.18em; text-transform: uppercase;
            margin-bottom: 14px;
            animation: fadeUp 0.5s 0.24s ease both;
        }
        .eyebrow-line { width: 26px; height: 1px; background: rgba(116,143,252,0.4); }

        /* Title */
        h1 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800; line-height: 1.18; letter-spacing: -0.028em;
            color: #FFFFFF;
            margin-bottom: 12px;
            animation: fadeUp 0.5s 0.29s ease both;
        }
        h1 em {
            font-style: italic;
            background: linear-gradient(125deg, #748FFC, #A5B4FC);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* Desc */
        .desc {
            font-size: 13px; line-height: 1.74;
            color: rgba(255,255,255,0.4);
            max-width: 380px;
            margin-bottom: 32px;
            animation: fadeUp 0.5s 0.33s ease both;
        }

      
        /* CTA */
        .btn {
            display: inline-flex; align-items: center; gap: 10px;
            background: #3B5BDB;
            color: #FFFFFF;
            padding: 13px 44px; border-radius: 10px;
            font-size: 14px; font-weight: 600;
            text-decoration: none;
            position: relative; overflow: hidden;
            transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
            animation: fadeUp 0.5s 0.37s ease both, btn-breathe 3s ease-in-out 1s infinite;
            letter-spacing: 0.01em;
            margin-bottom: 36px;
        }

        @keyframes btn-breathe {
            0%,100% {
                box-shadow: 0 4px 20px rgba(59,91,219,0.35), 0 0 0 0 rgba(59,91,219,0.25);
            }
            50% {
                box-shadow: 0 8px 32px rgba(59,91,219,0.55), 0 0 0 7px rgba(59,91,219,0);
            }
        }

        /* Shimmer sweep otomatis */
        .btn::before {
            content: ''; position: absolute;
            top: 0; left: -75%;
            width: 50%; height: 100%;
            background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.18) 50%, transparent 70%);
            transform: skewX(-18deg);
            animation: btn-shimmer 3.5s ease-in-out 1.5s infinite;
            pointer-events: none;
        }

        @keyframes btn-shimmer {
            0%    { left: -75%; opacity: 0; }
            10%   { opacity: 1; }
            45%   { left: 130%; opacity: 0.8; }
            46%   { opacity: 0; }
            100%  { left: 130%; opacity: 0; }
        }

        .btn:hover {
            background: #2F4AC7;
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(59,91,219,0.55);
            animation-play-state: paused;
        }
        .btn:active { transform: translateY(0); box-shadow: none; }
        .btn svg { transition: transform 0.18s; flex-shrink: 0; }
        .btn:hover svg { transform: translateX(4px); }

        /* Divider */
        .divider {
            width: 100%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(116,143,252,0.2), transparent);
            margin-bottom: 24px;
            animation: fadeUp 0.5s 0.41s ease both;
        }

        /* Stats */
        .stats {
            display: flex; width: 100%;
            border: 1px solid rgba(116,143,252,0.15);
            border-radius: 14px; overflow: hidden;
            animation: fadeUp 0.5s 0.45s ease both;
        }
        .stat {
            flex: 1; padding: 14px 10px; text-align: center;
            border-right: 1px solid rgba(116,143,252,0.1);
            transition: background 0.2s; cursor: default;
        }
        .stat:last-child { border-right: none; }
        .stat:hover { background: rgba(116,143,252,0.07); }
        .stat-val {
            font-size: 12.5px; font-weight: 700;
            color: #748FFC;
            font-family: 'JetBrains Mono', monospace;
            line-height: 1; margin-bottom: 6px;
        }
        .stat-lbl {
            font-size: 9px; color: rgba(255,255,255,0.28);
            text-transform: uppercase; letter-spacing: 0.1em;
        }

        /* Footer */
        .foot {
            margin-top: 20px;
            display: flex; align-items: center; gap: 8px;
            animation: fadeUp 0.5s 0.5s ease both;
        }
        .foot-dot {
            width: 5px; height: 5px; background: #52C476;
            border-radius: 50%; flex-shrink: 0;
            animation: pulse 2.4s ease-in-out infinite;
        }
        .foot-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10.5px; color: rgba(255,255,255,0.24);
        }
        .foot-text strong { color: rgba(255,255,255,0.38); font-weight: 500; }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 480px) {
            .btn { width: 100%; justify-content: center; }
            h1 { font-size: 1.5rem; }
        }
        @media (max-height: 700px) {
            .logo-wrapper { width: 90px; height: 90px; margin-bottom: 18px; }
            .logo-wrapper img { width: 72px; height: 72px; }
            .badge { margin-bottom: 14px; }
            h1 { font-size: 1.4rem; }
            .desc { margin-bottom: 20px; font-size: 12px; }
            .btn { margin-bottom: 24px; }
        }
    </style>
</head>
<body>

<div class="bg">
    <div class="bg-photo"></div>
    <div class="bg-overlay"></div>
    <div class="bg-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
</div>

<div class="content">

    <!-- Logo floating tanpa card -->
    <div class="logo-wrapper">
        <img src="{{ asset('images/logoit.png') }}" alt="iTlabs Logo">
    </div>
    <div class="eyebrow">
        <span class="eyebrow-line"></span>
        Laboratorium Informatika
        <span class="eyebrow-line"></span>
    </div>

    <h1>
        Sistem Informasi Akademik<br>
        Praktikum Program Studi<br>
        <em>Informatika</em>
    </h1>

    <p class="desc">
        Platform terintegrasi berbasis RFID untuk pengelolaan kegiatan praktikum secara efisien, transparan, dan real-time.
    </p>

  <a href="{{ route('login') }}" class="btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        Masuk ke Sistem
    </a>

    <div class="divider"></div>

    <div class="stats">
        <div class="stat">
            <div class="stat-val">RFID</div>
            <div class="stat-lbl">Presensi</div>
        </div>
        <div class="stat">
            <div class="stat-val">99.9%</div>
            <div class="stat-lbl">Akurasi</div>
        </div>
        <div class="stat">
            <div class="stat-val">Realtime</div>
            <div class="stat-lbl">Monitoring</div>
        </div>
        <div class="stat">
            <div class="stat-val">Data</div>
            <div class="stat-lbl">Terverifikasi</div>
        </div>
    </div>

    <div class="foot">
        <div class="foot-dot"></div>
        <span class="foot-text">© 2026 <strong>Laboratorium Informatika</strong> — Semua hak dilindungi.</span>
    </div>

</div>

</body>
</html>