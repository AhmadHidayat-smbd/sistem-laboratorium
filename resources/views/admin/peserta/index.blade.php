@extends('layouts.admin')

@section('title', 'Peserta Matkul')
@section('page_title', 'Peserta Matakuliah')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Data Peserta Matakuliah</h2>
        <p class="text-gray-500 font-medium">Kelola data peserta matakuliah dan pantau peserta setiap matakuliah</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-stretch gap-4 w-full lg:w-auto">
        <!-- Tahun Ajaran Filter -->
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
        
        <!-- Filter Matakuliah (within selected semester) -->
        <div class="relative group w-full sm:w-64">
            <select id="filterMatakuliah" class="w-full pl-12 pr-6 py-4 bg-white border border-gray-100 rounded-[1.5rem] shadow-sm appearance-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-500 outline-none transition-all duration-300 font-bold text-gray-700">
                <option value="all">📋 Semua Matakuliah</option>
                @foreach($matakuliah as $mk)
                    <option value="mk-{{ $mk->id }}">{{ $mk->nama }}</option>
                @endforeach
            </select>
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-violet-600">
                <i class="ph-bold ph-funnel text-xl"></i>
            </div>
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <i class="ph-bold ph-caret-down text-lg"></i>
            </div>
        </div>
    </div>
</div>

@if($tahunAjaran)
    @php
        $taParts = explode('-', $tahunAjaran);
        $taLabel = ($taParts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
    @endphp
    <div class="mb-6 flex items-center gap-2 text-sm animate-fade-in">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
            <i class="ph-fill ph-calendar"></i>
            {{ $tahunAjaran }} • Semester {{ $taLabel }}
        </span>
        <span class="text-gray-400 font-semibold">— {{ $matakuliah->count() }} mata kuliah</span>
    </div>
@endif

<!-- Sections -->
<div class="space-y-12 mb-10">
    @foreach($matakuliah as $mk)
    <div id="mk-{{ $mk->id }}" class="mk-section animate-fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm group-hover:rotate-6 transition-transform">
                <i class="ph-fill ph-bookmarks text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $mk->nama }}</h2>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-widest">{{ $mk->kode }}</span>
                    @if($mk->tahun_ajaran)
                    <span class="text-[10px] font-bold text-violet-500 bg-violet-50 px-2 py-0.5 rounded-full tracking-wide">{{ $mk->tahun_ajaran }}</span>
                    @endif
                </div>
            </div>
            <div class="h-1 flex-1 bg-gray-100 rounded-full ml-4"></div>
            
            <div class="flex items-center gap-3">
                @if($mk->mahasiswa->count() > 0)
                <form id="delete-all-{{ $mk->id }}" method="POST" action="{{ route('admin.peserta.delete-all', $mk->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDeleteAll('delete-all-{{ $mk->id }}', '{{ $mk->nama }}')" class="flex items-center gap-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white px-6 py-3 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-sm">
                        <i class="ph-bold ph-trash text-lg"></i>
                        <span class="text-sm">Hapus Semua</span>
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.peserta.create', $mk->id) }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105">  
                <i class="ph-bold ph-plus-circle text-xl"></i>
                    <span class="text-sm">Tambah Peserta</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">No</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Nama Mahasiswa</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">NIM</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($mk->mahasiswa as $mhs)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-400">#{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-black text-gray-800 tracking-tight">{{ $mhs->nama }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-black tracking-tight">
                                    {{ $mhs->nim }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.peserta.edit', ['matakuliah_id'=>$mk->id,'mahasiswa_id'=>$mhs->id]) }}" 
                                       class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300 shadow-sm"
                                       title="Edit Hubungan">
                                        <i class="ph-bold ph-arrows-left-right text-lg"></i>
                                    </a>
                                    
                                    <form id="delete-peserta-{{ $mk->id }}-{{ $mhs->id }}" method="POST" action="{{ route('admin.peserta.delete', ['matakuliah_id'=>$mk->id,'mahasiswa_id'=>$mhs->id]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-peserta-{{ $mk->id }}-{{ $mhs->id }}')" 
                                                class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm"
                                                title="Hapus Peserta">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Belum ada peserta terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    @if($matakuliah->count() === 0)
    <div class="text-center py-20 opacity-30 animate-fade-in">
        <i class="ph-bold ph-users-three text-6xl mb-4 block"></i>
        <p class="text-xl font-black uppercase tracking-widest">Belum ada mata kuliah di semester ini</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Tahun Ajaran filter — navigates with query param
    function filterByTahunAjaran(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('tahun_ajaran', value);
        window.location.href = url.toString();
    }

    // Matakuliah filter — client-side toggle within the semester
    const filter = document.getElementById("filterMatakuliah");
    const sections = document.querySelectorAll(".mk-section");

    filter.addEventListener("change", function () {
        let selected = this.value;
        sections.forEach(sec => {
            if (selected === "all" || sec.id === selected) {
                sec.style.display = "block";
                sec.classList.add('animate-fade-in');
            } else {
                sec.style.display = "none";
            }
        });
    });

    window.confirmDeleteAll = function(formId, mkNama) {
        Swal.fire({
            title: 'Hapus Semua Peserta?',
            text: `Semua data mahasiswa pada mata kuliah ${mkNama} akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem] border-0',
                confirmButton: 'px-8 py-4 rounded-xl font-bold bg-rose-600 text-white hover:bg-rose-700 transition-all mx-2',
                cancelButton: 'px-8 py-4 rounded-xl font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all mx-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }
</script>
@endpush

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
