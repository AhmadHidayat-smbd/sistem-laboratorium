@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')
@section('page_title', 'Kelola Pembayaran Mahasiswa')

@section('content')
<div class="animate-fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="flex-1">
            <div class="mb-4">
                <h3 class="text-lg font-black text-gray-800 tracking-tight">Ringkasan Pembayaran</h3>
                <p class="text-sm font-medium text-gray-400">Daftar mahasiswa yang telah melakukan pembayaran</p>
            </div>
            <!-- Filters -->
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Bar -->
                <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="relative flex-1 group">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau NIM..." 
                           class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-sm text-gray-700 shadow-sm group-hover:border-blue-300">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                        <i class="ph-bold ph-magnifying-glass text-xl"></i>
                    </div>
                    @if(request('search'))
                    <a href="{{ route('admin.pembayaran.index', ['angkatan' => request('angkatan')]) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500">
                        <i class="ph-bold ph-x-circle text-xl"></i>
                    </a>
                    @endif
                </form>

                <!-- Angkatan Filter -->
                <div class="relative min-w-[160px]">
                    <select onchange="window.location.href=this.value" 
                            class="w-full pl-12 pr-10 py-3 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-sm text-gray-700 shadow-sm appearance-none cursor-pointer">
                        <option value="{{ route('admin.pembayaran.index', ['search' => request('search')]) }}">Semua Angkatan</option>
                        @foreach($daftarAngkatan as $a)
                            <option value="{{ route('admin.pembayaran.index', ['angkatan' => $a, 'search' => request('search')]) }}" 
                                {{ request('angkatan') == $a ? 'selected' : '' }}>
                                Angkatan 20{{ $a }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="ph-bold ph-funnel text-xl"></i>
                    </div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="ph-bold ph-caret-down"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 h-fit">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
               class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 transition-all duration-200 shadow-lg shadow-emerald-100 group h-fit">
                <i class="ph-bold ph-file-arrow-up text-lg"></i>
                <span>Import Excel</span>
            </button>
            <a href="{{ route('admin.pembayaran.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all duration-200 shadow-lg shadow-blue-100 group h-fit">
                <i class="ph-bold ph-plus text-lg group-hover:rotate-90 transition-transform"></i>
                <span>Tambah Pembayaran</span>
            </a>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-[2rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.pembayaran.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-emerald-100 text-emerald-600 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ph-bold ph-file-xls text-2xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-xl leading-6 font-black text-gray-900" id="modal-title">Import Data Pembayaran</h3>
                                <div class="mt-2 text-sm text-gray-500 font-medium">
                                    <p>Unggah file Excel dengan header: <b>nim, nama, 22_1, 22_2, dst.</b> Nominal diisi di bawah kolom tahun semester.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-wider mb-2">Pilih File Excel (.xlsx, .xls)</label>
                            <input type="file" name="file" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-gray-100 p-2 rounded-2xl bg-gray-50">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-8 py-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-blue-600 text-base font-black text-white hover:bg-blue-700 focus:outline-none sm:w-auto sm:text-sm transition-all">Submit Import</button>
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-black text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="ph-fill ph-users-three text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Total Mahasiswa</p>
                <p class="text-2xl font-black text-gray-800">{{ $pembayarans->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                <p class="text-2xl font-black text-gray-800">{{ \App\Models\Pembayaran::count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="ph-fill ph-calendar-check text-2xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-wider">Update Terakhir</p>
                @php
                    $lastUpdate = \App\Models\Pembayaran::latest('updated_at')->first();
                @endphp
                <p class="text-xl font-black text-gray-800">{{ $lastUpdate ? $lastUpdate->updated_at->format('d/m/Y') : '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] w-16">No</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Mahasiswa</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">NIM</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Frek. Bayar</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Total Pembayaran</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pembayarans as $key => $p)
                    <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                        <td class="px-8 py-6">
                            <span class="font-bold text-gray-500">{{ $key + 1 }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-gray-700">{{ $p->nama }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-mono text-sm font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ $p->nim }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-black rounded-full border border-blue-100">
                                <i class="ph-bold ph-receipt"></i>
                                {{ $p->total_kali_bayar }} Kali
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-black text-gray-800">Rp {{ number_format($p->total_nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.pembayaran.show', $p->nim) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 font-bold text-xs rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-200 group/btn">
                                <span>Detail</span>
                                <i class="ph-bold ph-caret-right group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center">
                                    <i class="ph-fill ph-wallet text-4xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 font-bold italic">Belum ada data pembayaran terdaftar.</p>
                                    <p class="text-gray-300 text-sm">Klik tombol "Tambah Pembayaran Baru" untuk memulai.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- No Pagination -->
    </div>
</div>
@endsection
