<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Mahasiswa - {{ $mahasiswa->nama }}</title>
    <link rel="icon" href="{{ asset('images/logoit.png') }}" type="image/png">
    
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
    </style>
    @livewireStyles
</head>

<body class="bg-[#F8FAFC] min-h-screen text-slate-900 flex flex-col">

<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <div class="flex items-center gap-2.5">
           <img src="{{ asset('images/logoit.png') }}" class="h-20 w-auto" style="mix-blend-mode: multiply;">
                <span class="font-bold text-slate-900 text-lg tracking-tight hidden sm:block">Portal Mahasiswa<span class="text-blue-600 font-extrabold">.</span></span>
                <span class="font-bold text-slate-900 text-lg tracking-tight sm:hidden">Portal Mahasiswa</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('mahasiswa.profile') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 text-slate-600 font-bold text-sm rounded-xl transition-colors shadow-sm">
                    <i class="ph-bold ph-gear-six"></i>
                    <span class="hidden sm:inline">Pengaturan</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-slate-600 font-bold text-sm rounded-xl transition-colors shadow-sm">
                        <i class="ph-bold ph-sign-out"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 w-full flex-1 space-y-6 fade-in-up">
    
    <div class="bg-white rounded-[1.5rem] p-5 sm:p-8 border border-slate-200 shadow-sm relative overflow-hidden">
        <div class="absolute right-[-20px] top-[-20px] w-32 h-32 bg-blue-50 rounded-full opacity-60"></div>
        <div class="absolute right-[40px] top-[40px] w-12 h-12 bg-indigo-50 rounded-full opacity-60"></div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 relative z-10">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center text-2xl sm:text-3xl font-extrabold shadow-lg shadow-blue-200 shrink-0">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">{{ $mahasiswa->nama }}</h1>
                
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-2.5">
                    <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg uppercase tracking-wider">
                        {{ $mahasiswa->nim }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg text-[10px] sm:text-xs font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Status Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="fade-in-up" style="animation-delay: 0.1s">
        <livewire:mahasiswa-attendance-dashboard />
    </div>

</main>

<footer class="bg-white border-t border-slate-200 py-8 mt-auto fade-in-up" style="animation-delay: 0.2s">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center gap-5 text-center">
        
        <div class="inline-flex items-center gap-4 bg-slate-50 border border-slate-200 px-5 py-3 rounded-[1.2rem] shadow-sm">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-slate-200 text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
            </div>
            <div class="text-left">
                <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">ID Token Hardware</p>
                <p class="text-slate-800 text-sm font-mono font-bold tracking-widest">
                    <span class="text-slate-400">•••• ••• </span>{{ substr($mahasiswa->rfid_uid ?? '000', -3) }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
                    <span>&copy; 2026</span>
                    <span class="text-blue-600 font-bold">iTlabs</span>
                    <span>Janabadra. All rights reserved.</span>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>