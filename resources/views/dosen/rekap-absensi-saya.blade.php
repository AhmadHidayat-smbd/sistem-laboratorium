@extends('layouts.dosen')

@section('title', 'Rekap Kehadiran Saya')
@section('page_title', 'Kehadiran Saya')
@section('page_subtitle', 'Riwayat data mengajar Anda.')

@section('content')
<div class="p-6 lg:p-8 space-y-8">
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in">
        <form action="{{ route('dosen.absensi-saya') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="tahun_ajaran" class="block text-sm font-bold text-gray-700 mb-2">Filter Tahun Ajaran / Semester</label>
                <div class="relative">
                    <select name="tahun_ajaran" id="tahun_ajaran" onchange="this.form.submit()" 
                            class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none font-semibold text-gray-700">
                        <option value="">📅 Semua Semester</option>
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
            <a href="{{ route('dosen.absensi-saya') }}" class="h-[46px] px-6 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-bold transition-all">
                <i class="ph-bold ph-arrow-counter-clockwise mr-2"></i> Reset
            </a>
            

        </form>

        @if($tahunAjaran)
            @php
                $taParts = explode('-', $tahunAjaran);
                $taLabel = ($taParts[1] ?? '') == '1' ? 'Ganjil' : 'Genap';
            @endphp
            <div class="mt-4 flex items-center gap-2 text-sm">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100 uppercase tracking-wide">
                    <i class="ph-fill ph-calendar"></i>
                    {{ $tahunAjaran }} • Semester {{ $taLabel }}
                </span>
            </div>
        @endif
    </div>

    <!-- Per Mata Kuliah Sections -->
    @php $anyData = false; @endphp
    @foreach($matakuliah as $mk)
        @php
            $rows = $absensiDosen[$mk->id] ?? collect();
        @endphp

        @if($rows->isNotEmpty())
            @php $anyData = true; @endphp
            <div class="space-y-4 animate-fade-in">
                <!-- Course Header -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg text-white">
                         <span class="font-black text-xl">{{ substr($mk->nama, 0, 1) }}</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">{{ $mk->nama }}</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-widest">{{ $mk->kode }}</span>
                            @if($mk->tahun_ajaran)
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Semester {{ explode('-', $mk->tahun_ajaran)[1] == '1' ? 'Ganjil' : 'Genap' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="h-px flex-1 bg-gray-100 ml-4 mx-4"></div>
                    <button onclick="handleExport('{{ route('dosen.absensi-saya.export', ['matakuliah_id' => $mk->id]) }}')" 
                            class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-100 rounded-xl text-xs font-bold transition-all shadow-sm whitespace-nowrap">
                        <i class="ph-fill ph-file-xls text-lg"></i>
                        <span>Export Excel</span>
                    </button>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                                    <th class="px-8 py-4 w-20">Pertemuan</th>
                                    <th class="px-8 py-4">Hari & Tanggal</th>
                                    <th class="px-8 py-4">Materi yang disampaikan</th>
                                    <th class="px-8 py-4 text-center">Status</th>
                                    <th class="px-8 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($rows as $absen)
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-8 py-6">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gray-100 text-gray-600 font-bold group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                                {{ $absen->pertemuan }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l') }}</div>
                                            <div class="text-xs font-semibold text-gray-400">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <p class="text-sm font-medium text-gray-700 leading-relaxed max-w-md">
                                                @if($absen->materi)
                                                    {{ $absen->materi }}
                                                @else
                                                    <span class="text-gray-300 italic">Belum mengisi materi...</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            @if($absen->status == 'Hadir')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wide border border-emerald-100">
                                                    <i class="ph-fill ph-check-circle"></i> Hadir
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-black uppercase tracking-wide border border-rose-100">
                                                    <i class="ph-fill ph-x-circle"></i> {{ $absen->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <button onclick="editMateri({{ $absen->id }}, '{{ addslashes($absen->materi) }}')" 
                                                    class="w-10 h-10 bg-gray-50 text-gray-400 hover:bg-blue-600 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm group-hover:scale-110 active:scale-95" 
                                                    title="Edit Materi">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </button>
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

    @if(!$anyData)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-20 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ph-fill ph-calendar-blank text-5xl text-gray-200"></i>
            </div>
            <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest">Belum Ada Riwayat Mengajar</h3>
            <p class="text-gray-400 font-medium">Data kehadiran Anda akan muncul di sini setelah Anda melakukan presensi mengajar.</p>
        </div>
    @endif
</div>

<script>
    function editMateri(id, currentMateri) {
        Swal.fire({
            title: 'Edit Materi Pertemuan',
            input: 'textarea',
            inputLabel: 'Apa yang Anda sampaikan pada pertemuan ini?',
            inputValue: currentMateri,
            inputPlaceholder: 'Tuliskan rincian materi...',
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2.5rem]',
                confirmButton: 'bg-blue-600 rounded-2xl font-bold px-8 py-4',
                cancelButton: 'bg-gray-100 text-gray-600 rounded-2xl font-bold px-8 py-4'
            },
            showLoaderOnConfirm: true,
            preConfirm: (materi) => {
                return fetch('{{ route("dosen.absensi-saya.update-materi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id, materi: materi })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(`Gagal memperbarui: ${error}`)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Materi pengajaran telah diperbarui.',
                    icon: 'success',
                    customClass: {
                        popup: 'rounded-[2.5rem]',
                        confirmButton: 'bg-emerald-600 rounded-2xl font-bold px-8 py-4'
                    }
                }).then(() => {
                    location.reload();
                });
            }
        })
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }
</style>

<script>
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
