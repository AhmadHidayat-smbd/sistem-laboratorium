<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">
    <title> Sesi Presensi </title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-[#F8FAFC] min-h-screen text-slate-900 flex flex-col">

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full">
    <div class="fade-in-up">
        <!-- Back Button -->
        <a href="{{ route('asisten.absensi') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
            <span>Kembali ke Data Absensi</span>
        </a>

        @if(session('error'))
            <script>window.onload = () => showAppNotif('error', 'Gagal! ✗', "{{ session('error') }}");</script>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 md:p-12">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <i class="ph-fill ph-presentation-chart text-4xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Persiapan Sesi Presensi</h2>
                        <p class="text-slate-500 font-medium mt-1">Pilih mata kuliah dan pertemuan untuk memulai pemindaian mahasiswa.</p>
                    </div>
                </div>

                <form action="{{ route('asisten.tambah-absensi2') }}" method="GET" class="space-y-10">
                    <!-- Mata Kuliah -->
                    <div class="space-y-4">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[2px] ml-2">Pilih Mata Kuliah</label>
                        <div class="relative group">
                            <select name="matakuliah_id" id="matakuliahSelect" required
                                    class="w-full pl-14 pr-8 py-5 bg-slate-50 border border-slate-100 rounded-3xl appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-slate-700 text-lg">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($matakuliah as $mk)
                                    @php $jadwal = $mk->jadwal->first(); @endphp
                                    <option value="{{ $mk->id }}"
                                            data-jammulai="{{ $jadwal->jam_mulai ?? '' }}"
                                            data-jamselesai="{{ $jadwal->jam_selesai ?? '' }}"
                                            data-hari="{{ $jadwal->hari ?? '' }}">
                                        {{ $mk->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-book-open text-2xl"></i>
                            </div>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <i class="ph-bold ph-caret-down text-lg"></i>
                            </div>
                        </div>
                        
                        <!-- Dynamic Jadwal Info -->
                        <div id="jadwalInfo" class="hidden p-6 rounded-3xl bg-indigo-50 border border-indigo-100 transition-all duration-500">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                                    <i class="ph-bold ph-clock-countdown text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p id="jadwalText" class="text-indigo-900 font-bold text-base leading-snug"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Pertemuan -->
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[2px] ml-2">Pertemuan Ke-</label>
                            <div class="relative group">
                                <select name="pertemuan" required
                                        class="w-full pl-14 pr-8 py-5 bg-slate-50 border border-slate-100 rounded-3xl appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-slate-700 text-lg">
                                    <option value="">-- Pilih Pertemuan Ke --</option>
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">Pertemuan Ke-{{ $i }}</option>
                                    @endfor
                                    <option value="9">Sesi Responsi</option>
                                </select>
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="ph-bold ph-hash text-2xl"></i>
                                </div>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <i class="ph-bold ph-caret-down text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[2px] ml-2">Batas Waktu Scan (Menit)</label>
                            <div class="relative group">
                                <input type="number" name="duration" min="1" max="180" required
                                       class="w-full pl-14 pr-6 py-5 bg-slate-50 border border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-slate-700 text-lg"
                                       placeholder="Contoh: 30">
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="ph-bold ph-timer text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white py-6 rounded-3xl font-black text-lg transition-all duration-300 shadow-xl shadow-slate-200 flex items-center justify-center gap-4 group hover:scale-[1.02] active:scale-[0.98]">
                            <i class="ph-bold ph-qr-code text-3xl group-hover:rotate-12 transition-transform"></i>
                            <span>Buka Halaman Pemindaian RFID</span>
                        </button>
                        <p class="text-center text-slate-400 text-xs font-bold uppercase tracking-widest mt-6">Pastikan alat RFID sudah terhubung ke perangkat.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<footer class="bg-white border-t border-slate-200 py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-slate-500 text-sm font-medium">
        <p>© 2026 <span class="font-bold text-blue-600">iTlabs</span> Ecosystem • Smart Attendance</p>
    </div>
</footer>

<!-- Notification Modal ala Login -->
<div id="notifOverlay" class="fixed inset-0 z-[9998] hidden bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
<div id="notifModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[9999] hidden w-full max-w-sm px-4">
    <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl flex flex-col items-center transform scale-90 opacity-0 transition-all duration-300 border border-slate-100" id="notifBox">
        
        <div id="notifIconWrapper" class="w-20 h-20 rounded-full flex items-center justify-center text-white shadow-xl ring-8 mb-4">
            <i id="notifIcon" class="fa-solid text-4xl"></i>
        </div>

        <h3 id="notifTitle" class="text-2xl font-black text-center mb-2 tracking-tight"></h3>
        
        <p id="notifMessage" class="text-slate-500 text-center font-medium leading-relaxed mb-6"></p>

        <button onclick="closeAppNotif()" class="w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold transition-all active:scale-95">Tutup</button>
    </div>
</div>

<script>
    function showAppNotif(type, title, message) {
        const overlay = document.getElementById('notifOverlay');
        const modal = document.getElementById('notifModal');
        const box = document.getElementById('notifBox');
        const iconWrapper = document.getElementById('notifIconWrapper');
        const icon = document.getElementById('notifIcon');
        const titleEl = document.getElementById('notifTitle');
        const msgEl = document.getElementById('notifMessage');

        if (type === 'success') {
            iconWrapper.className = 'w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-xl shadow-emerald-100 ring-8 ring-emerald-50 mb-4 animate-bounce';
            icon.className = 'fa-solid fa-check text-4xl';
            titleEl.className = 'text-2xl font-black text-center mb-2 text-emerald-600 tracking-tight';
        } else {
            iconWrapper.className = 'w-20 h-20 rounded-full bg-rose-500 flex items-center justify-center text-white shadow-xl shadow-rose-100 ring-8 ring-rose-50 mb-4 animate-shake';
            icon.className = 'fa-solid fa-xmark text-4xl';
            titleEl.className = 'text-2xl font-black text-center mb-2 text-rose-600 tracking-tight';
        }

        titleEl.innerText = title;
        msgEl.innerText = message;

        overlay.classList.remove('hidden');
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            box.classList.remove('scale-90', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAppNotif() {
        const overlay = document.getElementById('notifOverlay');
        const modal = document.getElementById('notifModal');
        const box = document.getElementById('notifBox');
        
        overlay.classList.add('opacity-0');
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-90', 'opacity-0');
        
        setTimeout(() => {
            overlay.classList.add('hidden');
            modal.classList.add('hidden');
        }, 300);
    }

    const hariIndo = {
        "monday": "Senin", "tuesday": "Selasa", "wednesday": "Rabu",
        "thursday": "Kamis", "friday": "Jumat", "saturday": "Sabtu", "sunday": "Minggu"
    };

    document.getElementById("matakuliahSelect").addEventListener("change", function () {
        let opt = this.selectedOptions[0];
        let jm = opt.getAttribute("data-jammulai");
        let js = opt.getAttribute("data-jamselesai");
        let hari = opt.getAttribute("data-hari");
        
        const box = document.getElementById("jadwalInfo");
        const text = document.getElementById("jadwalText");

        if (jm && js && hari) {
            box.classList.remove('hidden');
            box.classList.add('animate-fade-in');
            let normalizedHari = hari.toLowerCase().trim();
            let hariTampil = hariIndo[normalizedHari] ?? hari;
            text.innerHTML = `Terjadwal setiap hari <span class="text-indigo-600 font-black">${hariTampil}</span> pukul <span class="text-indigo-600 font-black">${jm} - ${js}</span> WIB`;
        } else {
            box.classList.add('hidden');
        }
    });
</script>

</body>
</html>