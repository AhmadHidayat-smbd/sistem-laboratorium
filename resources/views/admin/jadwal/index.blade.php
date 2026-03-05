@extends('layouts.admin')

@section('title', 'Jadwal Kuliah')
@section('page_title', 'Jadwal Kuliah')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Jadwal Kuliah</h2>
        <p class="text-gray-500 font-medium">Pengaturan jadwal mingguan untuk setiap mata kuliah praktikum iTlabs.</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.jadwal.create') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i>
            <span>Tambah Jadwal Baru</span>
        </a>
    </div>
</div>

@php    
    $hariList = [
        'Monday' => ['label' => 'Senin', 'color' => 'blue', 'icon' => 'ph-fill ph-calendar-blank'],
        'Tuesday' => ['label' => 'Selasa', 'color' => 'indigo', 'icon' => 'ph-fill ph-calendar-blank'],
        'Wednesday' => ['label' => 'Rabu', 'color' => 'cyan', 'icon' => 'ph-fill ph-calendar-blank'],
        'Thursday' => ['label' => 'Kamis', 'color' => 'teal', 'icon' => 'ph-fill ph-calendar-blank'],
        'Friday' => ['label' => 'Jumat', 'color' => 'emerald', 'icon' => 'ph-fill ph-calendar-blank'],
    ];
@endphp

<div class="space-y-12 mb-10">
    @foreach($hariList as $dayEN => $dayInfo)
    @php $dataHari = $jadwal->filter(fn($j) => strtolower($j->hari) === strtolower($dayEN)); @endphp
    
    <div class="animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-{{ $dayInfo['color'] }}-100 text-{{ $dayInfo['color'] }}-600 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="{{ $dayInfo['icon'] }} text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">{{ $dayInfo['label'] }}</h2>
            <div class="h-1 flex-1 bg-gray-100 rounded-full ml-2"></div>
            @if($dataHari->count() > 0)
                <span class="text-xs font-black text-{{ $dayInfo['color'] }}-500 bg-{{ $dayInfo['color'] }}-50 px-4 py-1.5 rounded-full uppercase tracking-widest border border-{{ $dayInfo['color'] }}-100">
                    {{ $dataHari->count() }} Jadwal
                </span>
            @endif
        </div>

        @if($dataHari->count() === 0)
        <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-[2rem] p-10 text-center">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Tidak ada jadwal kuliah untuk hari ini</p>
        </div>
        @else
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Mata Kuliah</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Waktu</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($dataHari as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-{{ $dayInfo['color'] }}-50 text-{{ $dayInfo['color'] }}-600 rounded-xl flex items-center justify-center font-black group-hover:scale-110 transition-transform">
                                        <i class="ph-fill ph-book text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-800 tracking-tight">{{ $item->matakuliah->nama }}</h4>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $item->matakuliah->kode }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <span class="px-4 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-black tracking-tight">
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                    </span>
                                    <i class="ph-bold ph-arrow-right text-gray-300"></i>
                                    <span class="px-4 py-1.5 bg-gray-50 text-gray-600 rounded-full text-sm font-black tracking-tight">
                                        {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.jadwal.edit', $item->id) }}" 
                                       class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300">
                                        <i class="ph-bold ph-note-pencil text-lg"></i>
                                    </a>
                                    
                                    <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('admin.jadwal.delete', $item->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-form-{{ $item->id }}')" 
                                                class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300">
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
        @endif
    </div>
    @endforeach
</div>
@endsection

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
