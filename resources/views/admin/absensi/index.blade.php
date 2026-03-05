@extends('layouts.admin')

@section('title', 'Data Absensi')
@section('page_title', 'Absensi Mahasiswa')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
    <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Data Absensi Mahasiswa</h2>
        <p class="text-gray-500 font-medium">Rekap kehadiran mahasiswa per pertemuan (P1 - P8 + Responsi).</p>
    </div>

    <div class="flex flex-wrap items-center gap-4">
        <button onclick="handleExport('{{ route('admin.absensi.export.excel.all') }}')" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-emerald-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-file-xls text-xl"></i>
            <span>Export Excel</span>
        </button>

        <a href="{{ route('admin.absensi.create') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i>
            <span>Tambah Presensi</span>
        </a>
    </div>
</div>

    <livewire:admin-attendance-list />

<!-- Legend -->
<div class="flex items-center gap-6 mb-12 animate-fade-in" style="animation-delay: 0.3s">
    <div class="flex items-center gap-2">
        <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hadir</span>
    </div>
    <div class="flex items-center gap-2">
        <div class="w-2.5 h-2.5 bg-rose-500 rounded-full"></div>
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Titip Absen</span>
    </div>
    <div class="flex items-center gap-2">
        <div class="w-2.5 h-2.5 bg-gray-200 rounded-full"></div>
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum Ada</span>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center z-[100] p-4 backdrop-blur-md transition-all duration-500">
    <div class="modal-card bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">
        <div class="bg-gray-900 p-8 text-white relative">
            <h3 class="text-2xl font-black uppercase tracking-tight">Hapus Presensi</h3>
            <p class="text-gray-400 text-sm font-medium">Pilih pertemuan yang datanya akan dihapus.</p>
        </div>
        <div class="p-8">
            <div id="daftarPertemuan" class="grid grid-cols-2 gap-3 mb-8">
                <!-- Data injected via JS -->
            </div>
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" class="flex-1 px-6 py-4 rounded-2xl font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">Batal</button>
                <button onclick="confirmBatchDelete()" id="btnConfirmDelete" class="flex-1 px-6 py-4 rounded-2xl font-black bg-rose-600 text-white hover:bg-rose-700 transition-all shadow-lg shadow-rose-100">Hapus Terpilih</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentMhsId = null;
let currentMkId = null;

function openDeleteModal(mhsId, mkId, mhsNama, mkNama, pertemuanAda) {
    currentMhsId = mhsId;
    currentMkId = mkId;
    const container = document.getElementById('daftarPertemuan');
    container.innerHTML = '';
    
    if (pertemuanAda.length === 0) {
        container.innerHTML = `<p class="col-span-2 text-center py-4 text-gray-400 font-bold uppercase tracking-widest text-xs">Tidak ada data</p>`;
    } else {
        pertemuanAda.sort((a,b) => a-b).forEach(p => {
            const label = p == 9 ? 'Responsi' : `P${p}`;
            container.innerHTML += `
                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-gray-50 cursor-pointer hover:border-blue-200 transition-all group">
                    <span class="font-black text-gray-700">${label}</span>
                    <input type="checkbox" value="${p}" class="w-5 h-5 rounded-lg border-2 border-gray-200 text-blue-600 focus:ring-4 focus:ring-blue-500/10">
                </label>
            `;
        });
    }

    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('flex');
        modal.style.opacity = '1';
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

async function confirmBatchDelete() {
    const selected = Array.from(document.querySelectorAll('#daftarPertemuan input[type="checkbox"]:checked')).map(cb => cb.value);
    if (selected.length === 0) return Swal.fire('Error', 'Pilih minimal satu pertemuan', 'error');

    const result = await Swal.fire({
        title: 'Hapus data?',
        text: `Anda akan menghapus ${selected.length} data presensi.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-[2rem] border-0',
            confirmButton: 'px-8 py-4 rounded-xl font-bold bg-rose-600 text-white hover:bg-rose-700 transition-all mx-2',
            cancelButton: 'px-8 py-4 rounded-xl font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all mx-2'
        },
        buttonsStyling: false
    });

    if (result.isConfirmed) {
        const btn = document.getElementById('btnConfirmDelete');
        btn.disabled = true;
        btn.innerText = 'Menghapus...';

        try {
            for (const p of selected) {
                await fetch("{{ route('admin.absensi.delete.pertemuan') }}", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        mahasiswa_id: currentMhsId,
                        matakuliah_id: currentMkId,
                        pertemuan: p
                    })
                });
            }
            location.reload();
        } catch (err) {
            Swal.fire('Error', 'Gagal menghapus data', 'error');
            btn.disabled = false;
            btn.innerText = 'Hapus Terpilih';
        }
    }
}

// Global Export Loader Handler
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
            // Trigger download after showing loader
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