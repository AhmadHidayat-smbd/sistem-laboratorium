@extends('layouts.asisten')

@section('title', 'Dashboard Asisten')

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.1);
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10 fade-in-up">
    
    <section class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
            <p class="text-slate-500 mt-1 font-medium">Berikut adalah ringkasan performa praktikum periode ini.</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
            <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            <span class="text-sm font-semibold text-slate-700">Sistem Aktif: {{ now()->format('d M Y') }}</span>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 hover-lift relative overflow-hidden group shadow-sm">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-100 mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Mahasiswa</p>
                <p class="text-4xl font-extrabold text-slate-900 mt-1">{{ $totalMahasiswa }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 hover-lift relative overflow-hidden group shadow-sm">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-100 mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Hadir</p>
                <p class="text-4xl font-extrabold text-slate-900 mt-1">{{ $totalHadir }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 hover-lift relative overflow-hidden group shadow-sm">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-rose-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-rose-100 mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Tidak Hadir</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 hover-lift relative overflow-hidden group shadow-sm">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-100 mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Mata Kuliah</p>
                <p class="text-4xl font-extrabold text-slate-900 mt-1">{{ $totalMatakuliah }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <div class="lg:col-span-1 space-y-5">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                Aksi Cepat
            </h3>
            
            <a href="{{ route('asisten.tambah-absensi') }}" class="group block p-1 rounded-[1.5rem] bg-gradient-to-br from-blue-600 to-blue-800 hover:shadow-xl hover:shadow-blue-200 transition-all duration-300 hover:-translate-y-1">
                <div class="bg-white/10 p-6 rounded-[1.3rem] flex items-center gap-5">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div class="text-white">
                        <h4 class="font-bold text-base">Mulai Presensi</h4>
                        <p class="text-blue-100 text-xs mt-0.5">Scan RFID Mahasiswa</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('asisten.peserta') }}" class="group block p-6 rounded-[1.5rem] bg-white border border-slate-200 hover:border-blue-300 transition-all duration-300 hover:shadow-md">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-slate-50 group-hover:bg-blue-50 rounded-xl flex items-center justify-center text-slate-500 group-hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-base">Manajemen Peserta</h4>
                        <p class="text-slate-500 text-xs mt-0.5">Kelola daftar mahasiswa</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('asisten.absensi-dosen') }}" class="group block p-6 rounded-[1.5rem] bg-white border border-slate-200 hover:border-blue-300 transition-all duration-300 hover:shadow-md">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-slate-50 group-hover:bg-blue-50 rounded-xl flex items-center justify-center text-slate-500 group-hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-base">Absensi Dosen</h4>
                        <p class="text-slate-500 text-xs mt-0.5">Kelola kehadiran dosen</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    Mata Kuliah Aktif
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($matakuliah as $mk)
                <div class="group bg-white p-5 rounded-[1.5rem] border border-slate-200 hover:border-blue-300 transition-all flex flex-col justify-between hover:shadow-md">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-10 h-10 bg-slate-100 group-hover:bg-blue-600 group-hover:text-white rounded-lg flex items-center justify-center text-slate-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1 text-lg">{{ $mk->nama }}</h4>
                        <p class="text-slate-500 text-sm mt-1">{{ $mk->mahasiswa_count }} Mahasiswa Terdaftar</p>
                    </div>

                    <a href="{{ route('asisten.absensi', ['matakuliah_id' => $mk->id]) }}" class="mt-5 w-full py-2.5 bg-slate-50 group-hover:bg-blue-600 group-hover:text-white text-slate-700 rounded-lg font-semibold text-sm text-center transition-all border border-slate-200 group-hover:border-blue-600">
                        Kelola Presensi
                    </a>
                </div>
                @empty
                <div class="col-span-2 bg-white border border-slate-200 p-12 rounded-[1.5rem] text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada mata kuliah yang terdaftar.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</main>
@endsection