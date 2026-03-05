@extends('layouts.asisten')

@section('title', 'Daftar Peserta')

@push('styles')
<style>
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 fade-in-up flex-1">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Peserta</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Kelola daftar mahasiswa pada setiap mata kuliah praktikum.</p>
        </div>
        
        <div class="w-full lg:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                <select id="filterMatakuliah" class="w-full pl-10 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl shadow-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-semibold text-slate-700 cursor-pointer">
                    <option value="all">Semua Mata Kuliah</option>
                    @foreach($matakuliah as $mk)
                        <option value="mk-{{ $mk->id }}" {{ request('matakuliah_id') == $mk->id ? 'selected' : '' }}>{{ $mk->nama }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-10">
        @foreach($matakuliah as $mk)
        <div id="mk-{{ $mk->id }}" class="mk-section bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            
            <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white border border-slate-200 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $mk->nama }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md uppercase tracking-wider">{{ $mk->kode }}</span>
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase tracking-wider">{{ ($peserta[$mk->id] ?? collect())->count() }} Peserta</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    @if(($peserta[$mk->id] ?? collect())->count() > 0)
                    <form id="delete-all-{{ $mk->id }}" method="POST" action="{{ route('asisten.peserta.delete-all', $mk->id) }}" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteAll('delete-all-{{ $mk->id }}', '{{ $mk->nama }}')" class="flex items-center gap-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 px-4 py-2.5 rounded-xl font-semibold transition-colors text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Kosongkan
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('asisten.peserta.create', $mk->id) }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Peserta
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($peserta[$mk->id] ?? [] as $mhs)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-400">{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-800">{{ $mhs->nama }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-600">{{ $mhs->nim }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form id="delete-peserta-{{ $mk->id }}-{{ $mhs->id }}" method="POST" action="{{ route('asisten.peserta.delete', ['matakuliah_id'=>$mk->id,'mahasiswa_id'=>$mhs->id]) }}" class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-peserta-{{ $mk->id }}-{{ $mhs->id }}')" 
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Hapus Peserta">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-500">Belum ada peserta terdaftar untuk mata kuliah ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</main>
@endsection

@push('scripts')
<script>
    // Logic Filter Datatable
    const filter = document.getElementById("filterMatakuliah");
    const sections = document.querySelectorAll(".mk-section");

    filter.addEventListener("change", function () {
        let selected = this.value;
        sections.forEach(sec => {
            if (selected === "all" || sec.id === selected) {
                sec.style.display = "block";
                sec.style.opacity = 0;
                setTimeout(() => {
                    sec.style.transition = "opacity 0.3s ease";
                    sec.style.opacity = 1;
                }, 10);
            } else {
                sec.style.display = "none";
            }
        });
    });

    const swalCustomClass = {
        popup: 'rounded-2xl border border-slate-200 shadow-xl',
        title: 'text-xl font-bold text-slate-900',
        htmlContainer: 'text-sm text-slate-500 font-medium',
        confirmButton: 'px-6 py-2.5 rounded-xl font-bold bg-rose-600 text-white hover:bg-rose-700 transition-colors mx-2',
        cancelButton: 'px-6 py-2.5 rounded-xl font-bold bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors mx-2'
    };

    function confirmDelete(formId) {
        Swal.fire({
            title: 'Hapus Peserta?',
            text: "Mahasiswa akan dihapus dari daftar peserta mata kuliah ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: swalCustomClass,
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }

    function confirmDeleteAll(formId, mkNama) {
        Swal.fire({
            title: 'Hapus Semua Peserta?',
            text: `Semua data mahasiswa pada mata kuliah ${mkNama} akan dihapus secara permanen!`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal',
            customClass: swalCustomClass,
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }
</script>
@endpush