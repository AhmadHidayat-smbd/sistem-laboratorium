@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')
@section('page_title', 'Tambah Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.mahasiswa') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Data Mahasiswa</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-student text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Registrasi Mahasiswa Baru</h2>
                    <p class="text-sm font-medium text-gray-400">Sinkronisasi identitas mahasiswa dengan kartu RFID.</p>
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

            <form method="POST" action="{{ route('admin.mahasiswa.store') }}" class="space-y-8">
                @csrf

                <!-- Nama -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nama Lengkap Mahasiswa</label>
                    <div class="relative group">
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                               class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                               placeholder="Contoh: Budi Santoso">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="ph-bold ph-user text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- NIM -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">NIM (Nomor Induk Mahasiswa)</label>
                        <div class="relative group">
                            <input type="text" name="nim" value="{{ old('nim') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: 22330001">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-hash text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- RFID -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">RFID UID</label>
                        <div class="relative group">
                            <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-black text-gray-700 font-mono"
                                   placeholder="Format: A1B2C3D4">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-broadcast text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Kata Sandi (Opsional)</label>
                    <div class="relative group">
                        <input type="password" name="password"
                               class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                               placeholder="Kosongkan untuk menggunakan NIM sebagai password default">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="ph-bold ph-lock-key text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-check-circle text-2xl group-hover:rotate-12 transition-transform"></i>
                        <span>Simpan Data Mahasiswa</span>
                    </button>
                    <a href="{{ route('admin.mahasiswa') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Note -->
    <div class="mt-8 bg-blue-50/50 border border-blue-100 p-6 rounded-[2rem] flex items-start gap-4">
        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="ph-bold ph-shield-check text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-blue-900 text-sm mb-1 uppercase tracking-tight">Peringatan Keamanan Kartu</h4>
            <p class="text-xs text-blue-700 leading-relaxed font-medium">RFID UID bersifat unik. Pastikan kartu yang didaftarkan adalah milik mahasiswa yang bersangkutan untuk menghindari kesalahan pencatatan kehadiran otomatis di laboratorium.</p>
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