<?php

namespace App\Http\Controllers;

use App\Models\AbsensiAsisten;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiAsistenController extends Controller
{
    /**
     * Tampilkan halaman absensi asisten
     */
    public function index()
    {
        $asistenId = auth()->id();
        
        // Ambil riwayat seluruh absensi asisten (Rekapitulasi)
        $historyAll = AbsensiAsisten::with(['matakuliah', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_hadir', 'desc')
            ->get();

        // Grouping by Matakuliah
        $historyGrouped = $historyAll->groupBy('matakuliah_id');

        // Ambil daftar matakuliah aktif untuk stats or comparison
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();

        return view('asisten.absensi-asisten.index', compact('historyAll', 'historyGrouped', 'matakuliah'));
    }

    /**
     * Tampilkan halaman tambah absensi
     */
    public function create()
    {
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();
        return view('asisten.absensi-asisten.create', compact('matakuliah'));
    }

    /**
     * Simpan data absensi (Klik Tombol Hadir)
     */
    public function store(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:8',
        ], [
            'matakuliah_id.required' => 'Pilih mata kuliah terlebih dahulu.',
            'pertemuan.required'     => 'Pilih pertemuan keberapa.',
        ]);

        $asistenId = auth()->id();
        $today = Carbon::today()->toDateString();

        // Cek apakah SUDAH ADA asisten (siapapun) yang absen untuk matkul & pertemuan ini
        $exists = AbsensiAsisten::where('matakuliah_id', $request->matakuliah_id)
            ->where('pertemuan', $request->pertemuan)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk pertemuan ini sebelumnya!');
        }

        // Simpan absensi
        AbsensiAsisten::create([
            'user_id'       => $asistenId,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
            'tanggal'       => $today,
            'jam_hadir'     => Carbon::now()->toTimeString(),
            'status'        => 'Hadir'
        ]);

        return redirect()->route('asisten.absensi-asisten')
            ->with('success', 'Absensi berhasil! Selamat bertugas.');
    }

    /**
     * Hapus riwayat absen jika salah (opsional)
     */
    public function destroy($id)
    {
        $absensi = AbsensiAsisten::find($id);

        if (!$absensi) {
            return back()->with('error', 'Data absensi tidak ditemukan.');
        }

        // Pastikan hanya bisa menghapus milik sendiri
        if ($absensi->user_id !== auth()->id()) {
            return back()->with('error', 'Gagal! Anda tidak diperbolehkan menghapus riwayat absensi milik asisten lain.');
        }
            
        $absensi->delete();

        return back()->with('success', 'Riwayat absensi berhasil dihapus.');
    }

    /**
     * Export Excel Absensi Asisten
     */
    public function exportExcel(Request $request)
    {
        $matakuliah_id = $request->get('matakuliah_id');
        
        $filename = 'Rekap_Absensi_Asisten';
        if ($matakuliah_id) {
            $mk = Matakuliah::find($matakuliah_id);
            if ($mk) {
                $filename .= '_' . str_replace(' ', '_', $mk->nama);
            }
        }
        $filename .= '_' . date('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiAsistenExport($matakuliah_id),
            $filename
        );
    }
}
