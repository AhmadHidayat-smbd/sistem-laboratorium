@extends('layouts.asisten')

@section('title', 'Tambah Absensi Asisten')

@section('content')
<main class="max-w-3xl mx-auto px-4 py-12 space-y-10 fade-in-up flex-1 w-full">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Tambah Presensi Asisten</h2>
        <p class="text-gray-500 font-medium text-sm mt-2">Pilih mata kuliah dan pertemuan untuk mencatat kehadiran Anda</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 border border-slate-100 p-8 md:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full blur-3xl -mr-32 -mt-32"></div>
        
        <form action="{{ route('asisten.absensi-asisten.store') }}" method="POST" class="relative space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Matakuliah Praktikum</label>
                    <div class="relative">
                        <select name="matakuliah_id" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-700 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer" required>
                            <option value="">— Pilih Mata Kuliah —</option>
                            @foreach($matakuliah as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode }} — {{ $mk->nama }}</option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Pertemuan Ke-</label>
                    <div class="relative">
                        <select name="pertemuan" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-700 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer" required>
                            @for($i=1; $i<=8; $i++)
                                <option value="{{ $i }}">Pertemuan {{ $i }}</option>
                            @endfor
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex flex-col md:flex-row gap-4">
                <a href="{{ route('asisten.absensi-asisten') }}" wire:navigate class="flex-1 text-center py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-200">
                    Batal
                </a>
                <button type="submit" class="flex-[2] bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-600/20 flex items-center justify-center gap-3 transition-all transform hover:scale-[1.02] active:scale-95 text-base uppercase tracking-wider">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                    Konfirmasi Kehadiran
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
