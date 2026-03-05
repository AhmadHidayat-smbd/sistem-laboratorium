<div wire:poll.2s.visible class="space-y-8">
    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn" style="animation-delay: 0.1s">
        <!-- Total Hadir -->
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Kehadiran</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl md:text-5xl font-bold text-gray-900">{{ $totalHadir }}</h3>
                        <span class="text-sm font-medium text-gray-400">sesi</span>
                    </div>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <i class="ph-bold ph-check-circle text-2xl text-blue-600"></i>
                </div>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                @php $overallPercentage = $totalMatkul > 0 ? min(100, ($totalHadir / ($totalMatkul * 9)) * 100) : 0; @endphp
                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000" style="width: {{ $overallPercentage }}%"></div>
            </div>
        </div>

        <!-- Total Matkul -->
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mata Kuliah Diikuti</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl md:text-5xl font-bold text-gray-900">{{ $totalMatkul }}</h3>
                        <span class="text-sm font-medium text-gray-400">mata kuliah</span>
                    </div>
                </div>
                <div class="bg-purple-50 p-3 rounded-xl">
                    <i class="ph-bold ph-books text-2xl text-purple-600"></i>
                </div>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fadeIn" style="animation-delay: 0.2s">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Detail Presensi</h2>
            <p class="text-sm text-gray-500 mt-1">Status kehadiran per mata kuliah diperbarui secara real-time</p>
        </div>
        <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-lg border border-gray-200 text-sm">
            <i class="ph-bold ph-info text-gray-400"></i>
            <span class="font-medium text-gray-600">P1-8 & Responsi</span>
        </div>
    </div>

    <!-- Attendance Cards -->
    <div class="space-y-6">
        @forelse($matakuliah as $index => $mk)
            @php
                $mkAbsensi = $absensi_grouped->get($mk->id) ?? collect();
                $hadirCount = $mkAbsensi->where('status', 'Hadir')->where('pertemuan', '<=', 8)->count();
                $hasResponsi = $mkAbsensi->where('pertemuan', 9)->where('status', 'Hadir')->count() > 0;
                $percentage = round(($hadirCount/8)*100);
            @endphp
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 card-hover animate-slideUp" style="animation-delay: {{ 0.05 * $index }}s">
                
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 pb-6 border-b border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                            <i class="ph-bold ph-book-open text-2xl text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">{{ $mk->nama }}</h4>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 bg-gray-100 px-3 py-1 rounded-lg text-xs font-semibold text-gray-600">
                                    <i class="ph-bold ph-hash text-xs"></i>
                                    {{ $mk->kode }}
                                </span>
                                @if($mk->tahun_ajaran)
                                <span class="inline-flex items-center gap-1 bg-violet-50 px-3 py-1 rounded-lg text-xs font-semibold text-violet-600">
                                    <i class="ph-bold ph-calendar text-xs"></i>
                                    {{ $mk->tahun_ajaran }}
                                </span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 px-3 py-1 rounded-lg text-xs font-semibold text-blue-600">
                                    <i class="ph-bold ph-check text-xs"></i>
                                    {{ $hadirCount }}/8 Hadir
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress -->
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500 mb-1">Kehadiran</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $percentage }}%</p>
                        </div>
                        <div class="relative w-20 h-20">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="6" fill="none" class="text-gray-100"/>
                                <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="6" fill="none" 
                                    class="text-blue-600 transition-all duration-1000" 
                                    stroke-dasharray="226" 
                                    stroke-dashoffset="{{ 226 - (226 * $percentage / 100) }}"
                                    stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Attendance Grid -->
                <div class="grid grid-cols-5 md:grid-cols-9 gap-3 md:gap-4">
                    @for($p = 1; $p <= 8; $p++)
                        @php
                            $attended = $mkAbsensi->where('pertemuan', $p)->where('status', 'Hadir')->first();
                        @endphp
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-[10px] font-bold {{ $attended ? 'text-blue-600' : 'text-gray-400' }} uppercase tracking-wide">P{{ $p }}</span>
                            <div class="w-full aspect-square rounded-xl flex items-center justify-center border-2 transition-all attendance-cell
                                {{ $attended 
                                    ? 'bg-blue-600 border-blue-600 text-white shadow-sm' 
                                    : 'bg-gray-50 border-gray-200 text-gray-300' 
                                }}">
                                @if($attended)
                                    <i class="ph-bold ph-check text-lg"></i>
                                @else
                                    <i class="ph-bold ph-x text-sm"></i>
                                @endif
                            </div>
                        </div>
                    @endfor
                    
                    <!-- Responsi -->
                    <div class="flex flex-col items-center gap-2">
                        <span class="text-[10px] font-bold {{ $hasResponsi ? 'text-orange-600' : 'text-gray-400' }} uppercase tracking-wide">Resp</span>
                        <div class="w-full aspect-square rounded-xl flex items-center justify-center border-2 transition-all attendance-cell
                            {{ $hasResponsi 
                                ? 'bg-orange-500 border-orange-500 text-white shadow-sm' 
                                : 'bg-gray-50 border-dashed border-gray-200 text-gray-300' 
                            }}">
                            @if($hasResponsi)
                                <i class="ph-bold ph-seal-check text-lg"></i>
                            @else
                                <i class="ph-bold ph-lightning text-sm"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 md:p-16 shadow-sm border border-gray-100 text-center">
                <i class="ph-bold ph-folder-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Belum ada mata kuliah yang diikuti</p>
            </div>
        @endforelse
    </div>
</div>
