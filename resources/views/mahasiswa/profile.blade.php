<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - {{ $mahasiswa->nama }}</title>
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
</head>

<body class="bg-[#F8FAFC] min-h-screen text-slate-900 flex flex-col">

<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 bg-slate-100 group-hover:bg-blue-600 rounded-lg flex items-center justify-center transition-all">
                    <i class="ph-bold ph-arrow-left text-slate-600 group-hover:text-white transition-all"></i>
                </div>
                <span class="font-bold text-slate-900 text-lg tracking-tight">Kembali ke Dashboard</span>
            </a>

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-lock-key text-white"></i>
                </div>
                <span class="font-bold text-slate-900 text-lg tracking-tight hidden sm:block">Akun Saya</span>
            </div>

        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-1 space-y-6 fade-in-up">
    
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 text-emerald-700 animate-fade-in">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-[1.5rem] p-6 sm:p-10 border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Info Section -->
            <div class="md:w-1/3 space-y-6">
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Informasi Mahasiswa</h3>
                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-md">
                            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $mahasiswa->nama }}</p>
                            <p class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded mt-1">{{ $mahasiswa->nim }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                    <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="ph-fill ph-info"></i> Keamanan Akun
                    </h4>
                    <p class="text-[11px] leading-relaxed text-blue-600/80 font-medium">
                        Gunakan kombinasi password yang kuat untuk menjaga keamanan data praktikum Anda. Jangan berikan password Anda kepada siapa pun.
                    </p>
                </div>
            </div>

            <!-- Form Section -->
            <div class="md:w-2/3">
                <form action="{{ route('mahasiswa.update-password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                            <i class="ph-fill ph-shield-checkered text-blue-600"></i>
                            Ganti Password
                        </h2>

                        <!-- Current Password -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Password Saat Ini</label>
                            <div class="relative group">
                                <input type="password" name="current_password" required
                                       class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700 @error('current_password') border-rose-500 bg-rose-50 @enderror">
                                <i class="ph-fill ph-key absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            @error('current_password')
                                <p class="text-[11px] font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="h-px bg-slate-100 my-2"></div>

                        <!-- New Password -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Password Baru</label>
                            <div class="relative group">
                                <input type="password" name="password" required
                                       class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700 @error('password') border-rose-500 bg-rose-50 @enderror">
                                <i class="ph-fill ph-lock-key-open absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            @error('password')
                                <p class="text-[11px] font-bold text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Konfirmasi Password Baru</label>
                            <div class="relative group">
                                <input type="password" name="password_confirmation" required
                                       class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700">
                                <i class="ph-fill ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-xl text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-lg"></i>
                            Update Password Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<footer class="bg-white border-t border-slate-200 py-8 mt-auto fade-in-up">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center gap-5 text-center">
        <div class="flex items-center gap-2">
            <span>&copy; 2026</span>
            <span class="text-blue-600 font-bold">iTlabs</span>
            <span>Janabadra. All rights reserved.</span>
        </div>
    </div>
</footer>

</body>
</html>
