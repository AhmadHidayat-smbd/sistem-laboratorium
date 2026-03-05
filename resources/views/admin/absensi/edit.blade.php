@extends('layouts.admin')

@section('title', 'Edit Presensi')
@section('page_title', 'Edit Presensi')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.absensi') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Data Presensi</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-pencil-line text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Koreksi Data Kehadiran</h2>
                    <p class="text-sm font-medium text-gray-400">Modifikasi status kehadiran mahasiswa secara manual.</p>
                </div>
            </div>

            <!-- Profile Summary Card -->
            <div class="bg-gray-50 rounded-[2rem] p-8 mb-10 border border-gray-100 grid md:grid-cols-2 gap-8 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-[0.03] translate-x-1/4 -translate-y-1/4">
                    <i class="ph-fill ph-user-circle text-[150px]"></i>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="ph-bold ph-user text-xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Mahasiswa</span>
                            <p class="font-black text-gray-800 text-lg leading-none">{{ $mahasiswa->nama }}</p>
                            <p class="text-xs font-bold text-gray-400 font-mono mt-1">{{ $mahasiswa->nim }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                            <i class="ph-bold ph-book-open text-xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Mata Kuliah</span>
                            <p class="font-black text-gray-800 text-lg leading-none">{{ $matakuliah->nama }}</p>
                            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-tighter">{{ $matakuliah->kode }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('error'))
            <div class="bg-rose-50 border-2 border-rose-100 p-6 rounded-2xl mb-8 animate-shake">
                <div class="flex gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-2xl"></i>
                    <p class="font-bold text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($pertemuanAda->isEmpty())
                <div class="py-12 text-center bg-amber-50 rounded-[2rem] border border-amber-100">
                    <i class="ph-bold ph-database-slash text-5xl text-amber-300 mb-4 block"></i>
                    <p class="text-amber-800 font-black text-lg mb-2 uppercase tracking-tight">Tidak ada data presensi</p>
                    <p class="text-amber-600/70 text-sm font-medium mb-8">Mahasiswa ini belum tercatat dalam sesi pertemuan manapun.</p>
                    <a href="{{ route('admin.absensi') }}" class="px-8 py-4 bg-amber-500 text-white rounded-2xl font-black hover:bg-amber-600 transition-all shadow-lg shadow-amber-100">
                        Kembali ke Daftar
                    </a>
                </div>
            @else
                <form method="POST" action="{{ route('admin.absensi.update.pertemuan') }}" class="space-y-10">
                    @csrf
                    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                    <input type="hidden" name="matakuliah_id" value="{{ $matakuliah->id }}">

                    <!-- Pertemuan -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Pilih Sesi Pertemuan</label>
                        <div class="relative group">
                            <select name="pertemuan" required
                                    class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700">
                                <option value="">-- Pertemuan Tersedia --</option>
                                @foreach($pertemuanAda as $p)
                                    <option value="{{ $p }}">Pertemuan Ke-{{ $p }} @if($p == 9) (Responsi) @endif</option>
                                @endforeach
                            </select>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-calendar-check text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Status Kehadiran Baru</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative group cursor-pointer">
                                <input type="radio" name="status" value="Hadir" class="peer sr-only" required>
                                <div class="p-6 bg-white border-2 border-gray-100 rounded-[2rem] flex items-center justify-between peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all duration-300 group-hover:border-emerald-200">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                                            <i class="ph-bold ph-user-check text-xl"></i>
                                        </div>
                                        <span class="font-black text-gray-700 peer-checked:text-emerald-900">HADIR</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center transition-all">
                                        <i class="ph-bold ph-check text-white text-[10px] opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </div>
                            </label>

                            <label class="relative group cursor-pointer">
                                <input type="radio" name="status" value="Tidak Hadir" class="peer sr-only" required>
                                <div class="p-6 bg-white border-2 border-gray-100 rounded-[2rem] flex items-center justify-between peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-all duration-300 group-hover:border-rose-200">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center">
                                            <i class="ph-bold ph-user-minus text-xl"></i>
                                        </div>
                                        <span class="font-black text-gray-700 peer-checked:text-rose-900">Tipsen</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-200 peer-checked:border-rose-500 peer-checked:bg-rose-500 flex items-center justify-center transition-all">
                                        <i class="ph-bold ph-x text-white text-[10px] opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                            <i class="ph-bold ph-floppy-disk text-2xl group-hover:rotate-6 transition-transform"></i>
                            <span>Simpan Perubahan Presensi</span>
                        </button>
                        <a href="{{ route('admin.absensi') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            @endif
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