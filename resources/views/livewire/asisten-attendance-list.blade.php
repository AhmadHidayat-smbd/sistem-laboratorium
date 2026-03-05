<div wire:poll.2s.visible>
    <!-- Filter Section -->
    <div class="glass-card rounded-2xl p-6 shadow-lg animate-fadeIn mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Mata Kuliah</label>
                <div class="relative">
                    <select wire:model.live.debounce.300ms="matakuliahId" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all text-sm font-medium text-gray-700">
                        <option value="">📚 Tampilkan Semua Matakuliah Aktif</option>
                        @foreach($matakuliahList as $mkItem)
                            <option value="{{ $mkItem->id }}">{{ $mkItem->kode }} — {{ $mkItem->nama }}</option>
                        @endforeach
                    </select>
                    <!-- Loading Indicator -->
                    <div wire:loading wire:target="matakuliahId" class="absolute inset-y-0 right-3 flex items-center">
                         <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="flex items-end gap-2">
                <button wire:click="resetFilter" class="px-6 py-2.5 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold border border-gray-200 transition-all text-sm">
                    Reset
                </button>
                <button onclick="handleExport('{{ route('asisten.absensi.export.excel.all') }}')" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-sm transition-all text-sm">
                    <i class="ph-bold ph-file-xls text-xl"></i>
                    <span>Export Semua</span>
                </button>
            </div>
        </div>

        <div class="mt-3 flex items-center gap-2 text-sm">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">
                <i class="ph-fill ph-book-open"></i>
                {{ $matakuliah->count() }} mata kuliah aktif
            </span>
            @if($matakuliahId)
                @php $selectedMk = $matakuliahList->firstWhere('id', $matakuliahId); @endphp
                @if($selectedMk)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
                    <i class="ph-fill ph-funnel"></i>
                    {{ $selectedMk->nama }}
                </span>
                @endif
            @endif
        </div>
    </div>

    <!-- Loop Mata Kuliah -->
    @foreach($matakuliah as $mk)
        @php
            $dataAbsensi = $absensi[$mk->id] ?? collect();
            $listPeserta = $peserta[$mk->id] ?? collect();
            $pertemuanAktif = $dataAbsensi->pluck('pertemuan')->unique();
        @endphp

        @if($listPeserta->count())
        <div class="space-y-4 animate-fadeIn mb-12">
            <div class="glass-card rounded-2xl p-6 shadow-lg border-l-4 border-blue-600">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $mk->nama }}</h2>
                            <p class="text-sm text-gray-600 font-medium">{{ $mk->kode }} • Total Peserta: {{ $listPeserta->count() }} mahasiswa</p>
                        </div>
                    </div>

                    <button onclick="handleExport('{{ route('asisten.absensi.export.excel', ['matakuliah_id' => $mk->id]) }}')"
                       class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 rounded-xl font-bold transition-all text-sm group">
                        <i class="ph-bold ph-file-xls text-xl"></i>
                        <span>Export Excel</span>
                    </button>
                </div>
            </div>

            <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-blue-50 border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">NIM</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Nama Mahasiswa</th>
                                @for($i = 1; $i <= 8; $i++)
                                    <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">P{{ $i }}</th>
                                @endfor
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Responsi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Total Hadir</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @php $no = 1; @endphp
                            @foreach($listPeserta as $mhs)
                            @php
                                $hadirMhs = $dataAbsensi->where('mahasiswa_id', $mhs->id)
                                    ->where('status', 'Hadir')
                                    ->whereBetween('pertemuan', [1, 8])
                                    ->count();
                                $pertemuanMhs = $dataAbsensi->where('mahasiswa_id', $mhs->id)->pluck('pertemuan')->toArray();
                            @endphp
                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $no++ }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono font-semibold text-blue-600">{{ $mhs->nim }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $mhs->nama }}</span>
                                </td>

                                @for($p = 1; $p <= 8; $p++)
                                    @php
                                        $row = $dataAbsensi->first(fn($a) =>
                                            $a->mahasiswa_id == $mhs->id &&
                                            $a->pertemuan == $p
                                        );
                                    @endphp
                                    <td class="px-4 py-4 text-center">
                                        @if(!$pertemuanAktif->contains($p))
                                            <span class="text-gray-300 text-sm">—</span>
                                        @elseif($row && $row->status === 'Hadir')
                                            <div class="w-3 h-3 bg-green-500 rounded-full mx-auto" title="Hadir"></div>
                                        @elseif($row && $row->status === 'Tidak Hadir')
                                            <div class="w-3 h-3 bg-red-500 rounded-full mx-auto" title="Tidak Hadir"></div>
                                        @else
                                            <span class="text-gray-300 text-sm">—</span>
                                        @endif
                                    </td>
                                @endfor

                                {{-- Kolom Responsi (Pertemuan 9) --}}
                                @php
                                    $rowRes = $dataAbsensi->first(fn($a) =>
                                        $a->mahasiswa_id == $mhs->id &&
                                        $a->pertemuan == 9
                                    );
                                @endphp
                                <td class="px-4 py-4 text-center">
                                    @if(!$pertemuanAktif->contains(9))
                                        <span class="text-gray-300 text-sm">—</span>
                                    @elseif($rowRes && $rowRes->status === 'Hadir')
                                        <div class="w-3 h-3 bg-green-500 rounded-full mx-auto"></div>
                                    @elseif($rowRes && $rowRes->status === 'Tidak Hadir')
                                        <div class="w-3 h-3 bg-red-500 rounded-full mx-auto"></div>
                                    @else
                                        <span class="text-gray-300 text-sm">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-4 py-2 rounded-lg text-base font-bold 
                                        {{ $hadirMhs >= 6 ? 'bg-green-100 text-green-700' : ($hadirMhs >= 4 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $hadirMhs }}/8
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if(count($pertemuanMhs) > 0)
                                        <button onclick='bukaModalHapus({{ json_encode($mhs->id) }}, {{ json_encode($mk->id) }}, "{{ $mhs->nama }}", {{ json_encode($pertemuanMhs) }})' 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold text-sm transition-all shadow-sm hover:shadow-md">
                                            <i class="ph-bold ph-trash"></i>
                                            Hapus
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    @if($matakuliah->count() === 0)
        <div class="text-center py-20 opacity-30">
            <i class="ph-bold ph-book-open text-6xl mb-4 block"></i>
            <p class="text-xl font-black uppercase tracking-widest">Belum ada mata kuliah aktif</p>
        </div>
    @endif
</div>
