@extends('layouts.admin')

@section('title', 'Tambah Dosen')
@section('page_title', 'Tambah Dosen')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.dosen') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Daftar Dosen</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-chalkboard-teacher text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Form Data Dosen</h2>
                    <p class="text-sm font-medium text-gray-400">Masukkan informasi dosen pengampu mata kuliah.</p>
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-rose-50 border-2 border-rose-100 p-6 rounded-2xl mb-8 animate-shake">
                <div class="flex gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-2xl"></i>
                    <div>
                        <h4 class="font-black text-rose-800 mb-1 tracking-tight">Terjadi kesalahan input:</h4>
                        <ul class="text-sm text-rose-600 font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nama Lengkap Dosen</label>
                        <div class="relative group">
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: Dr. Ahmad Fauzi, M.Kom">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-user text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Alamat Email</label>
                        <div class="relative group">
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="dosen@itlabs.ac.id">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-envelope-simple text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Password -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Kata Sandi</label>
                        <div class="relative group">
                            <input type="password" name="password" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Minimal 6 karakter">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-lock-key text-xl"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 px-2 flex items-center gap-1">
                            <i class="ph-bold ph-info"></i>
                            Kombinasi huruf dan angka disarankan.
                        </p>
                    </div>

                    <!-- RFID UID -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">RFID UID <span class="lowercase tracking-normal font-medium text-gray-400">(Opsional)</span></label>
                        <div class="relative group">
                            <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" maxlength="50"
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: 1234567890AB">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-identification-card text-xl"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 px-2 flex items-center gap-1">
                            <i class="ph-bold ph-info"></i>
                            Untuk login via tap card.
                        </p>
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-floppy-disk text-2xl group-hover:scale-110 transition-transform"></i>
                        <span>Simpan Data Dosen</span>
                    </button>
                    <a href="{{ route('admin.dosen') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out;
    }
</style>
@endpush
