@extends('layouts.admin')

@section('title', 'Mata Kuliah')
@section('page_title', 'Mata Kuliah')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Mata Kuliah</h2>
        <p class="text-gray-500 font-medium">Manajemen kurikulum dan data mata kuliah praktikum iTlabs.</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.matakuliah.create') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i>
            <span>Tambah Mata Kuliah</span>
        </a>
    </div>
</div>

<!-- Filter Tahun Ajaran -->
<div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-10 animate-fade-in" style="animation-delay: 0.05s">
    <div class="relative group w-full sm:w-72">
        <select id="filterTahunAjaran" onchange="filterByTahunAjaran(this.value)" class="w-full pl-12 pr-6 py-4 bg-white border border-gray-100 rounded-[1.5rem] shadow-sm appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-700">
            <option value="all" {{ $tahunAjaran == '' ? 'selected' : '' }}>📚 Semua Semester</option>
            @foreach($tahunAjaranList as $ta)
                @php
                    $parts = explode('-', $ta);
                    $semLabel = ($parts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
                @endphp
                <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>
                    {{ $ta }} — Semester {{ $semLabel }}
                </option>
            @endforeach
        </select>
        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
            <i class="ph-bold ph-calendar text-xl"></i>
        </div>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
            <i class="ph-bold ph-caret-down text-lg"></i>
        </div>
    </div>

    @if($tahunAjaran)
        @php
            $taParts = explode('-', $tahunAjaran);
            $taLabel = ($taParts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
        @endphp
        <div class="flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
                <i class="ph-fill ph-calendar"></i>
                {{ $tahunAjaran }} • Semester {{ $taLabel }}
            </span>
            <span class="text-gray-400 font-semibold">— {{ $matakuliah->count() }} mata kuliah</span>
        </div>
    @endif
</div>

<!-- Table Card -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in" style="animation-delay: 0.1s">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">No</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Kode MK</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Nama Mata Kuliah</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Tahun Ajaran</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Dosen Pengampu</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Status</th>
                    <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($matakuliah as $mk)
                <tr class="hover:bg-blue-50/30 transition-colors group {{ !$mk->is_active ? 'opacity-50' : '' }}">
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-gray-400">#{{ $loop->iteration }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-sm font-black tracking-tight">
                            {{ $mk->kode }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-gray-800 font-black tracking-tight">
                        {{ $mk->nama }}
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $parts = explode('-', $mk->tahun_ajaran ?? '');
                            $semLabel = ($parts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
                            <i class="ph-fill ph-calendar text-sm"></i>
                            {{ $mk->tahun_ajaran }} <span class="text-violet-400">•</span> {{ $semLabel }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        @if($mk->dosen)
                            <span class="text-sm font-semibold text-gray-700">{{ $mk->dosen->nama }}</span>
                        @else
                            <span class="text-sm text-gray-400 italic">Belum ada dosen</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        <form method="POST" action="{{ route('admin.matakuliah.toggle-active', $mk->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300 hover:scale-105 active:scale-95 shadow-sm
                                {{ $mk->is_active 
                                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600' 
                                    : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-600 hover:text-white hover:border-gray-600' 
                                }}" title="{{ $mk->is_active ? 'Klik untuk sembunyikan' : 'Klik untuk aktifkan' }}">
                                @if($mk->is_active)
                                    <i class="ph-fill ph-eye text-sm"></i>
                                    Aktif
                                @else
                                    <i class="ph-fill ph-eye-slash text-sm"></i>
                                    Hidden
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.matakuliah.edit', $mk->id) }}" 
                               class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300 shadow-sm"
                               title="Edit Data">
                                <i class="ph-bold ph-note-pencil text-lg"></i>
                            </a>
                            
                            <form id="delete-form-{{ $mk->id }}" method="POST" action="{{ route('admin.matakuliah.delete', $mk->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-form-{{ $mk->id }}')" 
                                        class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm"
                                        title="Hapus Data">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center justify-center opacity-20">
                            <i class="ph-bold ph-book-open text-6xl mb-4"></i>
                            <p class="text-xl font-black uppercase tracking-widest">Belum ada mata kuliah</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function filterByTahunAjaran(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('tahun_ajaran', value);
        window.location.href = url.toString();
    }
</script>
@endpush
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
