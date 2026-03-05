<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiAllMatkulExport;




class AbsensiController extends Controller
{
    /**
     * =================================================
     * INDEX — DATA PRESENSI (GROUP BY MATA KULIAH)
     * =================================================
     */
    public function index(Request $request)
    {
        return view('admin.absensi.index');
    }

    /**
     * =================================================
     * STEP 1 — PILIH MATA KULIAH
     * =================================================
     */
    public function create()
    {
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();
        return view('admin.absensi.create', compact('matakuliah'));
    }

    /**
     * =================================================
     * STEP 2 — HALAMAN SCAN RFID / NIM
     * =================================================
     */
    public function createStep2(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
            'duration'      => 'required|integer|min:1|max:180',
        ]);

        return view('admin.absensi.create2', [
            'matakuliah' => Matakuliah::with('jadwal')->findOrFail($request->matakuliah_id),
            'pertemuan'  => $request->pertemuan,
            'duration'   => $request->duration,
        ]);
    }

    /**
     * =================================================
     * SIMPAN PRESENSI VIA RFID / NIM
     * =================================================
     */
    public function storeByRFID(Request $request)
    {
        $request->validate([
            'identifier'    => 'required|string',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
        ]);

        $identifier = trim($request->identifier);

        // Cari mahasiswa (RFID / NIM)
        $mahasiswa = Mahasiswa::where('rfid_uid', $identifier)->first()
            ?? Mahasiswa::where('nim', $identifier)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak terdaftar!'
            ]);
        }

        // Cek apakah mahasiswa peserta matkul
        $isPeserta = DB::table('mahasiswa_matakuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('matakuliah_id', $request->matakuliah_id)
            ->exists();

        if (!$isPeserta) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa bukan peserta mata kuliah ini!'
            ]);
        }

        // Cek double presensi - HANYA CEK PERTEMUAN (tidak perlu tanggal)
        // Karena 1 mahasiswa hanya boleh 1x presensi per pertemuan
        $exists = Absensi::where([
            'mahasiswa_id'  => $mahasiswa->id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa sudah melakukan presensi pada pertemuan ini!'
            ]);
        }

        // Simpan presensi
        Absensi::create([
            'mahasiswa_id'  => $mahasiswa->id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
            'tanggal'       => date('Y-m-d'),
            'status'        => 'Hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat',
            'nama'    => $mahasiswa->nama,
            'nim'     => $mahasiswa->nim,
        ]);
    }

    /**
     * Get Realtime Stats (untuk counter di scan page)
     */
    public function getStats(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
        ]);

        $totalPeserta = DB::table('mahasiswa_matakuliah')
            ->where('matakuliah_id', $request->matakuliah_id)
            ->count();

        $totalHadir = Absensi::where([
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
            'status'        => 'Hadir',
        ])->count();

        return response()->json([
            'success' => true,
            'total'   => $totalPeserta,
            'hadir'   => $totalHadir,
            'belum'   => $totalPeserta - $totalHadir,
        ]);
    }


    /**
     * =================================================
     * EDIT PRESENSI (BERDASARKAN PERTEMUAN)
     * =================================================
     */
    public function editByPertemuan($mahasiswa_id, $matakuliah_id)
    {
        $pertemuanAda = Absensi::where('mahasiswa_id', $mahasiswa_id)
            ->where('matakuliah_id', $matakuliah_id)
            ->orderBy('pertemuan')
            ->pluck('pertemuan')
            ->unique();

        return view('admin.absensi.edit', [
            'mahasiswa'    => Mahasiswa::findOrFail($mahasiswa_id),
            'matakuliah'   => Matakuliah::findOrFail($matakuliah_id),
            'pertemuanAda' => $pertemuanAda,
        ]);
    }

    /**
     * =================================================
     * UPDATE PRESENSI (BERDASARKAN PERTEMUAN)
     * =================================================
     */
    public function updateByPertemuan(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
            'status'        => 'required|in:Hadir,Tidak Hadir',
        ]);

        $absensi = Absensi::where([
            'mahasiswa_id'  => $request->mahasiswa_id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
        ])->first();

        if (!$absensi) {
            return back()->with('error', 'Belum ada presensi pada pertemuan ini.');
        }

        $absensi->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.absensi')
            ->with('success', 'Presensi berhasil diperbarui.');
    }

    /**
     * =================================================
     * DELETE PRESENSI (PER PERTEMUAN)
     * =================================================
     */
    public function destroyByPertemuan(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
        ]);

        Absensi::where([
            'mahasiswa_id'  => $request->mahasiswa_id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
        ])->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Presensi pertemuan berhasil dihapus.'
            ]);
        }

        return back()->with('success', 'Presensi pertemuan berhasil dihapus.');
    }

    // Export excel
    public function exportExcel(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
        ]);

        $matkul = Matakuliah::findOrFail($request->matakuliah_id);

        return Excel::download(
            new AbsensiExport($matkul->id),
            'Rekap_Presensi_' . str_replace(' ', '_', $matkul->nama) . '.xlsx'
        );
    }
    public function exportAllExcel()
    {
        return Excel::download(
            new AbsensiAllMatkulExport(),
            'rekap_presensi.xlsx'
        );
    }


}
