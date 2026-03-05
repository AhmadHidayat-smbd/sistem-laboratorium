@extends('layouts.asisten')

@section('title', 'Histori Absensi Asisten')

@section('content')
<main class="max-w-7xl mx-auto px-4 py-10 space-y-10 fade-in-up flex-1 w-full">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight uppercase">Histori Absensi Asisten</h2>
            <p class="text-gray-500 font-medium text-sm mt-1">Daftar rekapitulasi kehadiran asisten per mata kuliah</p>
        </div>
        
        <div class="flex items-center gap-4">
             <a href="{{ route('asisten.absensi-asisten.export') }}" 
                class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-900 text-white rounded-xl font-semibold shadow-sm transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="ph-fill ph-file-xls text-white-600 text-xl"></i>
                Export Semua
             </a>
             <a href="{{ route('asisten.absensi-asisten.create') }}" wire:navigate
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3.5 rounded-2xl shadow-lg shadow-blue-600/20 flex items-center gap-2 transition-all transform hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-wider">
                <i class="ph-fill ph-plus-circle text-xl"></i>
                Tambah Absensi
             </a>
        </div>
    </div>

    <div class="space-y-8">
        @forelse($matakuliah as $mk)
            @php
                $items = $historyGrouped->get($mk->id, collect());
            @endphp
            
            @if($items->isNotEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden fade-in-up">
                <div class="px-8 py-5 border-b border-slate-50 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="ph-fill ph-bookmarks text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800 tracking-tight uppercase">{{ $mk->kode }} — {{ $mk->nama }}</h3>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                    <i class="ph-fill ph-users"></i>
                                    Rekapitulasi Kehadiran
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="handleExport('{{ route('asisten.absensi-asisten.export', ['matakuliah_id' => $mk->id]) }}')"
                           class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 tooltip" title="Export Excel Matkul Ini">
                            <i class="ph-bold ph-file-xls text-xl"></i>
                        </button>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-700 text-[10px] font-black uppercase rounded-xl border border-blue-100">
                            <i class="ph-fill ph-check-circle text-xs"></i>
                            Total: {{ $items->count() }} Kehadiran
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-20">No</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asisten</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pertemuan</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Presensi</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($items->sortBy('pertemuan') as $index => $item)
                            <tr class="hover:bg-blue-50/20 transition-all group">
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-400">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                            {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">{{ $item->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                                        Pertemuan {{ $item->pertemuan }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ \Carbon\Carbon::parse($item->jam_hadir)->format('H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center">
                                        <form id="delete-asisten-{{ $item->id }}" action="{{ route('asisten.absensi-asisten.delete', $item->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-asisten-{{ $item->id }}')" class="w-9 h-9 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @empty
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-20 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-fill ph-clipboard-text text-4xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Belum Ada Riwayat Kehadiran</h3>
                <p class="text-slate-500 mt-2 font-medium">Anda belum melakukan presensi untuk mata kuliah manapun.</p>
                <a href="{{ route('asisten.absensi-asisten.create') }}" wire:navigate class="mt-8 inline-flex items-center gap-2 px-8 py-3.5 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all uppercase tracking-wider text-sm">
                    <i class="ph-fill ph-plus-circle text-xl"></i>
                    Tambah Absensi Pertama
                </a>
            </div>
        @endforelse
    </div>
</main>
@endsection

@push('scripts')
<script>
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Hapus riwayat absen?',
            text: "Data kehadiran akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
    
    function handleExport(url) {
        Swal.fire({
            title: 'Mempersiapkan File...',
            html: `
                <div class="flex flex-col items-center gap-4 py-4">
                    <div class="w-20 h-20 border-4 border-blue-100 border-t-emerald-500 rounded-full animate-spin"></div>
                    <p class="text-gray-500 font-medium animate-pulse">Sedang mengolah data Excel Anda</p>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: () => {
                window.location.href = url;
            }
        }).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Selesai!',
                text: 'File Excel sedang diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
</script>
@endpush
