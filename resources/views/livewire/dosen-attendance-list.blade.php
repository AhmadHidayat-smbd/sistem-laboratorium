<div wire:poll.2s.visible>
    <!-- Filter Section -->
    <div class="glass-card rounded-2xl p-6 shadow-lg animate-fadeIn mb-8 bg-white border border-gray-100">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Tahun Ajaran / Semester</label>
                <div class="relative">
                    <select wire:model.live.debounce.300ms="tahunAjaran" class="w-full pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all text-sm font-medium text-gray-700 appearance-none">
                        <option value="">📚 Tampilkan Semua Semester</option>
                        @foreach($tahunAjaranList as $ta)
                            @php
                                $parts = explode('-', $ta);
                                $semLabel = ($parts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
                            @endphp
                            <option value="{{ $ta }}">{{ $ta }} — Semester {{ $semLabel }}</option>
                        @endforeach
                    </select>
                    <!-- Loading Indicator -->
                    <div wire:loading wire:target="tahunAjaran" class="absolute inset-y-0 right-10 flex items-center pr-2">
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
            </div>
        </div>

        @if($tahunAjaran)
            @php
                $taParts = explode('-', $tahunAjaran);
                $taLabel = ($taParts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
            @endphp
            <div class="mt-3 flex items-center gap-2 text-sm">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
                    <i class="ph-fill ph-calendar"></i>
                    {{ $tahunAjaran }} • Semester {{ $taLabel }}
                </span>
                <span class="text-gray-400 font-semibold">— {{ $matakuliahList->count() }} mata kuliah</span>
            </div>
        @endif
    </div>

    <!-- Loop Mata Kuliah -->
    @foreach($matakuliahList as $mk)
        @if(!isset($peserta[$mk->id]) || $peserta[$mk->id]->isEmpty())
            @continue
        @endif

        @php
            $mkPeserta = $peserta[$mk->id];
            $mkAbsensi = $absensi[$mk->id] ?? collect();
            $pertemuanAktif = $mkAbsensi->pluck('pertemuan')->unique();
        @endphp

        <div class="space-y-4 animate-fadeIn mb-12">
            <!-- Header Card per Matakuliah -->
            <div class="glass-card rounded-2xl p-6 shadow-lg border-l-4 border-blue-600 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                             <span class="font-black text-xl">{{ substr($mk->nama, 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $mk->nama }}</h2>
                            <p class="text-sm text-gray-600 font-medium tracking-wide">{{ $mk->kode }} • Total Peserta: {{ $mkPeserta->count() }} mahasiswa</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-1 flex-1 bg-gray-100 rounded-full hidden md:block" style="min-width:40px"></div>
                        <button onclick="handleExport('{{ route('dosen.absensi.export.excel') }}?matakuliah_id={{ $mk->id }}')" 
                           class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl font-bold flex items-center gap-2 hover:bg-emerald-600 hover:text-white transition-all shadow-sm text-sm">
                            <i class="ph-bold ph-file-xls"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="glass-card rounded-2xl shadow-lg overflow-hidden bg-white border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Mahasiswa</th>
                                @for($i = 1; $i <= 8; $i++)
                                    <th class="px-2 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest w-10">P{{ $i }}</th>
                                @endfor
                                <th class="px-2 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest w-10 text-purple-600">Resp</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Total Hadir</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($mkPeserta as $mhs)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-400 group-hover:text-blue-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $mhs->nama }}</div>
                                    <div class="text-xs font-mono font-semibold text-blue-600 mt-0.5">{{ $mhs->nim }}</div>
                                </td>

                                @for($p = 1; $p <= 8; $p++)
                                    @php
                                        $row = $mkAbsensi->first(fn($a) => $a->mahasiswa_id == $mhs->id && $a->pertemuan == $p);
                                    @endphp
                                    <td class="px-2 py-4 text-center">
                                        @if(!$pertemuanAktif->contains($p))
                                            <span class="text-gray-200 text-lg leading-none">•</span>
                                        @elseif($row && $row->status === 'Hadir')
                                            <div class="w-3 h-3 bg-emerald-500 rounded-full mx-auto shadow-sm shadow-emerald-200" title="Hadir"></div>
                                        @elseif($row && $row->status === 'Tidak Hadir')
                                            <div class="w-3 h-3 bg-rose-500 rounded-full mx-auto shadow-sm shadow-rose-200" title="Tidak Hadir/Alpa"></div>
                                        @else
                                            <span class="text-gray-200 text-lg leading-none">•</span>
                                        @endif
                                    </td>
                                @endfor

                                {{-- Kolom Responsi (Pertemuan 9) --}}
                                @php
                                    $rowRes = $mkAbsensi->first(fn($a) => $a->mahasiswa_id == $mhs->id && $a->pertemuan == 9);
                                @endphp
                                <td class="px-2 py-4 text-center border-l border-gray-50 bg-gray-50/30">
                                    @if($rowRes && $rowRes->status === 'Hadir')
                                        <div class="w-3 h-3 bg-purple-500 rounded-full mx-auto shadow-sm shadow-purple-200" title="Hadir Responsi"></div>
                                    @elseif($rowRes && $rowRes->status === 'Tidak Hadir')
                                        <div class="w-3 h-3 bg-rose-500 rounded-full mx-auto shadow-sm shadow-rose-200" title="Tidak Hadir Responsi"></div>
                                    @else
                                        <span class="text-gray-200 text-lg leading-none">•</span>
                                    @endif
                                </td>

                                {{-- Total Hadir --}}
                                @php
                                    $hadirMhs = $mkAbsensi->where('mahasiswa_id', $mhs->id)
                                        ->where('status', 'Hadir')
                                        ->whereBetween('pertemuan', [1, 8])
                                        ->count();
                                @endphp
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-3 py-1 rounded-lg text-xs font-bold tracking-wide
                                        {{ $hadirMhs >= 6 ? 'bg-emerald-100 text-emerald-700' : ($hadirMhs >= 4 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ $hadirMhs }} / 8
                                    </span>
                                </td>
                                
                                {{-- Aksi (Edit) --}}
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('dosen.absensi.edit', ['mahasiswa' => $mhs->id, 'matakuliah' => $mk->id]) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 hover:bg-blue-600 text-gray-500 hover:text-white rounded-lg transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5"
                                       title="Edit Presensi">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    @if($matakuliahList->count() === 0)
        <div class="text-center py-20 opacity-30">
            <i class="ph-bold ph-book-open text-6xl mb-4 block"></i>
            <p class="text-xl font-black uppercase tracking-widest">Belum ada mata kuliah di semester ini</p>
        </div>
    @endif

    <!-- Legend -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 p-6 bg-white rounded-2xl border border-gray-100 animate-fadeIn text-xs font-bold text-gray-500 uppercase tracking-wide">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div> Hadir
        </div>
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-purple-500 shadow-sm shadow-purple-200"></div> Responsi
        </div>
        <div class="flex items-center gap-3">
             <span class="text-gray-300 text-lg leading-none transform translate-y-[-2px]">•</span> Belum Ada Data
        </div>
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-200"></div> Titip Absen
        </div>
    </div>
</div>
