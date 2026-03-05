@extends('layouts.admin')

@section('title', 'Tambah Presensi')
@section('page_title', 'Tambah Presensi')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.absensi') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Data Presensi</span>
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="ph-fill ph-presentation-chart text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Persiapan Sesi Presensi</h2>
                    <p class="text-sm font-medium text-gray-400">Konfigurasi mata kuliah, pertemuan, dan batas waktu scan.</p>
                </div>
            </div>

            <form action="{{ route('admin.absensi.create2') }}" method="GET" class="space-y-8">
                <!-- Mata Kuliah -->
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Pilih Mata Kuliah</label>
                    <div class="relative group">
                        <select name="matakuliah_id" id="matakuliahSelect" required
                                class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($matakuliah as $mk)
                                @php $jadwal = $mk->jadwal->first(); @endphp
                                <option value="{{ $mk->id }}"
                                        data-jammulai="{{ $jadwal->jam_mulai ?? '' }}"
                                        data-jamselesai="{{ $jadwal->jam_selesai ?? '' }}"
                                        data-hari="{{ $jadwal->hari ?? '' }}">
                                    {{ $mk->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="ph-bold ph-book-open text-xl"></i>
                        </div>
                    </div>
                    
                    <!-- Dynamic Jadwal Info -->
                    <div id="jadwalInfo" class="hidden animate-fade-in p-5 rounded-2xl bg-indigo-50 border border-indigo-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                                <i class="ph-bold ph-clock text-xl"></i>
                            </div>
                            <div>
                                <p id="jadwalText" class="text-indigo-900 font-bold text-sm leading-snug"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Pertemuan -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Pertemuan Ke-</label>
                        <div class="relative group">
                            <select name="pertemuan" required
                                    class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700">
                                <option value="">-- Pilih Pertemuan --</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Pertemuan Ke-{{ $i }}</option>
                                @endfor
                                <option value="9">Sesi Responsi</option>
                            </select>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-number-circle-one text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Durasi -->
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] ml-2">Durasi Scan (Menit)</label>
                        <div class="relative group">
                            <input type="number" name="duration" min="1" max="180" required
                                   class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white outline-none transition-all duration-300 font-bold text-gray-700"
                                   placeholder="Contoh: 30">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                <i class="ph-bold ph-timer text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white py-5 rounded-[1.5rem] font-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center justify-center gap-3 group">
                        <i class="ph-bold ph-qr-code text-2xl group-hover:scale-110 transition-transform"></i>
                        <span>Buka Halaman Pemindaian RFID</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hariIndo = {
        "monday": "Senin",
        "tuesday": "Selasa",
        "wednesday": "Rabu",
        "thursday": "Kamis",
        "friday": "Jumat",
        "saturday": "Sabtu",
        "sunday": "Minggu"
    };

    document.getElementById("matakuliahSelect").addEventListener("change", function () {
        let opt = this.selectedOptions[0];
        let jm = opt.getAttribute("data-jammulai");
        let js = opt.getAttribute("data-jamselesai");
        let hari = opt.getAttribute("data-hari");
        
        const box = document.getElementById("jadwalInfo");
        const text = document.getElementById("jadwalText");

        if (jm && js && hari) {
            box.classList.remove('hidden');
            let normalizedHari = hari.toLowerCase().trim();
            let hariTampil = hariIndo[normalizedHari] ?? hari;
            text.innerHTML = `Terjadwal setiap hari <span class="text-indigo-600 font-black">${hariTampil}</span> pada pukul <span class="text-indigo-600 font-black">${jm} - ${js}</span> WIB`;
        } else {
            box.classList.add('hidden');
        }
    });
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
