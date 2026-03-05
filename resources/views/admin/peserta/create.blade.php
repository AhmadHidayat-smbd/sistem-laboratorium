@extends('layouts.admin')

@section('title', 'Tambah Peserta ')
@section('page_title', 'Tambah Peserta')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.peserta') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Kelola Peserta</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <i class="ph-fill ph-users-three text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight"> Registrasi Peserta</h2>
                        <p class="text-sm font-medium text-gray-400">Daftarkan banyak mahasiswa sekaligus ke mata kuliah.</p>
                    </div>
                </div>
                <div class="bg-indigo-50 px-6 py-3 rounded-2xl">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest block mb-1">Mata Kuliah Target</span>
                    <span class="text-indigo-600 font-bold font-mono">{{ $matakuliah->kode }} - {{ $matakuliah->nama }}</span>
                </div>
            </div>

            @if(session('error'))
            <div class="bg-rose-50 border-2 border-rose-100 p-6 rounded-2xl mb-8 animate-shake">
                <div class="flex gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-2xl"></i>
                    <p class="font-bold text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.peserta.store', $matakuliah->id) }}" class="space-y-8">
                @csrf

                <!-- Filter & Controls -->
                <div class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 w-full space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Cari Nama / NIM</label>
                        <div class="relative group">
                            <input type="text" id="searchMahasiswa" placeholder="Ketik untuk memfilter daftar..."
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-magnifying-glass text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="button" id="selectAllBtn" class="flex-1 md:flex-none px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-checks"></i>
                            <span>Semua</span>
                        </button>
                        <button type="button" id="deselectAllBtn" class="flex-1 md:flex-none px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl font-bold transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-x"></i>
                            <span>Batal</span>
                        </button>
                    </div>
                </div>

                <!-- Selection Status -->
                <div class="bg-emerald-50/50 border border-emerald-100 p-6 rounded-[2rem] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                            <i class="ph-bold ph-selection-all text-xl"></i>
                        </div>
                        <p class="font-bold text-emerald-900">
                            <span id="selectedCount" class="text-2xl font-black mr-1 text-emerald-600">0</span>
                            Mahasiswa Terpilih
                        </p>
                    </div>
                    <div class="text-[10px] font-black text-emerald-400 uppercase tracking-widest hidden md:block">
                        Total yang akan didaftarkan
                    </div>
                </div>

                <!-- List Checkbox -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Daftar Mahasiswa Tersedia</label>
                    <div class="border border-gray-100 rounded-[2rem] overflow-hidden bg-gray-50/30">
                        <div id="mahasiswaList" class="max-h-[500px] overflow-y-auto custom-scrollbar divide-y divide-gray-50">
                            @forelse($mahasiswa as $m)
                                <label class="checkbox-item group flex items-center gap-4 px-6 py-5 cursor-pointer hover:bg-white transition-all"
                                       data-nama="{{ strtolower($m->nama) }}" data-nim="{{ strtolower($m->nim) }}">
                                    <input type="checkbox" name="mahasiswa_ids[]" value="{{ $m->id }}"
                                           class="w-6 h-6 rounded-lg border-2 border-gray-200 text-blue-600 focus:ring-4 focus:ring-blue-500/10 cursor-pointer mahasiswa-checkbox">
                                    
                                    <div class="flex-1 flex items-center gap-4">
                                        <div>
                                            <p class="font-black text-gray-800 leading-tight">{{ $m->nama }}</p>
                                            <p class="text-xs font-bold text-gray-400 font-mono tracking-tighter">{{ $m->nim }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="ph-bold ph-plus-circle text-blue-400 text-xl"></i>
                                    </div>
                                </label>
                            @empty
                                <div class="px-6 py-12 text-center text-gray-400">
                                    <i class="ph-bold ph-users-slash text-4xl mb-4 opacity-30"></i>
                                    <p class="font-bold">Tidak ada mahasiswa yang tersedia untuk mata kuliah ini</p>
                                </div>
                            @endforelse
                        </div>

                        <div id="noResults" class="hidden text-center py-20 text-gray-400">
                            <i class="ph-bold ph-magnifying-glass text-4xl mb-4 opacity-30"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">Pencarian tidak ditemukan</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-user-plus text-2xl group-hover:rotate-12 transition-transform"></i>
                        <span>Daftarkan Mahasiswa Terpilih</span>
                    </button>
                    <a href="{{ route('admin.peserta') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('searchMahasiswa');
    const checkboxItems = document.querySelectorAll('.checkbox-item');
    const checkboxes = document.querySelectorAll('.mahasiswa-checkbox');
    const selectedCountEl = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const noResults = document.getElementById('noResults');
    const mahasiswaList = document.getElementById('mahasiswaList');

    function updateSelectedCount() {
        const count = document.querySelectorAll('.mahasiswa-checkbox:checked').length;
        selectedCountEl.textContent = count;
        selectedCountEl.parentElement.classList.add('scale-105');
        setTimeout(() => selectedCountEl.parentElement.classList.remove('scale-105'), 200);
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateSelectedCount();
            const item = checkbox.closest('.checkbox-item');
            if (checkbox.checked) {
                item.classList.add('bg-blue-50/50', 'ring-2', 'ring-blue-100');
            } else {
                item.classList.remove('bg-blue-50/50', 'ring-2', 'ring-blue-100');
            }
        });
    });

    selectAllBtn.addEventListener('click', () => {
        const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
            return cb.closest('.checkbox-item').style.display !== 'none';
        });
        visibleCheckboxes.forEach(cb => {
            cb.checked = true;
            cb.closest('.checkbox-item').classList.add('bg-blue-50/50', 'ring-2', 'ring-blue-100');
        });
        updateSelectedCount();
    });

    deselectAllBtn.addEventListener('click', () => {
        checkboxes.forEach(cb => {
            cb.checked = false;
            cb.closest('.checkbox-item').classList.remove('bg-blue-50/50', 'ring-2', 'ring-blue-100');
        });
        updateSelectedCount();
    });

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        let visibleCount = 0;

        checkboxItems.forEach(item => {
            const nama = item.getAttribute('data-nama');
            const nim = item.getAttribute('data-nim');

            if (nama.includes(keyword) || nim.includes(keyword)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            mahasiswaList.classList.add('hidden');
            noResults.classList.remove('hidden');
        } else {
            mahasiswaList.classList.remove('hidden');
            noResults.classList.add('hidden');
        }
    });

    updateSelectedCount();
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
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
</style>
@endpush
