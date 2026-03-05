@extends('layouts.admin')

@section('title', 'Tambah Pembayaran')
@section('page_title', 'Input Data Pembayaran Baru')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in shadow-2xl shadow-blue-50">
    <!-- Back Button -->
    <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Daftar Pembayaran</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-wallet text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Form Pembayaran Baru</h2>
                    <p class="text-sm font-medium text-gray-400">Silakan isi detail transaksi di bawah ini dengan benar</p>
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

            <form action="{{ route('admin.pembayaran.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nama Lengkap Mahasiswa</label>
                        <div class="relative group">
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Masukkan Nama Lengkap">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-user text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- NIM -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">NIM (Nomor Induk Mahasiswa)</label>
                        <div class="relative group">
                            <input type="text" name="nim" value="{{ old('nim') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Masukkan NIM">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-identification-card text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Tahun Ajaran -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Tahun Ajaran</label>
                        <div class="relative group">
                            <select name="tahun_ajaran" required
                                    class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700 appearance-none">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <option value="2022-1" {{ old('tahun_ajaran') == '2022-1' ? 'selected' : '' }}>2022-1</option>
                                <option value="2022-2" {{ old('tahun_ajaran') == '2022-2' ? 'selected' : '' }}>2022-2</option>
                                <option value="2023-1" {{ old('tahun_ajaran') == '2023-1' ? 'selected' : '' }}>2023-1</option>
                                <option value="2023-2" {{ old('tahun_ajaran') == '2023-2' ? 'selected' : '' }}>2023-2</option>
                                <option value="2024-1" {{ old('tahun_ajaran') == '2024-1' ? 'selected' : '' }}>2024-1</option>
                                <option value="2024-2" {{ old('tahun_ajaran') == '2024-2' ? 'selected' : '' }}>2024-2</option>
                                <option value="2025-1" {{ old('tahun_ajaran') == '2025-1' ? 'selected' : '' }}>2025-1</option>
                                <option value="2025-2" {{ old('tahun_ajaran') == '2025-2' ? 'selected' : '' }}>2025-2</option>
                            </select>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors pointer-events-none">
                                <i class="ph-bold ph-calendar-blank text-xl"></i>
                            </div>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="ph-bold ph-caret-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nominal -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nominal Pembayaran (Rp)</label>
                        <div class="relative group">
                            <input type="number" name="nominal" value="{{ old('nominal') }}" required min="0"
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: 500000">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-wallet text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Pembayaran -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Tanggal Transaksi</label>
                    <div class="relative group max-w-md">
                        <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" required
                               class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors pointer-events-none">
                            <i class="ph-bold ph-calendar-check text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-plus-circle text-2xl group-hover:rotate-90 transition-transform"></i>
                        <span>Simpan Data Pembayaran</span>
                    </button>
                    <p class="text-center mt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pastikan data yang diinput sudah sesuai dengan bukti bayar</p>
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
