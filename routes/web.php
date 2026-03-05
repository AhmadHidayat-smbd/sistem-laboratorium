<?php

use App\Http\Controllers\NilaiResponsiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\MahasiswaPortalController; 
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPortalController;
use App\Http\Controllers\AbsensiDosenController;
use App\Http\Controllers\AbsensiAsistenController;


Route::get('/', function () {
    return view('index');
})->name('home');



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- PORTAL MAHASISWA ---
Route::middleware(['auth:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaPortalController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/profile', [MahasiswaPortalController::class, 'profile'])->name('mahasiswa.profile');
    Route::put('/mahasiswa/profile/update-password', [MahasiswaPortalController::class, 'updatePassword'])->name('mahasiswa.update-password');
});


// --- PORTAL DOSEN ---

Route::post('/dosen/logout', [DosenPortalController::class, 'logout'])->name('dosen.logout');

Route::middleware(['auth:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenPortalController::class, 'dashboard'])->name('dosen.dashboard');
    Route::get('/dosen/absensi', [DosenPortalController::class, 'absensi'])->name('dosen.absensi');
    Route::get('/dosen/nilai-responsi', [DosenPortalController::class, 'nilaiResponsi'])->name('dosen.nilai-responsi');
    Route::get('/dosen/nilai-responsi/export/{matakuliah_id}', [DosenPortalController::class, 'exportNilaiResponsi'])->name('dosen.nilai-responsi.export');
    Route::post('/dosen/nilai-responsi/store', [DosenPortalController::class, 'storeNilaiResponsi'])->name('dosen.nilai-responsi.store');
    Route::delete('/dosen/nilai-responsi/{id}', [DosenPortalController::class, 'deleteNilaiResponsi'])->name('dosen.nilai-responsi.delete');

    // Export Absensi untuk Dosen
    Route::get('/dosen/absensi/export/excel', [DosenPortalController::class, 'exportExcel'])->name('dosen.absensi.export.excel');
    Route::get('/dosen/absensi/export/excel/all', [DosenPortalController::class, 'exportAllExcel'])->name('dosen.absensi.export.excel.all');

    // Edit Absensi untuk Dosen
    Route::get('/dosen/absensi/edit/{mahasiswa}/{matakuliah}', [DosenPortalController::class, 'editAbsensi'])->name('dosen.absensi.edit');
    Route::post('/dosen/absensi/update', [DosenPortalController::class, 'updateAbsensi'])->name('dosen.absensi.update');

    // Lihat Absensi Diri Sendiri
    Route::get('/dosen/absensi-saya', [DosenPortalController::class, 'rekapAbsensiSaya'])->name('dosen.absensi-saya');
    Route::get('/dosen/absensi-saya/export', [DosenPortalController::class, 'exportAbsensiSaya'])->name('dosen.absensi-saya.export');
    Route::post('/dosen/absensi-saya/update-materi', [DosenPortalController::class, 'updateMateriDosen'])->name('dosen.absensi-saya.update-materi');

    // Profil Dosen
    Route::get('/dosen/profile', [DosenPortalController::class, 'profile'])->name('dosen.profile');
    Route::put('/dosen/profile/update', [DosenPortalController::class, 'updateProfile'])->name('dosen.profile.update');
    Route::put('/dosen/profile/password', [DosenPortalController::class, 'updatePassword'])->name('dosen.profile.password');
});


// --- PORTAL ASISTEN ---
Route::middleware(['auth'])->group(function () {
    // Dashboard - Halaman Utama (Ringkasan)
    Route::get('/asisten/dashboard', [AsistenController::class, 'dashboard'])->name('asisten.dashboard');
    
    // Absensi - Halaman Data Absensi (Tabel Detail)
    Route::get('/asisten/absensi', [AsistenController::class, 'absensi'])->name('asisten.absensi');
    
    // Group rute yang WAJIB IP Kampus
    Route::middleware(['kudu_kampus'])->group(function() {
        // Tambah Presensi Mahasiswa
        Route::get('/asisten/tambah-absensi', [AsistenController::class, 'createAbsensi'])->name('asisten.tambah-absensi');
        Route::get('/asisten/tambah-absensi/scan', [AsistenController::class, 'createAbsensiStep2'])->name('asisten.tambah-absensi2');
        Route::post('/asisten/absensi/store-rfid', [AsistenController::class, 'storeByRFID'])->name('asisten.absensi.store.rfid');
        
        // Absensi Dosen (Oleh Asisten)
        Route::get('/asisten/absensi-dosen/create', [AsistenController::class, 'createAbsensiDosen'])->name('asisten.absensi-dosen.create');
        Route::post('/asisten/absensi-dosen/store-rfid', [AsistenController::class, 'storeAbsensiDosenByRFID'])->name('asisten.absensi-dosen.store.rfid');
        
        // Absensi Asisten Sendiri
        Route::get('/asisten/absensi-asisten/create', [AbsensiAsistenController::class, 'create'])->name('asisten.absensi-asisten.create');
        Route::post('/asisten/absensi-asisten/store', [AbsensiAsistenController::class, 'store'])->name('asisten.absensi-asisten.store');
    });
    
    // Rute Luar (Bisa diakses dari mana saja)
    Route::get('/asisten/absensi/export/excel', [AsistenController::class, 'exportExcel'])->name('asisten.absensi.export.excel');
    Route::get('/asisten/absensi/export/excel/all', [AsistenController::class, 'exportAllExcel'])->name('asisten.absensi.export.excel.all');
    Route::get('/asisten/absensi/stats', [AsistenController::class, 'getStats'])->name('asisten.absensi.stats');
    Route::delete('/asisten/absensi/delete', [AsistenController::class, 'destroy'])->name('asisten.absensi.delete');

    // Peserta
    Route::get('/asisten/peserta', [AsistenController::class, 'peserta'])->name('asisten.peserta');
    Route::get('/asisten/peserta/{matakuliah_id}/create', [AsistenController::class, 'createPeserta'])->name('asisten.peserta.create');
    Route::post('/asisten/peserta/{matakuliah_id}/store', [AsistenController::class, 'storePeserta'])->name('asisten.peserta.store');
    Route::delete('/asisten/peserta/{matakuliah_id}/delete-all', [AsistenController::class, 'deleteAllPeserta'])->name('asisten.peserta.delete-all');
    Route::delete('/asisten/peserta/{matakuliah_id}/{mahasiswa_id}', [AsistenController::class, 'deletePeserta'])->name('asisten.peserta.delete');

    // Absensi Dosen (List & Export)
    Route::get('/asisten/absensi-dosen', [AsistenController::class, 'absensiDosen'])->name('asisten.absensi-dosen');
    Route::get('/asisten/absensi-dosen/export', [AsistenController::class, 'exportExcelDosen'])->name('asisten.absensi-dosen.export');
    Route::delete('/asisten/absensi-dosen/{id}', [AsistenController::class, 'destroyAbsensiDosen'])->name('asisten.absensi-dosen.delete');
    Route::get('/asisten/absensi-dosen/list/{matakuliah_id}', [AsistenController::class, 'listByMatakuliah'])->name('asisten.absensi-dosen.list');

    // Absensi Asisten (List & Export)
    Route::get('/asisten/absensi-asisten', [AbsensiAsistenController::class, 'index'])->name('asisten.absensi-asisten');
    Route::get('/asisten/absensi-asisten/export', [AbsensiAsistenController::class, 'exportExcel'])->name('asisten.absensi-asisten.export');
    Route::delete('/asisten/absensi-asisten/{id}', [AbsensiAsistenController::class, 'destroy'])->name('asisten.absensi-asisten.delete');
});


// --- PORTAL ADMIN ---
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Kelola User Routes
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/add', [AdminController::class, 'addUser'])->name('admin.users.add');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');


    Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index'])->name('admin.mahasiswa');
    Route::get('/admin/mahasiswa/create', [MahasiswaController::class, 'create'])->name('admin.mahasiswa.create');
    Route::post('/admin/mahasiswa', [MahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
    Route::get('/admin/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('admin.mahasiswa.edit');
    Route::put('/admin/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
    Route::delete('/admin/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('admin.mahasiswa.delete');
    Route::post('/admin/mahasiswa/import', [MahasiswaController::class, 'importExcel'])->name('admin.mahasiswa.import');


    Route::get('/admin/matakuliah', [MatakuliahController::class, 'index'])->name('admin.matakuliah');
    Route::get('/admin/matakuliah/create', [MatakuliahController::class, 'create'])->name('admin.matakuliah.create');
    Route::post('/admin/matakuliah/store', [MatakuliahController::class, 'store'])->name('admin.matakuliah.store');
    Route::get('/admin/matakuliah/{id}/edit', [MatakuliahController::class, 'edit'])->name('admin.matakuliah.edit');
    Route::put('/admin/matakuliah/{id}', [MatakuliahController::class, 'update'])->name('admin.matakuliah.update');
    Route::delete('/admin/matakuliah/{id}', [MatakuliahController::class, 'destroy'])->name('admin.matakuliah.delete');
    Route::patch('/admin/matakuliah/{id}/toggle-active', [MatakuliahController::class, 'toggleActive'])->name('admin.matakuliah.toggle-active');

    Route::get('/admin/peserta', [PesertaController::class, 'index'])->name('admin.peserta');
    Route::get('/admin/peserta/{matakuliah_id}/create', [PesertaController::class, 'create'])->name('admin.peserta.create');
    Route::post('/admin/peserta/{matakuliah_id}/store', [PesertaController::class, 'store'])->name('admin.peserta.store');
    Route::get('/admin/peserta/{matakuliah_id}/{mahasiswa_id}/edit', [PesertaController::class, 'edit'])->name('admin.peserta.edit');
    Route::put('/admin/peserta/{matakuliah_id}/{mahasiswa_id}', [PesertaController::class, 'update'])->name('admin.peserta.update');
    Route::delete('/admin/peserta/{matakuliah_id}/delete-all', [PesertaController::class, 'deleteAll'])->name('admin.peserta.delete-all');
    Route::delete('/admin/peserta/{matakuliah_id}/{mahasiswa_id}', [PesertaController::class, 'delete'])->name('admin.peserta.delete');


    Route::get('/admin/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal');
    Route::get('/admin/jadwal/create', [JadwalController::class, 'create'])->name('admin.jadwal.create');
    Route::post('/admin/jadwal/store', [JadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::get('/admin/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
    Route::put('/admin/jadwal/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/admin/jadwal/{id}', [JadwalController::class, 'delete'])->name('admin.jadwal.delete');

   // Daftar presensi
    Route::get('/admin/absensi', [AbsensiController::class, 'index'])->name('admin.absensi');

    // Tambah presensi (Langkah 1)
    Route::get('/admin/absensi/create', [AbsensiController::class, 'create'])->name('admin.absensi.create');

    // Tambah presensi (Langkah 2 - Scan)
    Route::get('/admin/absensi/create2', [AbsensiController::class, 'createStep2'])->name('admin.absensi.create2');

    // Simpan presensi manual
    Route::post('/admin/absensi/store', [AbsensiController::class, 'store'])->name('admin.absensi.store');

    // Edit presensi
    Route::get('/admin/absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('admin.absensi.edit');
    Route::put('/admin/absensi/{id}', [AbsensiController::class, 'update'])->name('admin.absensi.update');

    Route::get(
    '/admin/absensi/edit/{mahasiswa}/{matakuliah}',
    [AbsensiController::class, 'editByPertemuan'])->name('admin.absensi.edit.form');

    Route::post(
    '/admin/absensi/update-pertemuan',[AbsensiController::class, 'updateByPertemuan'])->name('admin.absensi.update.pertemuan');


    // Hapus
   Route::delete(
    '/admin/absensi/delete-pertemuan',
        [AbsensiController::class, 'destroyByPertemuan'])->name('admin.absensi.delete.pertemuan');


    // AJAX peserta matakuliah
    Route::get('/admin/get-mahasiswa/{id}', [AbsensiController::class, 'getMahasiswaByMatakuliah']);

    // Presensi via RFID atau NIM
    Route::post('/admin/absensi/rfid', [AbsensiController::class, 'storeByRFID'])
        ->name('admin.absensi.store.rfid');

    // Get Stats Presensi Realtime
    Route::get('/admin/absensi/stats', [AbsensiController::class, 'getStats'])
        ->name('admin.absensi.stats');

    Route::get(
    '/admin/absensi/export/excel',
    [AbsensiController::class, 'exportExcel']
    )->name('admin.absensi.export.excel');
    Route::get(
        '/admin/absensi/export/excel/all',
        [AbsensiController::class, 'exportAllExcel']
    )->name('admin.absensi.export.excel.all');

    // Nilai Responsi
    Route::get('/admin/nilai-responsi', [NilaiResponsiController::class, 'index'])->name('admin.nilai-responsi');
    Route::get('/admin/nilai-responsi/export/{matakuliah_id}', [NilaiResponsiController::class, 'exportExcel'])->name('admin.nilai-responsi.export');
    Route::post('/admin/nilai-responsi', [NilaiResponsiController::class, 'store'])->name('admin.nilai-responsi.store');
    Route::get('/admin/nilai-responsi/{mahasiswa_id}/{matakuliah_id}', [NilaiResponsiController::class, 'show'])->name('admin.nilai-responsi.show');
    Route::delete('/admin/nilai-responsi/{id}', [NilaiResponsiController::class, 'destroy'])->name('admin.nilai-responsi.delete');

    // Kelola Dosen
    Route::get('/admin/dosen', [DosenController::class, 'index'])->name('admin.dosen');
    Route::get('/admin/dosen/create', [DosenController::class, 'create'])->name('admin.dosen.create');
    Route::post('/admin/dosen', [DosenController::class, 'store'])->name('admin.dosen.store');
    Route::get('/admin/dosen/{id}/edit', [DosenController::class, 'edit'])->name('admin.dosen.edit');
    Route::put('/admin/dosen/{id}', [DosenController::class, 'update'])->name('admin.dosen.update');
    Route::delete('/admin/dosen/{id}', [DosenController::class, 'destroy'])->name('admin.dosen.delete');

    // Absensi Dosen
    Route::get('/admin/absensi-dosen', [AbsensiDosenController::class, 'index'])->name('admin.absensi-dosen');
    Route::get('/admin/absensi-dosen/export', [AbsensiDosenController::class, 'exportExcel'])->name('admin.absensi-dosen.export');
    Route::get('/admin/absensi-dosen/create', [AbsensiDosenController::class, 'create'])->name('admin.absensi-dosen.create');
    Route::post('/admin/absensi-dosen/store-rfid', [AbsensiDosenController::class, 'storeByRFID'])->name('admin.absensi-dosen.store.rfid');
    Route::delete('/admin/absensi-dosen/{id}', [AbsensiDosenController::class, 'destroy'])->name('admin.absensi-dosen.delete');
    Route::get('/admin/absensi-dosen/list/{matakuliah_id}', [AbsensiDosenController::class, 'listByMatakuliah'])->name('admin.absensi-dosen.list');

    // Pembayaran Routes
    Route::get('/admin/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::get('/admin/pembayaran/create', [PembayaranController::class, 'create'])->name('admin.pembayaran.create');
    Route::post('/admin/pembayaran', [PembayaranController::class, 'store'])->name('admin.pembayaran.store');
    Route::get('/admin/pembayaran/detail/{nim}', [PembayaranController::class, 'show'])->name('admin.pembayaran.show');
    Route::get('/admin/pembayaran/{pembayaran}/edit', [PembayaranController::class, 'edit'])->name('admin.pembayaran.edit');
    Route::put('/admin/pembayaran/{pembayaran}', [PembayaranController::class, 'update'])->name('admin.pembayaran.update');
    Route::delete('/admin/pembayaran/{pembayaran}', [PembayaranController::class, 'destroy'])->name('admin.pembayaran.destroy');
    Route::post('/admin/pembayaran/import', [PembayaranController::class, 'importExcel'])->name('admin.pembayaran.import');

});
