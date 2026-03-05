@extends('layouts.admin')

@section('title', 'Absensi Dosen')
@section('page_title', 'Absensi Dosen')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div>
                <p class="text-gray-500 font-medium text-sm">Rekap kehadiran dosen mengajar per mata kuliah</p>
            </div>
            
            <!-- Tahun Ajaran Filter -->
            <form action="{{ route('admin.absensi-dosen') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <select name="tahun_ajaran" 
                            onchange="this.form.submit()"
                            class="pl-10 pr-8 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 appearance-none focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>
                                {{ $ta }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ph-fill ph-calendar-blank absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400"></i>
                </div>
            </form>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="handleExport('{{ route('admin.absensi-dosen.export') }}')"
               class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-emerald-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-file-xls text-xl"></i>
                <span>Export Excel</span>
            </button>
            
            <a href="{{ route('admin.absensi-dosen.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98] text-sm">
                <i class="ph-fill ph-plus-circle text-xl"></i>
                <span>Tambah Absensi</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1">Mata Kuliah</p>
                    <h3 class="text-3xl font-black">{{ $matakuliah->count() }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="ph-fill ph-books text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider mb-1">Hadir (Offline)</p>
                    <h3 class="text-3xl font-black">{{ $absensiAll->where('status', 'Hadir')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-xs font-bold uppercase tracking-wider mb-1">Online</p>
                    <h3 class="text-3xl font-black">{{ $absensiAll->where('status', 'Online')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="ph-fill ph-globe text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-xs font-bold uppercase tracking-wider mb-1">Asisten</p>
                    <h3 class="text-3xl font-black">{{ $absensiAll->where('status', 'Digantikan Asisten')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="ph-fill ph-users text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grouped by Matakuliah -->
    @forelse($matakuliah as $mk)
        @php
            $items = $absensiGrouped->get($mk->id, collect());
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Course Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-gray-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-black shadow-md flex-shrink-0">
                        <i class="ph-fill ph-book-open text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900">{{ $mk->kode }} — {{ $mk->nama }}</h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <i class="ph-fill ph-chalkboard-teacher text-sm text-gray-400"></i>
                            <span class="text-sm font-semibold text-gray-500">{{ $mk->dosen->nama ?? 'Belum ada dosen' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase rounded-lg border border-emerald-100">
                        <i class="ph-fill ph-check-circle"></i>
                        {{ $items->where('status', 'Hadir')->count() }} Hadir
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase rounded-lg border border-blue-100">
                        <i class="ph-fill ph-globe"></i>
                        {{ $items->where('status', 'Online')->count() }} Online
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 text-[10px] font-black uppercase rounded-lg border border-amber-100">
                        <i class="ph-fill ph-users"></i>
                        {{ $items->where('status', 'Digantikan Asisten')->count() }} Asisten
                    </span>
                </div>
            </div>

            <!-- Attendance Table -->
            @if($items->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Dosen</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-28">Pertemuan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-32">Tanggal</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Materi</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-24">Status</th>
                                <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($items->sortBy('pertemuan') as $index => $item)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-3">
                                    <span class="text-sm font-bold text-gray-500">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $item->dosen->nama ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $item->dosen->email ?? '' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-bold rounded-lg border border-violet-100">
                                        <i class="ph-fill ph-hash text-[10px]"></i>
                                        P{{ $item->pertemuan }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-sm font-semibold text-gray-700">{{ $item->tanggal->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-sm text-gray-600">{{ $item->materi ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    @if($item->status == 'Hadir')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-100">
                                            <i class="ph-fill ph-check-circle text-sm"></i>
                                            Hadir
                                        </span>
                                    @elseif($item->status == 'Online')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                                            <i class="ph-fill ph-globe text-sm"></i>
                                            Online
                                        </span>
                                    @elseif($item->status == 'Digantikan Asisten')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-100">
                                            <i class="ph-fill ph-users text-sm"></i>
                                            Asisten
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-lg border border-rose-100">
                                            <i class="ph-fill ph-x-circle text-sm"></i>
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center justify-center">
                                        <form action="{{ route('admin.absensi-dosen.delete', $item->id) }}" method="POST" id="delete-absensi-{{ $item->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete('delete-absensi-{{ $item->id }}')"
                                                    class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-all duration-200 border border-rose-100">
                                                <i class="ph-fill ph-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <i class="ph-fill ph-clipboard-text text-3xl text-gray-300 mb-2"></i>
                    <p class="text-gray-400 text-sm font-semibold">Belum ada data absensi untuk mata kuliah ini.</p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
            <i class="ph-fill ph-clipboard-text text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Data</h3>
            <p class="text-gray-500 mb-6">Belum ada mata kuliah atau data absensi dosen.</p>
            <a href="{{ route('admin.absensi-dosen.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-800 transition-all">
                <i class="ph-fill ph-plus-circle text-xl"></i>
                Tambah Absensi Pertama
            </a>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
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
