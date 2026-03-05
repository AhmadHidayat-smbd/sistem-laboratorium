@extends('layouts.admin')

@section('title', 'Scan Presensi RFID')
@section('page_title', 'Sesi Presensi Aktif')

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Scan Area -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative">
                <!-- Session Header -->
                <div class="p-8 bg-gray-900 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full">Sesi Aktif</span>
                            <span class="text-gray-400 font-bold text-xs">Pertemuan Ke-{{ $pertemuan }}</span>
                        </div>
                        <h2 class="text-3xl font-black tracking-tight mb-2">{{ $matakuliah->nama }}</h2>
                        <div class="flex items-center gap-4 text-gray-400 text-sm font-medium">
                            <span class="flex items-center gap-1"><i class="ph-bold ph-calendar"></i> {{ date('d M Y') }}</span>
                            <span class="flex items-center gap-1"><i class="ph-bold ph-hourglass"></i> {{ $duration }} Menit</span>
                        </div>
                    </div>
                    <div class="absolute right-0 top-0 opacity-10 translate-x-1/4 -translate-y-1/4">
                        <i class="ph-fill ph-broadcast text-[200px]"></i>
                    </div>
                </div>

                <!-- Input Zone -->
                <div class="p-10">
                    <div class="mb-10 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 text-blue-600 rounded-[2rem] mb-6 shadow-xl shadow-blue-100/50 animate-pulse">
                            <i class="ph-fill ph-scan text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 mb-2">Siap Memindai Kartu</h3>
                        <p class="text-sm font-medium text-gray-400">Silakan tempelkan kartu RFID mahasiswa pada alat pemindai.</p>
                    </div>

                    <div class="relative group max-w-md mx-auto mb-8">
                        <input id="rfidInput" autofocus autocomplete="off" type="text"
                               class="w-full pl-14 pr-6 py-6 bg-gray-50 border-2 border-gray-100 rounded-[2rem] focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-black text-2xl text-center text-gray-800 tracking-[0.2em]"
                               placeholder="........">
                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors">
                            <i class="ph-bold ph-broadcast text-2xl"></i>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button id="manualBtn" class="flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-widest hover:text-blue-700 transition-colors py-2 px-4 rounded-xl hover:bg-blue-50">
                            <i class="ph-bold ph-keyboard"></i>
                            Input Manual (Enter)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Scans / Feedback -->
            <div id="notifArea" class="space-y-4 min-h-[100px]">
                <div id="idleMsg" class="text-center py-10 border-2 border-dashed border-gray-200 rounded-[2.5rem]">
                    <p class="text-gray-400 font-bold text-sm italic">Menunggu pemindaian pertama...</p>
                </div>
                <!-- Dynamic cards injected here -->
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Countdown Card -->
            <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-200 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[3px] text-blue-200 mb-4 text-center">Waktu Tersisa</p>
                    <div class="flex items-center justify-center gap-4">
                        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 min-w-[100px] text-center">
                            <span id="timerMin" class="text-5xl font-black tracking-tighter block leading-none mb-1">--</span>
                            <span class="text-[9px] font-black uppercase opacity-60">MENIT</span>
                        </div>
                        <span class="text-4xl font-black text-white/50 animate-pulse">:</span>
                        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-4 min-w-[100px] text-center">
                            <span id="timerSec" class="text-5xl font-black tracking-tighter block leading-none mb-1">--</span>
                            <span class="text-[9px] font-black uppercase opacity-60">DETIK</span>
                        </div>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-lg text-blue-600"></i>
                    Statistik Kehadiran
                </h4>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="ph-bold ph-check text-lg"></i>
                            </div>
                            <span class="font-bold text-gray-700">Hadir</span>
                        </div>
                        <span id="countHadir" class="text-2xl font-black text-emerald-600">0</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400 shadow-sm">
                                <i class="ph-bold ph-users-three text-lg"></i>
                            </div>
                            <span class="font-bold text-gray-600">Belum Scan</span>
                        </div>
                        <span id="countBelum" class="text-2xl font-black text-gray-400">--</span>
                    </div>

                    <div class="relative pt-4">
                        <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                            <span>Progres Kehadiran</span>
                            <span id="percentText">0%</span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div id="progressBar" class="h-full bg-blue-600 rounded-full transition-all duration-1000 shadow-lg shadow-blue-100" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-4">
                <a href="{{ route('admin.absensi') }}" class="w-full py-5 bg-white border border-gray-100 hover:border-blue-200 hover:bg-blue-50 text-gray-600 font-bold rounded-[1.5rem] transition-all flex items-center justify-center gap-3 group">
                    <i class="ph-bold ph-arrow-square-out text-xl group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    Selesai & Rekap Data
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Loader Overlay -->
<div id="loaderOverlay" class="fixed inset-0 z-[9999] bg-black/70 backdrop-blur-md hidden items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="w-16 h-16 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
</div>

<!-- Modal Notifikasi -->
<div id="notifModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[450px] max-w-[90%] bg-white p-10 rounded-[2.5rem] shadow-2xl z-[10000] opacity-0 invisible scale-75 transition-all duration-500 flex flex-col items-center">
    <div id="notifIcon" class="mb-6"></div>
    <h3 id="notifTitle" class="text-2xl font-black text-center mb-2 tracking-tight"></h3>
    <p id="notifMessage" class="text-center text-gray-500 font-medium mb-4"></p>
    <p id="notifDetail" class="text-center font-black text-xl text-blue-600 tracking-tight"></p>
</div>
@endsection

@push('scripts')
<script>
/* TIMER LOGIC */
let durationMinutes = {{ $duration }};
let today = new Date().toISOString().split('T')[0];
let KEY_BASE = `pres_adm_{{ $matakuliah->id }}_{{ $pertemuan }}_${today}`;
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

/* SCAN & STATS LOGIC */
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
    fetch(`/admin/absensi/stats?matakuliah_id={{ $matakuliah->id }}&pertemuan={{ $pertemuan }}`)
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            totalHadir = data.hadir;
            totalPeserta = data.total;
            updateUI();
        }
    });
}
fetchStats();

function updateUI() {
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
            <div class="w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-xl shadow-emerald-100 ring-8 ring-emerald-50 mb-4 animate-bounce-short">
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

function sendPresensi(identifier) {
    if (!identifier) return;
    showLoader();

    fetch("{{ route('admin.absensi.store.rfid') }}", {
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
            if (data.message.includes('Presensi berhasil')) {
                totalHadir++;
                updateUI();
            }
            showNotification(true, data.message, `${data.nama} (${data.nim})`);
        } else {
            showNotification(false, data.message);
        }
        rfidInput.value = "";
        rfidInput.focus();
    })
    .catch(error => {
        hideLoader();
        showNotification(false, "Kesalahan Sistem!", "Koneksi terputus");
        rfidInput.value = "";
        rfidInput.focus();
    });
}

function playStatusSound(success) {
    // Optional: Add Beep sounds here
}

rfidInput.addEventListener("input", e => {
    let v = e.target.value.trim();
    if (v.length === 8 && /^2[0-5]/.test(v)) {
        sendPresensi(v);
    } else if (v.length === 12) {
        sendPresensi(v);
    }
});

rfidInput.addEventListener("keypress", e => {
    if (e.key === "Enter") {
        sendPresensi(rfidInput.value.trim());
    }
});

document.getElementById("manualBtn").onclick = () => sendPresensi(rfidInput.value.trim());

// Auto focus back on input
document.addEventListener('click', () => rfidInput.focus());
</script>
@endpush

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }
    @keyframes scan-in {
        from { opacity: 0; transform: scale(0.9) translateY(-10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-scan-in {
        animation: scan-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
</style>
@endpush
