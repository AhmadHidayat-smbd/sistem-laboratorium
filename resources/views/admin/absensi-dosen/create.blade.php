@extends('layouts.admin')

@section('title', 'Tambah Absensi Dosen')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in p-6 lg:p-8">
    <!-- Back Button -->
    <a href="{{ route('admin.absensi-dosen') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold mb-8 transition-colors group">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Daftar Absensi Dosen</span>
    </a>

    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 mb-1 tracking-tight">Tambah Absensi Dosen</h1>
            <p class="text-gray-500 font-medium">Scan RFID atau masukkan email dosen untuk mencatat kehadiran</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Form -->
        <div class="space-y-6">
            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="ph-fill ph-clipboard-text text-2xl"></i>
                        Informasi Pertemuan
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Mata Kuliah -->
                    <div>
                        <label for="matakuliah_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mata Kuliah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="matakuliah_id" 
                                    name="matakuliah_id" 
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 appearance-none bg-white">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($matakuliah as $mk)
                                    <option value="{{ $mk->id }}" data-dosen="{{ $mk->dosen->nama ?? 'Belum ada dosen' }}">
                                        {{ $mk->nama }}
                                        @if($mk->dosen)
                                            ({{ $mk->dosen->nama }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <i class="ph-fill ph-book-bookmark absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400"></i>
                            <i class="ph-fill ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Pertemuan -->
                    <div>
                        <label for="pertemuan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pertemuan Ke- <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="pertemuan" 
                                    name="pertemuan" 
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 appearance-none bg-white">
                                <option value="">-- Pilih Pertemuan --</option>
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">Pertemuan {{ $i }}</option>
                                @endfor
                            </select>
                            <i class="ph-fill ph-hash absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400"></i>
                            <i class="ph-fill ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Materi -->
                    <div>
                        <label for="materi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Materi Perkuliahan <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="materi" 
                                   name="materi" 
                                   placeholder="Contoh: Pengenalan Database"
                                   class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                            <i class="ph-fill ph-notebook absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Status Kehadiran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Status Kehadiran <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="Hadir" class="peer hidden" checked>
                                <div class="p-3 text-center border-2 border-gray-100 rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition-all">
                                    <i class="ph-fill ph-check-circle text-xl block mb-1 text-gray-400 peer-checked:text-green-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-green-700">Hadir</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="Online" class="peer hidden">
                                <div class="p-3 text-center border-2 border-gray-100 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all">
                                    <i class="ph-fill ph-globe text-xl block mb-1 text-gray-400 peer-checked:text-blue-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-blue-700">Online</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="Digantikan Asisten" class="peer hidden">
                                <div class="p-3 text-center border-2 border-gray-100 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:bg-gray-50 transition-all">
                                    <i class="ph-fill ph-users text-xl block mb-1 text-gray-400 peer-checked:text-amber-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-amber-700">Asisten</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex gap-3">
                    <i class="ph-fill ph-info text-2xl text-blue-600 flex-shrink-0"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Cara Menggunakan:</p>
                        <ol class="list-decimal list-inside space-y-1 text-blue-700">
                            <li>Pilih mata kuliah dan pertemuan</li>
                            <li>Isi materi (opsional)</li>
                            <li>Tap kartu RFID dosen atau ketik email</li>
                            <li>Sistem akan otomatis validasi dan simpan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: RFID Scanner -->
        <div class="space-y-6">
            <!-- Scanner Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="ph-fill ph-identification-card text-2xl"></i>
                        Scan RFID / Email Dosen
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- RFID Input -->
                    <div>
                        <label for="identifier" class="block text-sm font-semibold text-gray-700 mb-2">
                            RFID UID atau Email Dosen <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="identifier" 
                                   name="identifier" 
                                   placeholder="Tap RFID atau ketik email dosen..."
                                   autofocus
                                   class="w-full pl-12 pr-4 py-4 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-lg font-mono">
                            <i class="ph-fill ph-scan absolute left-4 top-1/2 -translate-y-1/2 text-xl text-purple-400"></i>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 flex items-center gap-1">
                            <i class="ph-fill ph-lightning"></i>
                            Fokus otomatis pada field ini untuk scan cepat
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="button" 
                            id="submitBtn"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="ph-fill ph-check-circle text-2xl"></i>
                        <span class="text-lg">Simpan Absensi</span>
                    </button>
                </div>
            </div>

            <!-- Status Display -->
            <div id="statusDisplay" class="hidden">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Recent Scans -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-clock-clockwise text-xl text-gray-600"></i>
                        Scan Terakhir
                    </h3>
                </div>
                <div id="recentScans" class="p-6">
                    <p class="text-sm text-gray-500 text-center italic">Belum ada scan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Attendance List (muncul setelah pilih matakuliah) -->
    <div id="liveAttendanceSection" class="hidden mt-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="ph-fill ph-list-checks text-xl text-blue-600"></i>
                    <span id="liveListTitle">Daftar Absensi</span>
                </h3>
                <span id="liveListCount" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                    <i class="ph-fill ph-clipboard-text"></i>
                    0 Record
                </span>
            </div>
            <div id="liveAttendanceBody" class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Dosen</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-28">Pertemuan</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-32">Tanggal</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Materi</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody id="liveAttendanceTbody" class="divide-y divide-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm font-semibold">Pilih mata kuliah untuk melihat data absensi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:navigated', () => {
    initAbsensiDosen();
});

// Also run on initial load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAbsensiDosen);
} else {
    initAbsensiDosen();
}

function initAbsensiDosen() {
    const identifierInput = document.getElementById('identifier');
    const matakuliahSelect = document.getElementById('matakuliah_id');
    const pertemuanSelect = document.getElementById('pertemuan');
    const materiInput = document.getElementById('materi');
    const submitBtn = document.getElementById('submitBtn');
    const statusDisplay = document.getElementById('statusDisplay');
    const recentScans = document.getElementById('recentScans');

    // Live attendance elements
    const liveSection = document.getElementById('liveAttendanceSection');
    const liveListTitle = document.getElementById('liveListTitle');
    const liveListCount = document.getElementById('liveListCount');
    const liveTbody = document.getElementById('liveAttendanceTbody');

    if (!identifierInput || !submitBtn) return;

    // Auto-focus on identifier input
    identifierInput.focus();

    // Load attendance list when matakuliah changes
    matakuliahSelect.addEventListener('change', () => {
        const mkId = matakuliahSelect.value;
        if (mkId) {
            const selectedOption = matakuliahSelect.options[matakuliahSelect.selectedIndex];
            const mkText = selectedOption.text;
            loadAttendanceList(mkId, mkText);
        } else {
            liveSection.classList.add('hidden');
        }
    });

    // Function to load attendance list from API
    async function loadAttendanceList(mkId, mkName) {
        liveSection.classList.remove('hidden');
        liveListTitle.textContent = 'Daftar Absensi — ' + (mkName || '');
        liveTbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm font-semibold">
                    <i class="ph-fill ph-spinner text-xl animate-spin mr-2"></i>Memuat data...
                </td>
            </tr>
        `;

        try {
            const response = await fetch(`/admin/absensi-dosen/list/${mkId}`);
            const data = await response.json();

            liveListCount.innerHTML = `<i class="ph-fill ph-clipboard-text"></i> ${data.length} Record`;

            if (data.length === 0) {
                liveTbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <i class="ph-fill ph-clipboard-text text-3xl text-gray-300 mb-2 block"></i>
                            <span class="text-gray-400 text-sm font-semibold">Belum ada data absensi untuk mata kuliah ini.</span>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.forEach((item, index) => {
                let statusBadge = '';
                if (item.status === 'Hadir') {
                    statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-100">
                         <i class="ph-fill ph-check-circle text-sm"></i> Hadir
                       </span>`;
                } else if (item.status === 'Online') {
                    statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                         <i class="ph-fill ph-globe text-sm"></i> Online
                       </span>`;
                } else {
                    statusBadge = `<span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-100">
                         <i class="ph-fill ph-users text-sm"></i> Asisten
                       </span>`;
                }

                html += `
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-3"><span class="text-sm font-bold text-gray-500">${index + 1}</span></td>
                        <td class="px-6 py-3">
                            <div>
                                <p class="text-sm font-bold text-gray-900">${item.dosen}</p>
                                <p class="text-[10px] text-gray-400 font-medium">${item.email}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-bold rounded-lg border border-violet-100">
                                <i class="ph-fill ph-hash text-[10px]"></i> P${item.pertemuan}
                            </span>
                        </td>
                        <td class="px-6 py-3"><span class="text-sm font-semibold text-gray-700">${item.tanggal}</span></td>
                        <td class="px-6 py-3"><span class="text-sm text-gray-600">${item.materi}</span></td>
                        <td class="px-6 py-3">${statusBadge}</td>
                    </tr>
                `;
            });

            liveTbody.innerHTML = html;

            // Scroll to the live attendance section
            liveSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        } catch (error) {
            liveTbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-rose-500 text-sm font-semibold">
                        <i class="ph-fill ph-warning-circle text-xl mr-2"></i>Gagal memuat data.
                    </td>
                </tr>
            `;
        }
    }

    // Handle submit
    submitBtn.addEventListener('click', async () => {
        const identifier = identifierInput.value.trim();
        const matakuliah_id = matakuliahSelect.value;
        const pertemuan = pertemuanSelect.value;
        const materi = materiInput.value.trim();
        const status = document.querySelector('input[name="status"]:checked').value;

        // Validation
        if (!matakuliah_id) {
            showStatus('error', 'Pilih mata kuliah terlebih dahulu!');
            return;
        }

        if (!pertemuan) {
            showStatus('error', 'Pilih pertemuan terlebih dahulu!');
            return;
        }

        if (!identifier) {
            showStatus('error', 'Scan RFID atau masukkan email dosen!');
            identifierInput.focus();
            return;
        }

        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-fill ph-spinner text-2xl animate-spin"></i><span class="text-lg">Memproses...</span>';

        try {
            const response = await fetch('{{ route("admin.absensi-dosen.store.rfid") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    identifier: identifier,
                    matakuliah_id: matakuliah_id,
                    pertemuan: pertemuan,
                    materi: materi,
                    status: status
                })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                showStatus('error', errorData.message || `Terjadi kesalahan (Status: ${response.status})`);
                return;
            }

            const data = await response.json();

            if (data.success) {
                // Notifikasi cepat menggunakan SweetAlert Toast
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: data.message,
                    text: data.nama
                });

                // Redirect sangat cepat (500ms) agar terasa snappy
                setTimeout(() => {
                    window.location.href = '{{ route("admin.absensi-dosen") }}';
                }, 500);
            } else {
                showStatus('error', data.message, data.nama);
            }
        } catch (error) {
            console.error('Error details:', error);
            showStatus('error', 'Terjadi kesalahan sistem atau koneksi terputus!');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ph-fill ph-check-circle text-2xl"></i><span class="text-lg">Simpan Absensi</span>';
        }
    });

    // Enter key to submit
    identifierInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitBtn.click();
        }
    });

    function showStatus(type, message, nama = '', email = '') {
        statusDisplay.classList.remove('hidden');
        
        if (type === 'success') {
            statusDisplay.innerHTML = `
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-6 rounded-lg shadow-sm animate-pulse">
                    <div class="flex items-start gap-4">
                        <div class="bg-green-500 p-3 rounded-full">
                            <i class="ph-fill ph-check-circle text-3xl text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-green-800 font-bold text-lg mb-1">${message}</p>
                            ${nama ? `<p class="text-green-700 font-semibold">${nama}</p>` : ''}
                            ${email ? `<p class="text-green-600 text-sm">${email}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        } else {
            statusDisplay.innerHTML = `
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-6 rounded-lg shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="bg-red-500 p-3 rounded-full">
                            <i class="ph-fill ph-warning-circle text-3xl text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-red-800 font-bold text-lg mb-1">${message}</p>
                            ${nama ? `<p class="text-red-700 font-semibold">${nama}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        setTimeout(() => {
            statusDisplay.classList.add('hidden');
        }, 5000);
    }

    function addRecentScan(nama, identifier) {
        const now = new Date().toLocaleTimeString('id-ID');
        const scanItem = `
            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                    ${nama.charAt(0).toUpperCase()}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">${nama}</p>
                    <p class="text-xs text-gray-600">${identifier} • ${now}</p>
                </div>
                <i class="ph-fill ph-check-circle text-2xl text-green-600"></i>
            </div>
        `;

        if (recentScans.querySelector('p.italic')) {
            recentScans.innerHTML = scanItem;
        } else {
            recentScans.insertAdjacentHTML('afterbegin', scanItem);
        }
    }
}
</script>
@endsection

