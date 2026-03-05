@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('page_title', 'Riwayat Pembayaran Mahasiswa')

@section('content')
<div class="animate-fade-in space-y-8">
    <!-- Back Button -->
    <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Daftar Pembayaran</span>
    </a>

    <!-- Profile Card (Top Section) -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 flex items-center gap-6">
        <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center">
            <div class="w-12 h-12 rounded-full border-4 border-blue-500 flex items-center justify-center">
                <i class="ph-fill ph-user text-blue-500 text-2xl"></i>
            </div>
        </div>
        <div class="space-y-3">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $nama }}</h2>
                <p class="text-sm font-bold text-gray-400">{{ $nim }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-50 text-blue-600 text-[11px] font-black uppercase tracking-wider rounded-xl">
                    <i class="ph-bold ph-receipt"></i>
                    {{ count($pembayarans) }} Total Transaksi
                </span>
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[11px] font-black uppercase tracking-wider rounded-xl">
                    <i class="ph-bold ph-check-circle"></i>
                    {{ count($pembayarans) }} Lunas
                </span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
            <h3 class="text-lg font-black text-gray-800 tracking-tight">Riwayat Pembayaran</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] font-black text-gray-400 uppercase tracking-[2px]">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Nim</th>
                        <th class="px-6 py-4">Tahun Semester</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pembayarans as $key => $p)
                    <tr class="group hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-8">
                            <span class="text-sm font-bold text-gray-400">{{ $key + 1 }}</span>
                        </td>
                        <td class="px-6 py-8">
                            <span class="font-black text-gray-800">{{ $p->nama }}</span>
                        </td>
                        <td class="px-6 py-8">
                            <span class="text-sm font-bold text-gray-400">{{ $p->nim }}</span>
                        </td>
                        <td class="px-6 py-8">
                            <span class="font-bold text-blue-600">{{ $p->tahun_ajaran }}</span>
                        </td>
                        <td class="px-6 py-8">
                            <span class="text-sm font-bold text-gray-500">{{ $p->tanggal_pembayaran->translatedFormat('d F Y') }}</span>
                        </td>
                          <td class="px-6 py-8">
                            <span class="font-black text-gray-800">Rp {{ number_format($p->nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-8">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-emerald-100">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                Lunas
                            </span>
                        </td>
                        <td class="px-6 py-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.pembayaran.edit', $p->id) }}" 
                                   class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-amber-50 hover:text-amber-600 transition-all">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </a>
                                <form action="{{ route('admin.pembayaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all">
                                        <i class="ph-bold ph-trash text-lg"></i>
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
</div>
@endsection
