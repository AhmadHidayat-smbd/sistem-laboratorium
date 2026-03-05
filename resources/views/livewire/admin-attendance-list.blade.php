<div wire:poll.2s.visible>
    <!-- Filter Box -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 mb-12 animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex flex-col md:flex-row items-end gap-6">
            <div class="flex-1 w-full">
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] mb-3 ml-2">Pilih Tahun Ajaran / Semester</label>
                <div class="relative group">
                    <select wire:model.live.debounce.300ms="tahunAjaran" class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-700">
                        <option value="">📚 Tampilkan Semua Semester</option>
                        @foreach($tahunAjaranList as $ta)
                            @php
                                $parts = explode('-', $ta);
                                $semLabel = ($parts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
                            @endphp
                            <option value="{{ $ta }}">{{ $ta }} — Semester {{ $semLabel }}</option>
                        @endforeach
                    </select>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
                        <i class="ph-bold ph-calendar text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <button wire:click="resetFilter" class="flex-1 md:flex-none bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-4 rounded-[1.5rem] font-bold transition-all duration-300 flex items-center justify-center gap-2" title="Reset ke semester aktif">
                    <i class="ph-bold ph-arrow-counter-clockwise text-xl"></i>
                </button>
            </div>
        </div>

        @if($tahunAjaran)
            @php
                $taParts = explode('-', $tahunAjaran);
                $taLabel = ($taParts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
            @endphp
            <div class="mt-4 flex items-center gap-2 text-sm">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
                    <i class="ph-fill ph-calendar"></i>
                    {{ $tahunAjaran }} • Semester {{ $taLabel }}
                </span>
                <span class="text-gray-400 font-semibold">— {{ $allMatakuliah->count() }} mata kuliah</span>
            </div>
        @endif
    </div>

    <!-- Result Sections -->
    <div class="space-y-12 mb-10">
        @foreach($allMatakuliah as $mk)
            @php
                $dataAbsensi = $absensi[$mk->id] ?? collect();
                $listPeserta = $peserta[$mk->id] ?? collect();
                $pertemuanAktif = $dataAbsensi->pluck('pertemuan')->unique();
            @endphp

            @if($listPeserta->count())
            <div class="animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                        <i class="ph-fill ph-notebook text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $mk->nama }}</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $mk->kode }} • {{ $listPeserta->count() }} Mahasiswa Terdaftar</p>
                    </div>
                    <div class="h-1 flex-1 bg-gray-100 rounded-full mx-4"></div>
                    <button onclick="handleExport('{{ route('admin.absensi.export.excel') }}?matakuliah_id={{ $mk->id }}')" 
                            class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl font-bold flex items-center gap-2 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                        <i class="ph-bold ph-file-xls"></i>
                        <span class="text-xs">Export</span>
                    </button>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                                    <th class="px-6 py-6 border-r border-gray-100">Mhs Info</th>
                                    @for($i=1;$i<=8;$i++)
                                        <th class="px-2 py-6 text-center border-r border-gray-100">P{{ $i }}</th>
                                    @endfor
                                    <th class="px-2 py-6 text-center border-r border-gray-100">Resp</th>
                                    <th class="px-4 py-6 text-center border-r border-gray-100">Total</th>
                                    <th class="px-6 py-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($listPeserta as $mhs)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-6 min-w-[200px] border-r border-gray-50">
                                        <div class="font-black text-gray-800 tracking-tight leading-tight mb-1">{{ $mhs->nama }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 tracking-widest bg-gray-50 px-2 py-0.5 rounded-full w-max">{{ $mhs->nim }}</div>
                                    </td>

                                    @for($p=1;$p<=8;$p++)
                                        @php
                                            $row = $dataAbsensi->first(fn($a) => $a->mahasiswa_id == $mhs->id && $a->pertemuan == $p);
                                        @endphp
                                        <td class="px-2 py-6 text-center border-r border-gray-50">
                                            @if(!$pertemuanAktif->contains($p))
                                                <span class="text-gray-200">●</span>
                                            @elseif($row && $row->status === 'Hadir')
                                                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full mx-auto shadow-sm shadow-emerald-200 ring-4 ring-emerald-50" title="Hadir"></div>
                                            @elseif($row && $row->status === 'Tidak Hadir')
                                                <div class="w-2.5 h-2.5 bg-rose-500 rounded-full mx-auto shadow-sm shadow-rose-200 ring-4 ring-rose-50" title="Tidak Hadir"></div>
                                            @else
                                                <span class="text-gray-200">●</span>
                                            @endif
                                        </td>
                                    @endfor

                                    {{-- Responsi --}}
                                    @php
                                        $rowRes = $dataAbsensi->first(fn($a) => $a->mahasiswa_id == $mhs->id && $a->pertemuan == 9);
                                    @endphp
                                    <td class="px-2 py-6 text-center border-r border-gray-50">
                                        @if(!$pertemuanAktif->contains(9))
                                            <span class="text-gray-200">●</span>
                                        @elseif($rowRes && $rowRes->status === 'Hadir')
                                            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full mx-auto ring-4 ring-emerald-50"></div>
                                        @elseif($rowRes && $rowRes->status === 'Tidak Hadir')
                                            <div class="w-2.5 h-2.5 bg-rose-500 rounded-full mx-auto ring-4 ring-rose-50"></div>
                                        @else
                                            <span class="text-gray-200">●</span>
                                        @endif
                                    </td>

                                    {{-- Total --}}
                                    @php
                                        $hadirMhs = $dataAbsensi->where('mahasiswa_id', $mhs->id)
                                            ->where('status', 'Hadir')
                                            ->whereBetween('pertemuan', [1, 8])
                                            ->count();
                                    @endphp
                                    <td class="px-4 py-6 text-center border-r border-gray-50">
                                        <span class="text-xs font-black {{ $hadirMhs >= 6 ? 'text-emerald-600' : ($hadirMhs >= 4 ? 'text-amber-500' : 'text-rose-500') }}">
                                            {{ $hadirMhs }}/8
                                        </span>
                                    </td>

                                    <td class="px-6 py-6 border-r border-gray-50">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.absensi.edit.form', [$mhs->id, $mk->id]) }}" 
                                               class="w-9 h-9 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300"
                                               title="Edit Status">
                                                <i class="ph-bold ph-note-pencil"></i>
                                            </a>
                                            @php $pertemuanMhs = $dataAbsensi->where('mahasiswa_id', $mhs->id)->pluck('pertemuan')->toArray(); @endphp
                                            <button onclick='window.parentOpenDeleteModal("{{ $mhs->id }}", "{{ $mk->id }}", "{{ addslashes($mhs->nama) }}", "{{ addslashes($mk->nama) }}", {{ json_encode($pertemuanMhs) }})' 
                                                    class="w-9 h-9 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
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

        @if($allMatakuliah->count() === 0)
            <div class="text-center py-20 opacity-30">
                <i class="ph-bold ph-book-open text-6xl mb-4 block"></i>
                <p class="text-xl font-black uppercase tracking-widest">Belum ada mata kuliah di semester ini</p>
            </div>
        @endif
    </div>
    
    <script>
        // Bridge function to call parent window's openDeleteModal
        window.parentOpenDeleteModal = function(mhsId, mkId, mhsNama, mkNama, pertemuanAda) {
            if (typeof openDeleteModal === 'function') {
                openDeleteModal(mhsId, mkId, mhsNama, mkNama, pertemuanAda);
            } else {
                console.error("Fungsi openDeleteModal belum ter-load dari halaman utama.");
            }
        };
    </script>
</div>
