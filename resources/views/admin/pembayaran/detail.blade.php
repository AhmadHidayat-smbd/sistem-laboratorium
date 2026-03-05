@extends('layouts.admin')

@section('title', 'Detail Pembayaran - ' . $mahasiswa->nama)
@section('page_title', 'Detail Pembayaran')

@section('content')
<div class="space-y-7 animate-fade-in">

    <!-- Back Button -->
    <a href="{{ route('admin.pembayaran') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-blue-600 font-bold transition-all group">
        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="uppercase tracking-widest text-[11px]">Kembali ke Monitoring</span>
    </a>

    <!-- Student Info Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-7">

            <!-- Avatar -->
            <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center flex-shrink-0 border border-blue-100">
                <i class="ph-fill ph-user-circle text-5xl"></i>
            </div>

            <!-- Info -->
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-black text-gray-800 tracking-tight mb-1">{{ $mahasiswa->nama }}</h2>
                <p class="font-mono text-sm font-semibold text-gray-400 tracking-wide mb-4">{{ $mahasiswa->nim }}</p>
                <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                        <i class="ph-fill ph-receipt"></i>
                        {{ count($history) }} Total Transaksi
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                        <i class="ph-fill ph-check-circle"></i>
                        {{ collect($history)->where('status', 'Lunas')->count() }} Lunas
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-8 py-6 border-b border-gray-50">
            <h3 class="text-base font-black text-gray-800 flex items-center gap-2.5">
                <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                Riwayat Pembayaran
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/60 border-b border-gray-100">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">No</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">Nama</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">NIM</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">Tahun Semester</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">Tanggal</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[2px] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($history as $index => $tx)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-gray-400">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-gray-800">{{ $mahasiswa->nama }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-mono text-sm font-semibold text-gray-400 tracking-wide">{{ $mahasiswa->nim }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-indigo-500">{{ $tx['tahun_semester'] }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-semibold text-gray-500">{{ $tx['tanggal_format'] }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if(($tx['status'] ?? '') === 'Lunas')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-500 border border-rose-100 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full"></span>
                                    {{ $tx['status'] ?? 'Pending' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-300">
                                <i class="ph-bold ph-receipt text-5xl mb-3"></i>
                                <p class="text-sm font-black uppercase tracking-widest">Tidak ada data pembayaran</p>
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

@push('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s ease-out forwards; }
</style>
@endpush