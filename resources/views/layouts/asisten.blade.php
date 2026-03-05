<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Asisten Lab') - iTlabs</title>
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-shake { animation: shake 0.4s ease-in-out; }
        .animate-bounce-custom { animation: bounce 0.6s ease-in-out 3; }
        .nav-link-active { @apply text-blue-600 bg-blue-50 font-semibold; }
        .nav-link-inactive { @apply text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium; }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] min-h-screen text-slate-900 flex flex-col">

<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('asisten.dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logoit.png') }}" 
                             alt="Logo iTlabs" 
                             class="h-10 w-10 object-contain rounded-lg shadow-sm border border-gray-100">
                        <span class="font-bold text-slate-900 text-lg tracking-tight leading-none">iTlabs<span class="text-blue-600 font-extrabold">.</span></span>
                    </div>
                </a>

                <div class="hidden md:block h-6 w-px bg-slate-200"></div>
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('asisten.dashboard') }}" wire:navigate 
                       class="px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('asisten.dashboard') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('asisten.peserta') }}" wire:navigate 
                       class="px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('asisten.peserta*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                        Data Peserta
                    </a>
                    <a href="{{ route('asisten.absensi') }}" wire:navigate 
                       class="px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('asisten.absensi') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                        Data Absensi Mahasiswa
                    </a>
                    <a href="{{ route('asisten.absensi-dosen') }}" wire:navigate 
                       class="px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('asisten.absensi-dosen*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                        Absensi Dosen
                    </a>
                    <a href="{{ route('asisten.absensi-asisten') }}" wire:navigate 
                       class="px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('asisten.absensi-asisten*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
                        Absensi Asisten
                    </a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-3 pr-4 border-r border-slate-200">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] font-medium text-slate-500 mt-1 uppercase tracking-wider">Asisten Lab</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-9 h-9 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 font-semibold rounded-r-lg animate-fade-in flex items-center gap-3">
                <div class="bg-emerald-500 p-2 rounded-lg text-white">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>
    @yield('content')
</main>

    <footer class="h-14 bg-white border-t border-gray-200 flex items-center justify-center text-sm font-medium text-gray-500 px-6">
            <div class="flex items-center gap-2">
                <span>&copy; 2026</span>
                <span class="text-blue-600 font-bold">iTlabs</span>
                <span>Janabadra. All rights reserved.</span>
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
                iconWrapper.className = 'w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-xl shadow-emerald-100 ring-8 ring-emerald-50 mb-4 animate-bounce-custom';
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

        @if(session('error'))
            window.addEventListener('load', () => showAppNotif('error', 'Gagal! ✗', "{{ session('error') }}"));
        @endif
    </script>
@livewireScripts
@stack('scripts')
</body>
</html>
