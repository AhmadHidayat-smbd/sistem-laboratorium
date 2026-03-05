@extends('layouts.asisten')

@section('title', 'Data Presensi')

@push('styles')
<style>
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 fade-in-up flex-1 w-full">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Data Presensi Mahasiswa</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Rekap kehadiran mahasiswa secara real-time berdasarkan pertemuan.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
           
            <a href="{{ route('asisten.tambah-absensi') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3.5 rounded-2xl shadow-lg shadow-blue-600/20 flex items-center gap-2 transition-all transform hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-wider">
                <i class="ph-fill ph-plus-circle text-xl"></i>
                Tambah Presensi
            </a>
        </div>
    </div>

    <livewire:asisten-attendance-list />

</main>

<div id="modalHapus" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0 overflow-hidden" id="modalContent">
        
        <div class="p-6 border-b border-slate-100 flex items-start gap-4 bg-slate-50/50">
            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Presensi</h3>
                <p class="text-sm font-semibold text-slate-500 mt-0.5" id="modalNamaMahasiswa"></p>
            </div>
        </div>

        <div class="p-6">
            <p class="text-sm text-slate-600 mb-4 font-medium">Pilih pertemuan yang ingin dihapus dari catatan kehadiran:</p>
            
            <div id="daftarPertemuan" class="space-y-2 max-h-56 overflow-y-auto pr-2 custom-scrollbar"></div>
            
            <div class="mt-8 flex gap-3">
                <button onclick="tutupModal()" class="flex-1 px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-bold transition-colors">Batal</button>
                <button onclick="konfirmasiHapus()" class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">Hapus Terpilih</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentMahasiswaId, currentMatakuliahId, currentNamaMahasiswa;

    function bukaModalHapus(mahasiswaId, matakuliahId, namaMahasiswa, pertemuanList) {
        currentMahasiswaId = mahasiswaId;
        currentMatakuliahId = matakuliahId;
        currentNamaMahasiswa = namaMahasiswa;
        document.getElementById('modalNamaMahasiswa').innerText = namaMahasiswa;
        
        const container = document.getElementById('daftarPertemuan');
        container.innerHTML = '';
        
        pertemuanList.forEach(p => {
            const label = document.createElement('label');
            label.className = 'flex items-center justify-between px-4 py-3 bg-white border border-slate-200 hover:border-rose-200 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer group';
            label.innerHTML = `
                <span class="text-slate-700 font-semibold text-sm group-hover:text-rose-700 transition-colors">
                    ${p == 9 ? 'Responsi' : 'Pertemuan ' + p}
                </span>
                <div class="flex items-center h-5">
                    <input type="checkbox" value="${p}" class="w-5 h-5 text-rose-600 border-slate-300 rounded focus:ring-rose-500 cursor-pointer">
                </div>
            `;
            container.appendChild(label);
        });

        const modal = document.getElementById('modalHapus');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            document.getElementById('modalContent').classList.remove('scale-95', 'opacity-0');
            document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function tutupModal() {
        const content = document.getElementById('modalContent');
        content.classList.replace('scale-100', 'scale-95');
        content.classList.replace('opacity-100', 'opacity-0');
        
        setTimeout(() => {
            document.getElementById('modalHapus').classList.replace('flex', 'hidden');
        }, 300);
    }

    async function konfirmasiHapus() {
        const selected = Array.from(document.querySelectorAll('#daftarPertemuan input[type="checkbox"]:checked')).map(cb => cb.value);
        if (selected.length === 0) return alert('Silakan pilih minimal satu pertemuan untuk dihapus.');
        
        if (!confirm('Apakah Anda yakin ingin menghapus data presensi terpilih?')) return;
        
        tutupModal();
        
        for (const p of selected) {
            await fetch('{{ route("asisten.absensi.delete") }}', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ mahasiswa_id: currentMahasiswaId, matakuliah_id: currentMatakuliahId, pertemuan: parseInt(p) })
            });
        }
        location.reload();
    }

    // Global Export Loader Handler (Sama dengan Dosen)
    function handleExport(url) {
        Swal.fire({
            title: 'Mempersiapkan File...',
            html: `
                <div class="flex flex-col items-center gap-4 py-4">
                    <div class="w-20 h-20 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
                    <p class="text-slate-500 font-medium animate-pulse">Sedang mengolah data Excel Anda</p>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                // Redirect to download URL
                window.location.href = url;
                
                // Close alert after delay
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selesai!',
                        text: 'File Excel sedang diunduh.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-2xl border-0 shadow-xl'
                        }
                    });
                }, 3000);
            }
        });
    }
</script>
@endpush