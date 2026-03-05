@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Data Mahasiswa')

@section('content')
<!-- Header Section -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 animate-fade-in">
    <div class="flex-1">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase">Data Mahasiswa</h2>
        <p class="text-gray-500 font-medium">Kelola data mahasiswa, registrasi RFID, dan informasi akademik praktikum.</p>
    </div>
    
    <div class="flex flex-col md:flex-row items-center gap-4">
        <!-- Search Form (Non-Livewire) -->
        <form action="{{ route('admin.mahasiswa') }}" method="GET" class="relative group w-full md:w-80">
            <input name="search" 
                   type="text" 
                   value="{{ $search }}"
                   class="w-full pl-12 pr-6 py-4 bg-white border border-gray-100 rounded-[1.5rem] shadow-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-700"
                   placeholder="Cari Nama atau NIM...">
            <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
                <i class="ph-bold ph-magnifying-glass text-xl"></i>
            </button>
        </form>

        <!-- Import Excel Button -->
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                class="w-full md:w-auto flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-emerald-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-file-arrow-up text-xl"></i>
            <span>Import Excel</span>
        </button>

        <a href="{{ route('admin.mahasiswa.create') }}" class="w-full md:w-auto flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[1.5rem] font-bold shadow-lg shadow-blue-100 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i>
            <span>Tambah Mahasiswa</span>
        </a>
    </div>
</div>

<!-- Import Errors Display -->
@if(session('import_errors'))
<div class="mb-6 bg-amber-50 border-2 border-amber-200 p-6 rounded-[2rem] animate-fade-in">
    <div class="flex items-start gap-3 mb-4">
        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="ph-bold ph-warning text-xl"></i>
        </div>
        <div>
            <h4 class="font-black text-amber-800 tracking-tight">Detail Hasil Import</h4>
            <p class="text-xs font-medium text-amber-600 mt-0.5">Beberapa baris tidak berhasil diproses:</p>
        </div>
    </div>
    <div class="max-h-48 overflow-y-auto space-y-1.5 ml-[52px]">
        @foreach(session('import_errors') as $err)
            <div class="flex items-start gap-2 text-sm text-amber-700 font-medium">
                <i class="ph-bold ph-dot text-xs mt-1 flex-shrink-0"></i>
                <span>{{ $err }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">No</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">Mahasiswa</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">NIM</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px]">RFID UID</th>
                        <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[2px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($mahasiswa as $mhs)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                         <td class="px-8 py-6">
                        <span class="text-sm font-bold text-gray-400">{{ $loop->iteration }}</span>
                    </td>
                        <td class="px-8 py-6">
                            <div>
                                <span class="block font-black text-gray-800 tracking-tight">{{ $mhs->nama }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mahasiswa Aktif</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-4 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-black tracking-tight border border-blue-100">
                                {{ $mhs->nim }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($mhs->rfid_uid)
                                <div class="flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100 w-fit">
                                    <i class="ph-bold ph-identification-card text-lg"></i>
                                    <span class="text-xs font-black tracking-widest">{{ $mhs->rfid_uid }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100 w-fit">
                                    <i class="ph-bold ph-warning-circle text-lg"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">No RFID Data</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="w-11 h-11 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all duration-300 shadow-sm border border-amber-100 hover:border-amber-600">
                                    <i class="ph-bold ph-pencil-simple text-xl"></i>
                                </a>
                                
                                <form id="delete-form-{{ $mhs->id }}" action="{{ route('admin.mahasiswa.delete', $mhs->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-form-{{ $mhs->id }}')" class="w-11 h-11 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm border border-rose-100 hover:border-rose-600">
                                        <i class="ph-bold ph-trash text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-300 shadow-inner">
                                    <i class="ph-bold ph-magnifying-glass text-4xl"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-black text-gray-800 tracking-tight">Tidak Ada Hasil</p>
                                    <p class="text-sm font-medium text-gray-400 mt-1">Gunakan kata kunci pencarian yang berbeda.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination & Per Page Section -->
        <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/30 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            
            <!-- Per Page Dropdown -->
            <form action="{{ route('admin.mahasiswa') }}" method="GET" class="flex items-center gap-3">
                @if($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <span class="text-sm font-bold text-gray-500">Tampilkan</span>
                <div class="relative group">
                    <select name="per_page" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-sm text-gray-700 cursor-pointer">
                        <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 Data</option>
                        <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25 Data</option>
                        <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 Data</option>
                        <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100 Data</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="ph-bold ph-caret-down"></i>
                    </div>
                </div>
            </form>

            @if($mahasiswa->hasPages())
            <div class="flex-1 w-full overflow-x-auto">
                {{ $mahasiswa->appends(['search' => $search, 'per_page' => $perPage ?? 10])->links('vendor.pagination.custom-tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ═══════════ IMPORT MODAL ═══════════ -->
<div id="importModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-8 py-7 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                        <i class="ph-fill ph-file-xls text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black tracking-tight">Import Data Mahasiswa</h3>
                        <p class="text-emerald-100 text-xs font-semibold mt-0.5">Upload file Excel (.xlsx, .xls, .csv)</p>
                    </div>
                </div>
            </div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        <!-- Body -->
        <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <!-- Format Info -->
            <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl mb-6">
                <h4 class="text-xs font-black text-blue-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="ph-fill ph-info text-base"></i> Format File Excel
                </h4>
                <div class="space-y-2">
                    <p class="text-xs text-blue-600 font-medium leading-relaxed">
                        File harus memiliki <strong>header baris pertama</strong> dengan kolom:
                    </p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-3 py-1.5 bg-white text-blue-700 rounded-lg text-[11px] font-black border border-blue-200 shadow-sm">nama</span>
                        <span class="px-3 py-1.5 bg-white text-blue-700 rounded-lg text-[11px] font-black border border-blue-200 shadow-sm">nim</span>
                        <span class="px-3 py-1.5 bg-white text-blue-700 rounded-lg text-[11px] font-black border border-blue-200 shadow-sm">rfid_uid</span>
                    </div>
                    <p class="text-[10px] text-blue-500 font-medium mt-2">
                        ⚠️ NIM harus unik. Data dengan NIM duplikat (di file maupun database) akan otomatis di-skip.
                    </p>
                    <p class="text-[10px] text-blue-500 font-medium">
                        🔑 Password default = NIM mahasiswa.
                    </p>
                </div>
            </div>

            <!-- File Upload -->
            <div class="mb-6">
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2 mb-3">Pilih File Excel</label>
                <div class="relative">
                    <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" required
                           class="hidden"
                           onchange="updateFileName(this)">
                    <label for="fileInput" 
                           class="flex items-center gap-4 w-full p-5 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/30 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-gray-400 group-hover:text-emerald-500 transition-colors shadow-sm border border-gray-100">
                            <i class="ph-bold ph-cloud-arrow-up text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p id="fileName" class="font-bold text-sm text-gray-600 truncate">Klik untuk memilih file...</p>
                            <p class="text-[10px] font-semibold text-gray-400 mt-0.5">Maksimal 5MB • .xlsx, .xls, .csv</p>
                        </div>
                    </label>
                </div>
            </div>

            @if ($errors->has('file'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-sm font-bold text-rose-600 flex items-center gap-2">
                <i class="ph-bold ph-warning-circle text-lg"></i>
                {{ $errors->first('file') }}
            </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-[1.5rem] font-black transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-2 active:scale-95">
                    <i class="ph-bold ph-upload-simple text-lg"></i>
                    Import Sekarang
                </button>
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-8 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

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

@push('scripts')
<script>
    function updateFileName(input) {
        const label = document.getElementById('fileName');
        if (input.files.length > 0) {
            const file = input.files[0];
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            label.textContent = `${file.name} (${sizeMB} MB)`;
            label.classList.remove('text-gray-600');
            label.classList.add('text-emerald-700');
        } else {
            label.textContent = 'Klik untuk memilih file...';
            label.classList.remove('text-emerald-700');
            label.classList.add('text-gray-600');
        }
    }

    // Auto open modal if file validation error exists
    @if($errors->has('file'))
        document.getElementById('importModal').classList.remove('hidden');
    @endif
</script>
@endpush