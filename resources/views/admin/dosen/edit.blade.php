@extends('layouts.admin')

@section('title', 'Edit Dosen')
@section('page_title', 'Edit Data Dosen')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.dosen') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Daftar Dosen</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-pencil-simple text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Form Edit Dosen</h2>
                    <p class="text-sm font-medium text-gray-400">Perbarui informasi dosen {{ $dosen->nama }}</p>
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-rose-50 border-2 border-rose-100 p-6 rounded-2xl mb-8 animate-shake">
                <div class="flex gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-2xl"></i>
                    <div>
                        <h4 class="font-black text-rose-800 mb-1 tracking-tight">Terjadi kesalahan input:</h4>
                        <ul class="text-sm text-rose-600 font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Nama Lengkap Dosen</label>
                        <div class="relative group">
                            <input type="text" name="nama" value="{{ old('nama', $dosen->nama) }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: Dr. Ahmad Fauzi, M.Kom">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-amber-600 transition-colors">
                                <i class="ph-bold ph-user text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Alamat Email</label>
                        <div class="relative group">
                            <input type="email" name="email" value="{{ old('email', $dosen->email) }}" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="dosen@itlabs.ac.id">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-amber-600 transition-colors">
                                <i class="ph-bold ph-envelope-simple text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Password -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Kata Sandi Baru</label>
                        <div class="relative group">
                            <input type="password" name="password" 
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Kosongkan jika tidak diubah">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-amber-600 transition-colors">
                                <i class="ph-bold ph-lock-key text-xl"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 px-2 flex items-center gap-1">
                            <i class="ph-bold ph-info"></i>
                            Hanya diisi jika ingin merubah password saat ini.
                        </p>
                    </div>

                    <!-- RFID UID -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">RFID UID <span class="lowercase tracking-normal font-medium text-gray-400">(Opsional)</span></label>
                        <div class="relative group">
                            <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $dosen->rfid_uid) }}" maxlength="50"
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: 1234567890AB">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-amber-600 transition-colors">
                                <i class="ph-bold ph-identification-card text-xl"></i>
                            </div>
                        </div>
                        @if($dosen->rfid_uid)
                            <p class="text-[10px] font-bold text-green-600 px-2 flex items-center gap-1">
                                <i class="ph-bold ph-check-circle"></i>
                                UID Saat ini: <span class="font-mono">{{ $dosen->rfid_uid }}</span>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Mata Kuliah yang Diampu -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Mata Kuliah Yang Diampu Saat Ini</label>
                    <div class="bg-gray-50 rounded-[1.5rem] p-6 border border-gray-100">
                        @if($dosen->matakuliah->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($dosen->matakuliah as $mk)
                                    <div class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl border border-gray-200 shadow-sm">
                                        <div class="bg-blue-50 text-blue-600 p-2 rounded-lg">
                                            <i class="ph-fill ph-book-bookmark"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-gray-500">{{ $mk->kode }}</p>
                                            <p class="text-sm font-bold text-gray-800">{{ $mk->nama }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ph-fill ph-books-light text-4xl text-gray-300 mb-2 block"></i>
                                <p class="text-sm text-gray-500 font-medium">Belum ada mata kuliah yang sedang diampu.</p>
                            </div>
                        @endif
                    </div>
                </div>


                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-gray-900 py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-yellow-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-check-circle text-2xl group-hover:scale-110 transition-transform"></i>
                        <span>Update Data Dosen</span>
                    </button>
                    <a href="{{ route('admin.dosen') }}" class="px-10 py-5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.5rem] font-bold transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
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
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out;
    }
</style>
@endpush
