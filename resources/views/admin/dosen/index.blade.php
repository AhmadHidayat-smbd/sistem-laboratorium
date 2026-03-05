@extends('layouts.admin')

@section('title', 'Kelola Dosen')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Kelola Dosen</h2>
        <p class="text-gray-500 font-medium">Manajemen data dosen pengampu mata kuliah praktikum iTlabs.</p>
    </div>
    
    <div class="flex flex-col md:flex-row items-center gap-4">
        <!-- Search Form -->
        <form action="{{ route('admin.dosen') }}" method="GET" class="relative group w-full md:w-80">
            <input name="search" 
                   type="text" 
                   value="{{ $search ?? '' }}"
                   class="w-full pl-12 pr-6 py-4 bg-white border border-gray-100 rounded-[1.5rem] shadow-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-700"
                   placeholder="Cari Nama atau Email...">
            <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
                <i class="ph-bold ph-magnifying-glass text-xl"></i>
            </button>
        </form>

        <a href="{{ route('admin.dosen.create') }}" class="w-full md:w-auto flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i>
            <span>Tambah Dosen</span>
        </a>
    </div>
</div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Dosen</p>
                    <h3 class="text-3xl font-bold">{{ $dosen->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="ph-fill ph-chalkboard-teacher text-4xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Dosen dengan RFID</p>
                    <h3 class="text-3xl font-bold">{{ $dosen->whereNotNull('rfid_uid')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="ph-fill ph-identification-card text-4xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Dosen Aktif Mengajar</p>
                    <h3 class="text-3xl font-bold">{{ $dosen->filter(fn($d) => $d->matakuliah->count() > 0)->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="ph-fill ph-user-check text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Dosen</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">RFID UID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dosen as $index => $item)
                    <tr class="hover:bg-blue-50/50 transition-all duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $item->nama }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="ph-fill ph-envelope text-gray-400"></i>
                                <span class="text-sm text-gray-700">{{ $item->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->rfid_uid)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-semibold rounded-lg">
                                    <i class="ph-fill ph-check-circle"></i>
                                    {{ $item->rfid_uid }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-500 text-xs font-medium rounded-lg">
                                    <i class="ph-fill ph-minus-circle"></i>
                                    Belum ada
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg">
                                <i class="ph-fill ph-books"></i>
                                {{ $item->matakuliah->count() }} Matkul
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.dosen.edit', $item->id) }}" 
                                   class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300 shadow-sm"
                                   title="Edit Data">
                                    <i class="ph-bold ph-note-pencil text-lg"></i>
                                </a>
                                
                                <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('admin.dosen.delete', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-form-{{ $item->id }}')" 
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
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="ph-fill ph-users text-6xl text-gray-300"></i>
                                <p class="text-gray-500 font-medium">
                                    @if($search)
                                        Tidak ada dosen yang ditemukan dengan kata kunci "{{ $search }}"
                                    @else
                                        Belum ada data dosen. Silakan tambahkan dosen baru.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
