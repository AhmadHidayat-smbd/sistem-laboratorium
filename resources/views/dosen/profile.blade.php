@extends('layouts.dosen')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Profile Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative flex items-center gap-6">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-3xl font-black border border-white/20 shadow-lg">
                {{ strtoupper(substr($dosen->nama, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-2xl font-black tracking-tight">{{ $dosen->nama }}</h3>
                <p class="text-blue-100 font-medium text-sm mt-1">
                    <i class="ph-bold ph-envelope-simple mr-1"></i>{{ $dosen->email }}
                </p>
            </div>
        </div>
    </div>

    <!-- Update Profile Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="ph-fill ph-user-circle text-xl text-blue-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Informasi Profil</h3>
                <p class="text-xs text-gray-500 font-medium">Perbarui nama dan email Anda</p>
            </div>
        </div>
        <form action="{{ route('dosen.profile.update') }}" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <i class="ph-bold ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $dosen->nama) }}" 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-800 placeholder-gray-400"
                           placeholder="Masukkan nama lengkap">
                </div>
                @error('nama')
                    <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                        <i class="ph-fill ph-warning-circle"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <i class="ph-bold ph-envelope-simple absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="email" name="email" id="email" value="{{ old('email', $dosen->email) }}" 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-800 placeholder-gray-400"
                           placeholder="Masukkan email">
                </div>
                @error('email')
                    <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                        <i class="ph-fill ph-warning-circle"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98] flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <i class="ph-fill ph-lock text-xl text-amber-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Ubah Password</h3>
                <p class="text-xs text-gray-500 font-medium">Pastikan menggunakan password yang kuat dan mudah diingat</p>
            </div>
        </div>
        <form action="{{ route('dosen.profile.password') }}" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
                <div class="relative">
                    <i class="ph-bold ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full pl-12 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-800 placeholder-gray-400"
                           placeholder="Masukkan password saat ini">
                    <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ph-bold ph-eye text-lg"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                        <i class="ph-fill ph-warning-circle"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                <div class="relative">
                    <i class="ph-bold ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="password" name="password" id="password" 
                           class="w-full pl-12 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-800 placeholder-gray-400"
                           placeholder="Masukkan password baru (min. 8 karakter)">
                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ph-bold ph-eye text-lg"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
                        <i class="ph-fill ph-warning-circle"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <div class="relative">
                    <i class="ph-bold ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full pl-12 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-800 placeholder-gray-400"
                           placeholder="Ulangi password baru">
                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ph-bold ph-eye text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-sm transition-all duration-200 shadow-sm hover:shadow-md active:scale-[0.98] flex items-center gap-2">
                    <i class="ph-bold ph-key text-lg"></i>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ph-eye');
            icon.classList.add('ph-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('ph-eye-slash');
            icon.classList.add('ph-eye');
        }
    }
</script>
@endpush
@endsection
