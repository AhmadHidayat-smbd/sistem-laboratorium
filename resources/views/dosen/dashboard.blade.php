@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang, ' . Auth::guard('dosen')->user()->nama)

@section('content')
<div class="p-6 lg:p-8 space-y-8">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-black mb-2">Selamat Datang Kembali! 👋</h1>
                    <p class="text-blue-100 text-lg font-medium mb-4">{{ Auth::guard('dosen')->user()->nama }}</p>
                    <p class="text-blue-50">Kelola mata kuliah dan pantau progress mahasiswa Anda di sini</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="ph-fill ph-chalkboard-teacher text-7xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Mata Kuliah -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="ph-fill ph-books text-3xl text-white"></i>
                </div>
                <span class="text-4xl font-black text-gray-900">{{ $matakuliah->count() }}</span>
            </div>
            <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide">Mata Kuliah</h3>
            <p class="text-xs text-gray-500 mt-1">Yang Anda ampu</p>
        </div>

        <!-- Total Mahasiswa -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="ph-fill ph-users-three text-3xl text-white"></i>
                </div>
                <span class="text-4xl font-black text-gray-900">{{ $totalMahasiswa }}</span>
            </div>
            <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide">Total Mahasiswa</h3>
            <p class="text-xs text-gray-500 mt-1">Di semua kelas</p>
        </div>

        <!-- Kehadiran Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="ph-fill ph-calendar-check text-3xl text-white"></i>
                </div>
                <span class="text-4xl font-black text-gray-900">{{ $absensiHariIni }}</span>
            </div>
            <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide">Absensi Hari Ini</h3>
            <p class="text-xs text-gray-500 mt-1">Mahasiswa hadir</p>
        </div>
    </div>

    <!-- Mata Kuliah yang Diampu -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="ph-fill ph-book-bookmark text-2xl text-blue-600"></i>
                Mata Kuliah yang Anda Ampu
            </h2>
        </div>

        <div class="p-6">
            @if($matakuliah->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($matakuliah as $mk)
                        <div class="border-2 border-gray-100 rounded-xl p-5 hover:border-blue-300 hover:shadow-md transition-all duration-200 group">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg mb-2">
                                        {{ $mk->kode }}
                                    </span>
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $mk->nama }}
                                    </h3>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-fill ph-book-open text-2xl text-white"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-gray-100">
                                <div class="text-center">
                                    <p class="text-2xl font-black text-gray-900">
                                        {{ $mk->mahasiswa->count() }}
                                    </p>
                                    <p class="text-xs text-gray-600 font-semibold">Mahasiswa</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-black text-gray-900">
                                        {{ $mk->jadwal->count() }}
                                    </p>
                                    <p class="text-xs text-gray-600 font-semibold">Jadwal</p>
                                </div>
                            </div>

                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('dosen.absensi', ['matakuliah' => $mk->id]) }}" 
                                   class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors text-center">
                                    <i class="ph-fill ph-clipboard-text"></i> Absensi
                                </a>
                                <a href="{{ route('dosen.nilai-responsi', ['matakuliah' => $mk->id]) }}" 
                                   class="flex-1 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors text-center">
                                    <i class="ph-fill ph-exam"></i> Nilai
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <i class="ph-fill ph-books text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-medium">Anda belum mengampu mata kuliah apapun</p>
                    <p class="text-sm text-gray-400 mt-1">Hubungi admin untuk penugasan mata kuliah</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Info Card 1 -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-6 rounded-xl">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-info text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900 mb-2">Fitur Portal Dosen</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Lihat daftar absensi mahasiswa per mata kuliah</li>
                        <li>• Input dan kelola nilai responsi</li>
                        <li>• Pantau progress mahasiswa secara real-time</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Info Card 2 -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500 p-6 rounded-xl">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-lightbulb text-2xl text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-green-900 mb-2">Tips Penggunaan</h3>
                    <ul class="text-sm text-green-800 space-y-1">
                        <li>• Klik kartu mata kuliah untuk akses cepat</li>
                        <li>• Gunakan menu sidebar untuk navigasi</li>
                        <li>• Data diperbarui secara otomatis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
