<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">
    <title>Sesi Presensi</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scan-pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        .animate-scan {
            animation: scan-pulse 2s infinite ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        .animate-shake { animation: shake 0.4s ease-in-out; }
    </style>
</head>

<body class="bg-[#F8FAFC] min-h-screen text-slate-900 flex flex-col">

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in-up">
        <!-- Main Scan Area -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative">
                <!-- Session Header -->
                <div class="px-7 py-6 bg-slate-900 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-3 py-1 bg-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-blue-500/20">Sesi Presensi Mahasiswa</span>
                            <span class="text-slate-400 font-bold text-xs uppercase tracking-wider">Pertemuan Ke-{{ $pertemuan }}</span>
                        </div>
                        <h2 class="text-2xl font-black tracking-tight mb-3 leading-tight">{{ $matakuliah->nama }}</h2>
                        
                        <div class="flex flex-wrap items-center gap-3 text-slate-400 text-xs font-bold uppercase tracking-widest">
                            <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg">
                                <i class="ph-bold ph-calendar text-blue-500"></i> {{ date('d M Y') }}
                            </span>
                            @php $jadwal = $matakuliah->jadwal->first(); @endphp
                            @if($jadwal)
                            @php
                                $hariIndo = [
                                    'monday'    => 'Senin',
                                    'tuesday'   => 'Selasa',
                                    'wednesday' => 'Rabu',
                                    'thursday'  => 'Kamis',
                                    'friday'    => 'Jumat',
                                    'saturday'  => 'Sabtu',
                                    'sunday'    => 'Minggu',
                                ];
                                $hariTampil = $hariIndo[strtolower(trim($jadwal->hari))] ?? $jadwal->hari;
                            @endphp
                            <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg">
                                <i class="ph-bold ph-clock text-blue-500"></i> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg">
                                <i class="ph-bold ph-map-pin text-blue-500"></i> {{ $hariTampil }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="absolute right-0 top-0 opacity-10 translate-x-1/4 -translate-y-1/4">
                        <i class="ph-fill ph-broadcast text-[180px]"></i>
                    </div>
                </div>

                <!-- Input Zone -->
                <div class="px-8 py-8">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-[1.5rem] mb-4 shadow-lg shadow-blue-100/50 animate-scan">
                            <i class="ph-fill ph-scan text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-1">Siap Memindai Kartu</h3>
                        <p class="text-slate-400 font-medium text-sm">Tempelkan kartu RFID mahasiswa</p>
                    </div>

                    <div class="relative group max-w-sm mx-auto mb-6">
                        <input id="rfidInput" autofocus autocomplete="off" type="text"
                               class="w-full pl-6 pr-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-black text-2xl text-center text-slate-800 tracking-[0.2em]"
                               placeholder="........">
                    </div>

                    <div class="flex justify-center">
                        <button id="manualBtn" class="flex items-center gap-2 text-blue-600 font-black text-[11px] uppercase tracking-[2px] hover:text-blue-700 transition-all py-2.5 px-5 rounded-xl hover:bg-blue-50 active:scale-95">
                            <i class="ph-bold ph-keyboard text-base"></i>
                            Input Manual (Enter)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Feedback Area -->
            <div id="notifArea" class="space-y-4">
                <div id="idleMsg" class="text-center py-8 border-2 border-dashed border-slate-200 rounded-[2rem] bg-white/50">
                    <i class="ph-fill ph-circles-three text-3xl text-slate-200 mb-2"></i>
                    <p class="text-slate-400 font-bold text-xs italic uppercase tracking-widest">Menunggu Aktivitas Pemindaian...</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-5">
            <!-- Countdown Card -->
            <div class="bg-blue-600 rounded-[2rem] p-7 text-white shadow-2xl shadow-blue-200 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[4px] text-blue-200 mb-5 text-center">Sisa Waktu Presensi</p>
                    <div class="flex items-center justify-center gap-3">
                        <div class="bg-white/10 backdrop-blur-xl rounded-[1.5rem] p-4 min-w-[85px] text-center border border-white/10 group-hover:bg-white/20 transition-colors">
                            <span id="timerMin" class="text-5xl font-black tracking-tighter block leading-none mb-1">--</span>
                            <span class="text-[9px] font-black uppercase opacity-60 tracking-[2px]">MENIT</span>
                        </div>
                        <span class="text-3xl font-black text-white/30 animate-pulse">:</span>
                        <div class="bg-white/10 backdrop-blur-xl rounded-[1.5rem] p-4 min-w-[85px] text-center border border-white/10 group-hover:bg-white/20 transition-colors">
                            <span id="timerSec" class="text-5xl font-black tracking-tighter block leading-none mb-1">--</span>
                            <span class="text-[9px] font-black uppercase opacity-60 tracking-[2px]">DETIK</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-7">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[3px] mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-xl text-blue-600"></i>
                    Statistik Sesi Ini
                </h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-5 bg-emerald-50 rounded-[1.5rem] border border-emerald-100 group hover:shadow-md hover:shadow-emerald-100/50 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-50">
                                <i class="ph-bold ph-check text-xl"></i>
                            </div>
                            <span class="font-bold text-slate-700 text-sm">Hadir</span>
                        </div>
                        <span id="countHadir" class="text-2xl font-black text-emerald-600">0</span>
                    </div>

                    <div class="flex items-center justify-between p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100 group hover:shadow-md hover:shadow-slate-100/50 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 shadow-sm border border-slate-50">
                                <i class="ph-bold ph-users-three text-xl"></i>
                            </div>
                            <span class="font-bold text-slate-600 text-sm">Belum</span>
                        </div>
                        <span id="countBelum" class="text-2xl font-black text-slate-400">--</span>
                    </div>

                    <div class="relative pt-2">
                        <div class="flex justify-between text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            <span>Target Kehadiran</span>
                            <span id="percentText" class="text-blue-600">0%</span>
                        </div>
                        <div class="h-3 bg-slate-100 rounded-full overflow-hidden shadow-inner p-0.5">
                            <div id="progressBar" class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000 shadow-md" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('asisten.absensi') }}" class="w-full py-5 bg-white border border-slate-200 hover:border-blue-500 hover:text-blue-600 text-slate-600 font-black rounded-[1.5rem] transition-all flex items-center justify-center gap-3 group hover:shadow-lg hover:shadow-blue-50 text-sm">
                    <i class="ph-bold ph-arrow-square-out text-xl group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    Selesai & Keluar Sesi
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Status Modals / Feedback -->
<div id="loaderOverlay" class="fixed inset-0 z-[9999] bg-slate-900/80 backdrop-blur-md hidden items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="w-20 h-20 border-6 border-white/20 border-t-white rounded-full animate-spin"></div>
</div>

<div id="notifModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[420px] max-w-[90%] bg-white p-8 rounded-[2rem] shadow-2xl z-[10000] opacity-0 invisible scale-75 transition-all duration-500 flex flex-col items-center">
    <div id="notifIcon" class="mb-5"></div>
    <h3 id="notifTitle" class="text-2xl font-black text-center mb-2 tracking-tight"></h3>
    <p id="notifMessage" class="text-center text-gray-500 font-medium mb-3 text-sm"></p>
    <p id="notifDetail" class="text-center font-black text-lg text-blue-600 tracking-tight"></p>
</div>

<script>
    /* TIMER LOGIC */
    let durationMinutes = {{ $duration }};
    let today = new Date().toISOString().split('T')[0];
    let KEY_BASE = `pres_ast_{{ $matakuliah->id }}_{{ $pertemuan }}_${today}`;
    let KEY_TIME = KEY_BASE + "_end";
    let KEY_DUR = KEY_BASE + "_dur";

    let savedEnd = localStorage.getItem(KEY_TIME);
    let savedDur = localStorage.getItem(KEY_DUR);

    if (!savedEnd || savedDur != durationMinutes) {
        savedEnd = Date.now() + durationMinutes * 60 * 1000;
        localStorage.setItem(KEY_TIME, savedEnd);
        localStorage.setItem(KEY_DUR, durationMinutes);
    } else {
        savedEnd = parseInt(savedEnd);
    }

    function updateTimer() {
        let diff = Math.floor((savedEnd - Date.now()) / 1000);
        if (diff <= 0) {
            document.getElementById("timerMin").textContent = "00";
            document.getElementById("timerSec").textContent = "00";
            document.getElementById("rfidInput").disabled = true;
            document.getElementById("manualBtn").disabled = true;
            localStorage.removeItem(KEY_TIME);
            return;
        }
        let m = Math.floor(diff / 60);
        let s = diff % 60;
        document.getElementById("timerMin").textContent = String(m).padStart(2, '0');
        document.getElementById("timerSec").textContent = String(s).padStart(2, '0');
        setTimeout(updateTimer, 1000);
    }
    updateTimer();

    /* SCAN & UI LOGIC */
    const rfidInput = document.getElementById("rfidInput");
    const countHadirEl = document.getElementById("countHadir");
    const countBelumEl = document.getElementById("countBelum");
    const progressBar = document.getElementById("progressBar");
    const percentText = document.getElementById("percentText");
    const loaderOverlay = document.getElementById("loaderOverlay");
    const notifModal = document.getElementById("notifModal");
    const notifIcon = document.getElementById("notifIcon");
    const notifTitle = document.getElementById("notifTitle");
    const notifMessage = document.getElementById("notifMessage");
    const notifDetail = document.getElementById("notifDetail");

    let totalHadir = 0;
    let totalPeserta = 0;

    function fetchStats() {
        fetch(`/asisten/absensi/stats?matakuliah_id={{ $matakuliah->id }}&pertemuan={{ $pertemuan }}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                totalHadir = data.hadir;
                totalPeserta = data.total;
                updateStatsUI();
            }
        });
    }
    fetchStats();

    function updateStatsUI() {
        countHadirEl.textContent = totalHadir;
        if (totalPeserta > 0) {
            let belum = totalPeserta - totalHadir;
            countBelumEl.textContent = belum < 0 ? 0 : belum;
            let p = Math.round((totalHadir / totalPeserta) * 100);
            progressBar.style.width = `${p}%`;
            percentText.textContent = `${p}%`;
        }
    }

    function showLoader() {
        loaderOverlay.classList.remove('hidden');
        loaderOverlay.classList.add('flex');
        setTimeout(() => loaderOverlay.style.opacity = '1', 10);
    }

    function hideLoader() {
        loaderOverlay.style.opacity = '0';
        setTimeout(() => {
            loaderOverlay.classList.remove('flex');
            loaderOverlay.classList.add('hidden');
        }, 300);
    }

    function showNotification(success, message, detail = '') {
        hideLoader();
        
        if (success) {
            notifIcon.innerHTML = `
                <div class="w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-xl shadow-emerald-100 ring-8 ring-emerald-50 mb-4 animate-bounce">
                    <i class="ph-bold ph-check text-4xl"></i>
                </div>
            `;
            notifTitle.textContent = "Berhasil! ✓";
            notifTitle.className = "text-2xl font-black text-center mb-2 text-emerald-600 tracking-tight";
        } else {
            notifIcon.innerHTML = `
                <div class="w-20 h-20 rounded-full bg-rose-500 flex items-center justify-center text-white shadow-xl shadow-rose-100 ring-8 ring-rose-50 mb-4 animate-shake">
                    <i class="ph-bold ph-x text-4xl"></i>
                </div>
            `;
            notifTitle.textContent = "Gagal! ✗";
            notifTitle.className = "text-2xl font-black text-center mb-2 text-rose-600 tracking-tight";
        }
        
        notifMessage.textContent = message;
        notifDetail.textContent = detail;
        
        notifModal.classList.remove('invisible');
        notifModal.classList.add('opacity-100', 'scale-100');
        
        setTimeout(() => {
            notifModal.classList.add('invisible');
            notifModal.classList.remove('opacity-100', 'scale-100');
        }, 3000);
    }

    function processScan(identifier) {
        if (!identifier) return;
        showLoader();

        fetch("{{ route('asisten.absensi.store.rfid') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                identifier,
                matakuliah_id: {{ $matakuliah->id }},
                pertemuan: {{ $pertemuan }}
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                totalHadir++;
                updateStatsUI();
                showNotification(true, data.message, `${data.nama} — ${data.nim}`);
            } else {
                showNotification(false, data.message);
            }
            rfidInput.value = "";
            rfidInput.focus();
        })
        .catch(error => {
            hideLoader();
            showNotification(false, "Kesalahan Jaringan!", "Koneksi ke server terputus.");
            rfidInput.value = "";
            rfidInput.focus();
        });
    }

    rfidInput.addEventListener("input", e => {
        let v = e.target.value.trim();
        // Deteksi NIM (contoh 8 digit) atau RFID (12 digit)
        if (v.length === 8 && /^2[0-5]/.test(v)) {
            processScan(v);
        } else if (v.length === 12) {
            processScan(v); 
        }
    });

    rfidInput.addEventListener("keypress", e => {
        if (e.key === "Enter") {
            processScan(rfidInput.value.trim());
        }
    });

    document.getElementById("manualBtn").onclick = () => processScan(rfidInput.value.trim());
    
    // Auto focus back on input every click
    document.addEventListener('click', () => rfidInput.focus());
</script>

</body>
</html>