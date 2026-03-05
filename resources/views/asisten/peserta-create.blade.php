@extends('layouts.asisten')

@section('title', 'Tambah Peserta - ' . $matakuliah->nama)

@push('styles')
<style>
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 fade-in-up flex-1 w-full">
    
    <!-- Navigation & Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('asisten.peserta') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm">
            <i class="ph-bold ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Peserta</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-sm font-semibold text-slate-500">Mata Kuliah:</span>
                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md uppercase tracking-wider">{{ $matakuliah->nama }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm mb-6">
        <div class="text-emerald-500 mt-0.5">
            <i class="ph-fill ph-check-circle text-xl"></i>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-emerald-800">Berhasil!</h3>
            <p class="text-sm text-emerald-600 mt-1">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3 shadow-sm mb-6">
        <div class="text-rose-500 mt-0.5">
            <i class="ph-fill ph-warning-circle text-xl"></i>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-rose-800">Perhatian!</h3>
            <p class="text-sm text-rose-600 mt-1">{{ session('error') }}</p>
        </div>
    </div>
    @endif
    
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden p-6 md:p-8">
        
        <form method="POST" action="{{ route('asisten.peserta.store', $matakuliah->id) }}" class="space-y-6">
            @csrf

            <!-- Search and Controls -->
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    </div>
                    <input type="text" id="searchMahasiswa" placeholder="Cari Nama atau NIM..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-semibold text-slate-700">
                </div>
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-100 flex items-center gap-2">
                        <span class="text-xs font-bold text-blue-800">Terpilih:</span>
                        <span id="selectedCount" class="text-sm font-black text-blue-600 bg-white px-2 py-0.5 rounded-md shadow-sm">0</span>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" id="selectAllBtn" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5">
                            <i class="ph-bold ph-check-square"></i> Semua
                        </button>
                        <button type="button" id="deselectAllBtn" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-rose-600 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5">
                            <i class="ph-bold ph-x-square"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mahasiswa List -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50">
                <div id="mahasiswaList" class="max-h-[400px] overflow-y-auto custom-scrollbar divide-y divide-slate-100">
                    @forelse($mahasiswa as $m)
                    <label class="checkbox-item group flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-white transition-all w-full"
                           data-nama="{{ strtolower($m->nama) }}" data-nim="{{ strtolower($m->nim) }}">
                        
                        <div class="relative flex items-center">
                            <input type="checkbox" name="mahasiswa_ids[]" value="{{ $m->id }}"
                                   class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500/30 cursor-pointer mahasiswa-checkbox transition-all shadow-sm">
                        </div>
                        
                        <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <p class="font-bold text-slate-800 text-sm">{{ $m->nama }}</p>
                                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">{{ $m->nim }}</p>
                            </div>
                        </div>
                    </label>
                    @empty
                    <div class="p-12 text-center text-slate-400">
                        <i class="ph-fill ph-users-three text-4xl mb-3"></i>
                        <p class="font-bold text-sm">Tidak ada mahasiswa yang tersedia untuk ditambahkan.</p>
                    </div>
                    @endforelse
                </div>

                <div id="noResults" class="hidden text-center py-16 text-slate-400">
                    <i class="ph-fill ph-magnifying-glass text-4xl mb-3"></i>
                    <p class="font-bold text-sm">Pencarian mahasiswa tidak ditemukan.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-sm shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                    <i class="ph-bold ph-plus-circle text-lg"></i>
                    Daftarkan Terpilih
                </button>
            </div>
        </form>
    </div>
</main>
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
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateSelectedCount();
            const item = checkbox.closest('.checkbox-item');
            if (checkbox.checked) {
                item.classList.add('bg-white', 'ring-1', 'ring-blue-500/20');
            } else {
                item.classList.remove('bg-white', 'ring-1', 'ring-blue-500/20');
            }
        });
    });

    selectAllBtn.addEventListener('click', () => {
        checkboxes.forEach(cb => {
            if (cb.closest('.checkbox-item').style.display !== 'none') {
                cb.checked = true;
                cb.closest('.checkbox-item').classList.add('bg-white', 'ring-1', 'ring-blue-500/20');
            }
        });
        updateSelectedCount();
    });

    deselectAllBtn.addEventListener('click', () => {
        checkboxes.forEach(cb => {
            cb.checked = false;
            cb.closest('.checkbox-item').classList.remove('bg-white', 'ring-1', 'ring-blue-500/20');
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
