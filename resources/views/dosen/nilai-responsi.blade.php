@extends('layouts.dosen')

@section('title', 'Nilai Responsi')
@section('page_title', 'Nilai Responsi')
@section('page_subtitle', 'Kelola nilai responsi mahasiswa')

@section('content')
<div class="p-6 lg:p-8 space-y-6">

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Tahun Ajaran -->
            <div class="flex-1 w-full">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select id="filterTahunAjaran" onchange="applyFilter()" class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none font-semibold text-gray-700">
                        <option value="all" {{ $tahunAjaran == '' ? 'selected' : '' }}>📅 Semua Semester</option>
                        @foreach($tahunAjaranList as $ta)
                            @php
                                $parts = explode('-', $ta);
                                $semLabel = ($parts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
                            @endphp
                            <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>
                                {{ $ta }} — Semester {{ $semLabel }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ph-fill ph-calendar absolute left-3 top-1/2 -translate-y-1/2 text-xl text-blue-500"></i>
                    <i class="ph-fill ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-xl text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <!-- Mata Kuliah -->
            <div class="flex-1 w-full">
                <label class="block text-sm font-bold text-gray-700 mb-2">Mata Kuliah</label>
                <div class="relative">
                    <select id="filterMatakuliah" onchange="applyFilter()" class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all appearance-none font-semibold text-gray-700">
                        <option value="">📚 Semua Mata Kuliah</option>
                        @foreach($matakuliah as $mk)
                            <option value="{{ $mk->id }}" {{ $matakuliahId == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode }} — {{ $mk->nama }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ph-fill ph-book-open absolute left-3 top-1/2 -translate-y-1/2 text-xl text-violet-500"></i>
                    <i class="ph-fill ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-xl text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <!-- Reset -->
            <a href="{{ route('dosen.nilai-responsi') }}" class="h-[46px] px-5 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition-all">
                <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
            </a>
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
            </div>
        @endif
    </div>

    <!-- Per Matakuliah Sections -->
    @php $anyPeserta = false; @endphp
    @foreach($matakuliah as $mk)
        @if($matakuliahId && $matakuliahId != $mk->id) @continue @endif

        @php
            $listPeserta = $peserta[$mk->id] ?? collect();
            $grades = $nilaiResponsi[$mk->id] ?? collect();
        @endphp

        @if($listPeserta->count())
            @php $anyPeserta = true; @endphp
            <div>
                <!-- Course Header -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                        <i class="ph-fill ph-medal text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">{{ $mk->nama }}</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-widest">{{ $mk->kode }}</span>
                            @if($mk->tahun_ajaran)
                            <span class="text-[10px] font-bold text-violet-500 bg-violet-50 px-2 py-0.5 rounded-full tracking-wide">{{ $mk->tahun_ajaran }}</span>
                            @endif
                            <span class="text-[10px] font-bold text-gray-400">• {{ $listPeserta->count() }} mahasiswa</span>
                        </div>
                    </div>
                    <div class="h-1 flex-1 bg-gray-50 rounded-full mx-4"></div>
                    <button onclick="handleExport('{{ route('dosen.nilai-responsi.export', $mk->id) }}')"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-700 font-bold rounded-xl border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all shadow-sm text-[10px] uppercase tracking-wider whitespace-nowrap">
                        <i class="ph-fill ph-file-xls text-lg"></i>
                        <span>Export Excel</span>
                    </button>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider w-40 text-center">Nilai</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider w-32 text-center">Status</th>
                                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-wider w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($listPeserta as $mhs)
                                @php
                                    $nilaiData = $grades->firstWhere('mahasiswa_id', $mhs->id);
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-blue-500 text-white rounded-lg flex items-center justify-center font-bold text-xs shadow">
                                                {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 text-sm">{{ $mhs->nama }}</div>
                                                <span class="font-mono font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-[10px]">{{ $mhs->nim }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="relative flex items-center justify-center">
                                            <input type="number" 
                                                   min="0" 
                                                   max="100" 
                                                   value="{{ $nilaiData ? $nilaiData->nilai : '' }}" 
                                                   data-mahasiswa-id="{{ $mhs->id }}"
                                                   data-matakuliah-id="{{ $mk->id }}"
                                                   class="nilai-input w-24 text-center py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-bold text-gray-800 transition-all"
                                                   placeholder="0"
                                                   onchange="saveNilai(this)">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <textarea 
                                            data-mahasiswa-id="{{ $mhs->id }}"
                                            data-matakuliah-id="{{ $mk->id }}"
                                            id="catatan-{{ $mk->id }}-{{ $mhs->id }}"
                                            class="catatan-input w-full min-w-[200px] py-2 px-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 transition-all resize-none"
                                            rows="2"
                                            placeholder="Catatan..."
                                            onchange="saveNilai(this.closest('tr').querySelector('.nilai-input'))">{{ $nilaiData ? $nilaiData->catatan : '' }}</textarea>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div id="status-{{ $mk->id }}-{{ $mhs->id }}" class="transition-all duration-300 transform scale-0 opacity-0">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($nilaiData)
                                        <button onclick='confirmDeleteNilai("{{ $nilaiData->id }}", "{{ $mhs->nama }}")' 
                                                class="w-9 h-9 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300 mx-auto"
                                                title="Hapus Nilai">
                                            <i class="ph-bold ph-trash text-base"></i>
                                        </button>
                                        @else
                                        <span class="text-gray-300 text-sm">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="ph-fill ph-info"></i>
                            Nilai otomatis tersimpan saat Anda berpindah kolom atau menekan Enter
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if(!$anyPeserta)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ph-fill ph-users text-3xl text-blue-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Data Responsi</h3>
        <p class="text-gray-500">Belum ada mahasiswa yang tercatat <strong>HADIR</strong> pada pertemuan Responsi (P9).</p>
    </div>
    @endif

</div>

<script>
function applyFilter() {
    const url = new URL(window.location.href);
    const ta = document.getElementById('filterTahunAjaran').value;
    const mk = document.getElementById('filterMatakuliah').value;
    
    url.searchParams.set('tahun_ajaran', ta);
    if (mk) {
        url.searchParams.set('matakuliah_id', mk);
    } else {
        url.searchParams.delete('matakuliah_id');
    }
    window.location.href = url.toString();
}

async function saveNilai(input) {
    const mahasiswaId = input.dataset.mahasiswaId;
    const matakuliahId = input.dataset.matakuliahId;
    const nilai = input.value;
    const catatanEl = document.getElementById(`catatan-${matakuliahId}-${mahasiswaId}`);
    const catatan = catatanEl ? catatanEl.value : '';
    const statusDiv = document.getElementById(`status-${matakuliahId}-${mahasiswaId}`);

    if (!nilai || nilai < 0 || nilai > 100) return;

    statusDiv.innerHTML = '<span class="text-xs font-semibold text-gray-400"><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>';
    statusDiv.classList.remove('scale-0', 'opacity-0');

    try {
        const response = await fetch('{{ route("dosen.nilai-responsi.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                mahasiswa_id: mahasiswaId,
                matakuliah_id: matakuliahId,
                nilai: nilai,
                catatan: catatan
            })
        });

        const data = await response.json();

        if (response.ok) {
            statusDiv.innerHTML = '<span class="text-xs font-bold text-green-600 flex items-center justify-center gap-1"><i class="ph-fill ph-check-circle"></i> Tersimpan</span>';
            input.classList.add('border-green-500', 'bg-green-50');
            setTimeout(() => {
                input.classList.remove('border-green-500', 'bg-green-50');
                setTimeout(() => {
                    statusDiv.classList.add('scale-0', 'opacity-0');
                }, 2000);
            }, 1000);
        } else {
            throw new Error(data.message || 'Gagal menyimpan');
        }
    } catch (error) {
        console.error('Error:', error);
        statusDiv.innerHTML = '<span class="text-xs font-bold text-red-600 flex items-center justify-center gap-1"><i class="ph-fill ph-x-circle"></i> Gagal</span>';
        input.classList.add('border-red-500', 'bg-red-50');
    }
}

async function confirmDeleteNilai(id, nama) {
    const result = await Swal.fire({
        title: 'Hapus nilai?',
        text: `Nilai responsi ${nama} akan dihapus!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        try {
            const res = await fetch(`/dosen/nilai-responsi/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });
            const data = await res.json();
            if (data.success) {
                Swal.fire('Terhapus', 'Nilai berhasil dihapus', 'success').then(() => location.reload());
            }
        } catch (err) {
            Swal.fire('Error', 'Gagal menghapus nilai', 'error');
        }
    }
}

function handleExport(url) {
    Swal.fire({
        title: 'Mempersiapkan File...',
        html: `
            <div class="flex flex-col items-center gap-4 py-4">
                <div class="w-20 h-20 border-4 border-blue-100 border-t-emerald-500 rounded-full animate-spin"></div>
                <p class="text-gray-500 font-medium animate-pulse">Sedang mengolah data Excel Anda</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: () => {
            window.location.href = url;
        }
    }).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Selesai!',
            text: 'File Excel sedang diunduh.',
            timer: 2000,
            showConfirmButton: false
        });
    });
}
</script>
@endsection
