@extends('layouts.admin')

@section('title', 'Edit Peserta')
@section('page_title', 'Edit Peserta')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.peserta') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Kelola Peserta</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <i class="ph-fill ph-user-circle-gear text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Koreksi Data Peserta</h2>
                        <p class="text-sm font-medium text-gray-400">Ganti mahasiswa yang terdaftar pada sesi ini.</p>
                    </div>
                </div>
                <div class="bg-indigo-50 px-6 py-3 rounded-2xl">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest block mb-1">Mata Kuliah</span>
                    <span class="text-indigo-600 font-bold font-mono">{{ $matakuliah->kode }} - {{ $matakuliah->nama }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.peserta.update', ['matakuliah_id'=>$matakuliah->id,'mahasiswa_id'=>$mahasiswa->id]) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Pilih Mahasiswa Pengganti</label>
                    <div class="relative group">
                        <select name="mahasiswa_id" required
                                class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700">
                            @foreach($allMahasiswa as $m)
                                <option value="{{ $m->id }}" {{ $mahasiswa->id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }} ({{ $m->nim }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="ph-bold ph-student text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-amber-100 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-floppy-disk text-2xl group-hover:rotate-6 transition-transform"></i>
                        <span>Simpan Perubahan Peserta</span>
                    </button>
                    <a href="{{ route('admin.peserta') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
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
</style>
@endpush
