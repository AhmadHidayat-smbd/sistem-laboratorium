@extends('layouts.admin')

@section('title', 'Nilai Responsi')
@section('page_title', 'Nilai Responsi')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-6 animate-fade-in">
    <div class="flex-1">
        <p class="text-gray-500 font-medium">Penginputan dan rekapitulasi nilai responsi mahasiswa per mata kuliah.</p>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 mb-10 animate-fade-in" style="animation-delay: 0.05s">
    <div class="flex flex-col md:flex-row items-end gap-6">
        <!-- Tahun Ajaran -->
        <div class="flex-1 w-full">
            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] mb-3 ml-2">Tahun Ajaran</label>
            <div class="relative">
                <select id="filterTahunAjaran" onchange="filterByTahunAjaran()" class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-700">
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
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
                    <i class="ph-bold ph-calendar text-xl"></i>
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    <i class="ph-bold ph-caret-down text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Mata Kuliah -->
        <div class="flex-1 w-full">
            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] mb-3 ml-2">Mata Kuliah</label>
            <div class="relative">
                <select id="filterMatakuliah" onchange="filterByTahunAjaran()" class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-500 outline-none transition-all duration-300 font-bold text-gray-700">
                    <option value="">📚 Semua Mata Kuliah</option>
                    @foreach($matakuliah as $mk)
                        <option value="{{ $mk->id }}" {{ $matakuliahId == $mk->id ? 'selected' : '' }}>
                            {{ $mk->kode }} — {{ $mk->nama }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-violet-600">
                    <i class="ph-bold ph-book-open text-xl"></i>
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                    <i class="ph-bold ph-caret-down text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Reset -->
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.nilai-responsi') }}" class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-4 rounded-[1.5rem] font-bold transition-all duration-300">
                <i class="ph-bold ph-arrow-counter-clockwise text-xl"></i>
            </a>
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
        </div>
    @endif
</div>

<!-- Result Sections -->
<div class="space-y-12 mb-10">
    @php $anyPeserta = false; @endphp
    @foreach($matakuliah as $mk)
        @if($matakuliahId && $matakuliahId != $mk->id) @continue @endif

        @php
            $listPeserta = $peserta[$mk->id] ?? collect();
            $grades = $nilaiResponsi[$mk->id] ?? collect();
        @endphp

        @if($listPeserta->count())
            @php $anyPeserta = true; @endphp
            <div class="animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                        <i class="ph-fill ph-medal text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $mk->nama }}</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-widest">{{ $mk->kode }}</span>
                            @if($mk->tahun_ajaran)
                            <span class="text-[10px] font-bold text-violet-500 bg-violet-50 px-2 py-0.5 rounded-full tracking-wide">{{ $mk->tahun_ajaran }}</span>
                            @endif
                            <span class="text-[10px] font-bold text-gray-400">• {{ $listPeserta->count() }} mahasiswa</span>
                        </div>
                    </div>
                    <div class="h-1 flex-1 bg-gray-100 rounded-full mx-4"></div>
                    <button onclick="handleExport('{{ route('admin.nilai-responsi.export', $mk->id) }}')"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-50 text-emerald-700 font-black rounded-2xl border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-sm text-xs uppercase tracking-widest whitespace-nowrap">
                        <i class="ph-fill ph-file-xls text-lg"></i>
                        <span>Export Excel</span>
                    </button>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                                    <th class="px-8 py-6 w-20">No</th>
                                    <th class="px-8 py-6">Mahasiswa</th>
                                    <th class="px-8 py-6 text-center">Nilai Responsi</th>
                                    <th class="px-8 py-6">Catatan Dosen</th>
                                    <th class="px-8 py-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($listPeserta as $mhs)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-bold text-gray-400">#{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div>
                                            <div class="font-black text-gray-800 tracking-tight leading-tight mb-1">{{ $mhs->nama }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 tracking-widest bg-gray-50 px-2 py-0.5 rounded-full w-max">{{ $mhs->nim }}</div>
                                        </div>
                                    </td>
                                    @php
                                        $nilaiData = $grades->firstWhere('mahasiswa_id', $mhs->id);
                                    @endphp
                                    <td class="px-8 py-6 text-center">
                                        @if($nilaiData)
                                            @php
                                                $n = $nilaiData->nilai;
                                                $colorClass = $n >= 80 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                                                              ($n >= 60 ? 'bg-blue-50 text-blue-600 border-blue-100' :
                                                              ($n >= 40 ? 'bg-amber-50 text-amber-600 border-amber-100' :
                                                              'bg-rose-50 text-rose-600 border-rose-100'));
                                            @endphp
                                            <div class="inline-flex items-center justify-center w-16 h-16 {{ $colorClass }} rounded-2xl font-black text-2xl shadow-sm border">
                                                {{ $n }}
                                            </div>
                                        @else
                                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 text-gray-300 rounded-2xl font-black text-2xl border border-dashed border-gray-200">
                                                -
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-xs font-medium text-gray-500 max-w-xs italic text-wrap break-words">
                                            {{ $nilaiData->catatan ?? 'Belum ada catatan...' }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick='openNilaiModal("{{ $mhs->id }}", "{{ $mk->id }}", "{{ $mhs->nama }}", "{{ $mk->nama }}")' 
                                                    class="w-10 h-10 {{ $nilaiData ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-blue-600 text-white shadow-lg shadow-blue-100 hover:bg-blue-700' }} rounded-xl flex items-center justify-center transition-all duration-300"
                                                    title="{{ $nilaiData ? 'Edit Nilai' : 'Input Nilai' }}">
                                                <i class="ph-bold {{ $nilaiData ? 'ph-note-pencil' : 'ph-plus-circle' }} text-lg"></i>
                                            </button>
                                            @if($nilaiData)
                                            <button onclick='confirmDeleteNilai("{{ $nilaiData->id }}")' 
                                                    class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300"
                                                    title="Hapus Nilai">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                            @endif
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

    @if(!$anyPeserta)
    <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 p-20 text-center animate-fade-in">
        <div class="w-24 h-24 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ph-fill ph-users-three text-5xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest">Belum Ada Peserta Responsi</h3>
        <p class="text-gray-400 font-medium">Data mahasiswa akan muncul di sini setelah mereka melakukan presensi pada Sesi Responsi (Pertemuan 9).</p>
    </div>
    @endif
</div>

<!-- Nilai Modal -->
<div id="nilaiModal" class="fixed inset-0 hidden items-center justify-center z-[100] p-4 backdrop-blur-md transition-all duration-500">
    <div class="modal-card bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">
        <div class="bg-blue-600 p-8 text-white relative">
            <h3 class="text-2xl font-black uppercase tracking-tight">Input Nilai Responsi</h3>
            <p id="nilaiModalSubtitle" class="text-blue-100 text-sm font-medium"></p>
        </div>
        <form id="nilaiForm" class="p-8 space-y-6">
            @csrf
            <input type="hidden" id="nilaiMhsId">
            <input type="hidden" id="nilaiMkId">
            
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nilai (0 - 100)</label>
                <div class="relative">
                    <input type="number" id="inputNilai" name="nilai" min="0" max="100" required
                           class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-lg text-gray-800">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 font-black text-xl">%</div>
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Catatan Dosen</label>
                <textarea id="inputCatatan" name="catatan" rows="3" 
                          placeholder="Masukkan feedback untuk mahasiswa..."
                          class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium text-gray-700"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeNilaiModal()" class="flex-1 px-6 py-4 rounded-2xl font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-4 rounded-2xl font-black bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Filter
function filterByTahunAjaran() {
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

// Modal
let currentMhsId = null;
let currentMkId = null;

function openNilaiModal(mhsId, mkId, mhsNama, mkNama) {
    currentMhsId = mhsId;
    currentMkId = mkId;
    document.getElementById('nilaiMhsId').value = mhsId;
    document.getElementById('nilaiMkId').value = mkId;
    document.getElementById('nilaiModalSubtitle').textContent = `${mhsNama} — ${mkNama}`;
    
    document.getElementById('inputNilai').value = '';
    document.getElementById('inputCatatan').value = '';

    fetch(`/admin/nilai-responsi/${mhsId}/${mkId}`)
        .then(r => r.json())
        .then(res => {
            if (res.data) {
                document.getElementById('inputNilai').value = res.data.nilai;
                document.getElementById('inputCatatan').value = res.data.catatan || '';
            }
        });

    const modal = document.getElementById('nilaiModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('flex');
        modal.style.opacity = '1';
    }, 10);
}

function closeNilaiModal() {
    const modal = document.getElementById('nilaiModal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

document.getElementById('nilaiForm').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = {
        mahasiswa_id: currentMhsId,
        matakuliah_id: currentMkId,
        nilai: formData.get('nilai'),
        catatan: formData.get('catatan')
    };

    try {
        const res = await fetch("{{ route('admin.nilai-responsi.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        if (result.success) {
            Swal.fire('Berhasil', result.message, 'success').then(() => location.reload());
        }
    } catch (err) {
        Swal.fire('Error', 'Gagal menyimpan nilai', 'error');
    }
};

async function confirmDeleteNilai(id) {
    const result = await Swal.fire({
        title: 'Hapus nilai?',
        text: 'Data nilai yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        try {
            const res = await fetch(`/admin/nilai-responsi/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });
            const data = await res.json();
            if (data.success) {
                Swal.fire('Terhapus', data.message, 'success').then(() => location.reload());
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
@endpush

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
