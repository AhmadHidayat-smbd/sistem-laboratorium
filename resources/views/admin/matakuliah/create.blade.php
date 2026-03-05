@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')
@section('page_title', 'Tambah Mata Kuliah')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.matakuliah') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Mata Kuliah</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-book-open text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Data Matakuliah Baru</h2>
                    <p class="text-sm font-medium text-gray-400">Daftarkan kode dan nama mata kuliah praktikum.</p>
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

            <form method="POST" action="{{ route('admin.matakuliah.store') }}" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kode -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Kode MK</label>
                        <div class="relative group">
                            <input type="text" name="kode" value="{{ old('kode') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-black text-gray-700 font-mono uppercase"
                                   placeholder="IF101">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-key text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="md:col-span-2 space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nama Mata Kuliah</label>
                        <div class="relative group">
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: Pemrograman Mobile Berbasis Lokasi">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-book text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tahun Ajaran -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Tahun Ajaran / Semester</label>
                    <div class="relative group">
                        @php
                            $currentYear = (int) date('Y');
                            $currentMonth = (int) date('m');
                            $defaultTA = $currentMonth >= 7 ? $currentYear . '-1' : ($currentYear - 1) . '-2';
                        @endphp
                        <select name="tahun_ajaran" required
                                class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700 appearance-none">
                            @for($y = $currentYear + 2; $y >= $currentYear - 1; $y--)
                                @for($s = 2; $s >= 1; $s--)
                                    @php $val = $y . '-' . $s; @endphp
                                    <option value="{{ $val }}" {{ old('tahun_ajaran', $defaultTA) == $val ? 'selected' : '' }}>
                                        {{ $val }} — Semester {{ $s == 1 ? 'Ganjil' : 'Genap' }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="ph-bold ph-calendar text-xl"></i>
                        </div>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <i class="ph-bold ph-caret-down text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 ml-2 flex items-center gap-1">
                        <i class="ph-fill ph-info"></i>
                        Format: Tahun-Semester (contoh: 2025-1 = Ganjil, 2025-2 = Genap)
                    </p>
                </div>

                <!-- Dosen Pengampu -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Dosen Pengampu</label>
                    <div class="relative group">
                        <select name="dosen_id"
                                class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700 appearance-none">
                            <option value="">-- Pilih Dosen (Opsional) --</option>
                            @foreach($dosen as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="ph-bold ph-chalkboard-teacher text-xl"></i>
                        </div>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <i class="ph-bold ph-caret-down text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 ml-2 flex items-center gap-1">
                        <i class="ph-fill ph-info"></i>
                        Dosen dapat ditambahkan/diubah nanti
                    </p>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-check-circle text-2xl group-hover:rotate-12 transition-transform"></i>
                        <span>Simpan Mata Kuliah</span>
                    </button>
                    <a href="{{ route('admin.matakuliah') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
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
