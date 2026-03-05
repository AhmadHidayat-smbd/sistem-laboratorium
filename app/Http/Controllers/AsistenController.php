<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\AbsensiDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenController extends Controller
{
    /**
     * Dashboard - Halaman Utama (Ringkasan)
     */
    public function dashboard()
    {
        // Ambil hanya mata kuliah yang aktif dengan hitungan peserta
        $matakuliah = Matakuliah::active()
            ->withCount('mahasiswa')
            ->orderBy('nama')
            ->get();

        // Statistics
        $totalMatakuliah = $matakuliah->count();
        $totalMahasiswa  = DB::table('mahasiswa')->count();
        $totalHadir      = Absensi::where('status', 'Hadir')->count();

        return view('asisten.dashboard', compact(
            'matakuliah',
            'totalMatakuliah',
            'totalMahasiswa',
            'totalHadir',
        ));
    }

    /**
     * Absensi - Halaman Data Absensi (Tabel Detail)
     */
    public function absensi(Request $request)
    {
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();
        return view('asisten.absensi', compact('matakuliah'));
    }

    /**
     * Step 1: Pilih Mata Kuliah & Pertemuan
     */
    public function createAbsensi()
    {
        $matakuliah = Matakuliah::active()->with('jadwal')->orderBy('nama')->get();
        return view('asisten.tambah-absensi', compact('matakuliah'));
    }

    /**
     * Step 2: Halaman Scan RFID
     */
    public function createAbsensiStep2(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
            'duration'      => 'required|integer|min:1|max:180',
        ]);

        return view('asisten.tambah-absensi2', [
            'matakuliah' => Matakuliah::with('jadwal')->findOrFail($request->matakuliah_id),
            'pertemuan'  => $request->pertemuan,
            'duration'   => $request->duration,
        ]);
    }

    /**
     * Store Presensi via RFID/NIM
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

        // Cek double presensi (per pertemuan, bukan per hari)
        $exists = Absensi::where([
            'mahasiswa_id'  => $mahasiswa->id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan'     => $request->pertemuan,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa sudah melakukan presensi untuk pertemuan ini!'
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
     * Get Stats Presensi Realtime
     */
    public function getStats(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
        ]);

        // Hitung total peserta
        $totalPeserta = DB::table('mahasiswa_matakuliah')
            ->where('matakuliah_id', $request->matakuliah_id)
            ->count();

        // Hitung yang sudah hadir untuk pertemuan ini (tidak filter tanggal)
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
     * Hapus Presensi per Pertemuan
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan'     => 'required|integer|min:1|max:9',
        ]);

        try {
            $deleted = Absensi::where([
                'mahasiswa_id'  => $request->mahasiswa_id,
                'matakuliah_id' => $request->matakuliah_id,
                'pertemuan'     => $request->pertemuan,
            ])->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Presensi berhasil dihapus!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data presensi tidak ditemukan!'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data!'
            ], 500);
        }
    }

    /**
     * Export Excel per Mata Kuliah
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
        ]);

        $matkul = Matakuliah::findOrFail($request->matakuliah_id);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiPerMatkulSheet($matkul),
            'Rekap_Presensi_' . str_replace(' ', '_', $matkul->nama) . '_' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Excel Semua Mata Kuliah
     */
    public function exportAllExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiAllMatkulExport(),
            'Rekap_Presensi_Semua_Matkul_' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Peserta - Daftar Peserta per Mata Kuliah
     */
    public function peserta(Request $request)
    {
        $matakuliah = Matakuliah::active()->orderBy('nama')->get();

        $activeMkIds = $matakuliah->pluck('id')->toArray();
        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->whereIn('mahasiswa_matakuliah.matakuliah_id', $activeMkIds)
            ->select('mahasiswa.*', 'mahasiswa_matakuliah.matakuliah_id')
            ->when($request->matakuliah_id, fn($q) =>
                $q->where('mahasiswa_matakuliah.matakuliah_id', $request->matakuliah_id)
            )
            ->orderBy('mahasiswa.nim', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        return view('asisten.peserta', compact('matakuliah', 'peserta'));
    }

    /**
     * Create Peserta - Form Tambah Mahasiswa ke Matkul
     */
    public function createPeserta($matakuliah_id)
    {
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        
        // Ambil ID mahasiswa yang sudah terdaftar
        $registeredIds = $matakuliah->mahasiswa()->pluck('mahasiswa_id')->toArray();
        
        // Ambil mahasiswa yang belum terdaftar
        $mahasiswa = Mahasiswa::whereNotIn('id', $registeredIds)
            ->orderBy('nama', 'asc')
            ->get();

        return view('asisten.peserta-create', compact('matakuliah', 'mahasiswa'));
    }

    /**
     * Store Peserta - Simpan Mahasiswa ke Matkul
     */
    public function storePeserta(Request $request, $matakuliah_id)
    {
        $request->validate([
            'mahasiswa_ids' => 'required|array|min:1',
            'mahasiswa_ids.*' => 'exists:mahasiswa,id',
        ], [
            'mahasiswa_ids.required' => 'Pilih minimal 1 mahasiswa!',
        ]);

        $mk = Matakuliah::findOrFail($matakuliah_id);
        $mk->mahasiswa()->attach($request->mahasiswa_ids);

        return redirect()->route('asisten.peserta')
            ->with('success', count($request->mahasiswa_ids) . ' mahasiswa berhasil ditambahkan ke ' . $mk->nama);
    }

    /**
     * Delete Peserta - Hapus Mahasiswa dari Matkul
     */
    public function deletePeserta($matakuliah_id, $mahasiswa_id)
    {
        $mk = Matakuliah::findOrFail($matakuliah_id);
        $mk->mahasiswa()->detach($mahasiswa_id);

        return back()->with('success', 'Peserta berhasil dihapus dari mata kuliah.');
    }

    public function deleteAllPeserta($matakuliah_id)
    {
        $mk = Matakuliah::findOrFail($matakuliah_id);
        $mk->mahasiswa()->detach();

        return back()->with('success', 'Semua peserta dari mata kuliah ' . $mk->nama . ' berhasil dihapus!');
    }

    /**
     * Absensi Dosen - Halaman Utama
     */
    public function absensiDosen(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran');
        
        $matakuliahQuery = Matakuliah::with('dosen')->orderBy('nama');
        if ($tahunAjaran) {
            $matakuliahQuery->where('tahun_ajaran', $tahunAjaran);
        }
        $matakuliah = $matakuliahQuery->get();

        $absensiQuery = AbsensiDosen::with(['dosen', 'matakuliah'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('pertemuan', 'desc');

        if ($tahunAjaran) {
            $absensiQuery->whereHas('matakuliah', function($q) use ($tahunAjaran) {
                $q->where('tahun_ajaran', $tahunAjaran);
            });
        }
        
        $absensiAll = $absensiQuery->get();
        $absensiGrouped = $absensiAll->groupBy('matakuliah_id');

        // Get unique tahun ajaran list for filter
        $tahunAjaranList = Matakuliah::select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        return view('asisten.absensi-dosen-index', compact('matakuliah', 'absensiAll', 'absensiGrouped', 'tahunAjaranList', 'tahunAjaran'));
    }

    /**
     * Absensi Dosen - Tambah (Step 1)
     */
    public function createAbsensiDosen()
    {
        $matakuliah = Matakuliah::active()->with('dosen')->orderBy('nama')->get();
        return view('asisten.absensi-dosen-create', compact('matakuliah'));
    }

    /**
     * Absensi Dosen - Store via RFID
     */
    public function storeAbsensiDosenByRFID(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'pertemuan' => 'required|integer|min:1|max:9',
            'materi' => 'nullable|string|max:255',
            'status' => 'required|in:Hadir,Online,Digantikan Asisten',
        ]);

        $identifier = trim($request->identifier);

        // Cari dosen by RFID or Email
        $dosen = Dosen::where('rfid_uid', $identifier)->first()
            ?? Dosen::where('email', $identifier)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan!'
            ]);
        }

        // Cek apakah dosen mengampu matakuliah ini
        $matakuliah = Matakuliah::find($request->matakuliah_id);
        if ($matakuliah->dosen_id != $dosen->id) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak mengampu mata kuliah ini!'
            ]);
        }

        // Cek duplicate
        $exists = AbsensiDosen::where([
            'dosen_id' => $dosen->id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan' => $request->pertemuan,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen sudah melakukan presensi untuk pertemuan ini!',
                'nama' => $dosen->nama,
            ]);
        }

        // Simpan
        AbsensiDosen::create([
            'dosen_id' => $dosen->id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan' => $request->pertemuan,
            'tanggal' => date('Y-m-d'),
            'materi' => $request->materi,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat!',
            'nama' => $dosen->nama,
            'email' => $dosen->email,
        ]);
    }

    /**
     * Absensi Dosen - Delete
     */
    public function destroyAbsensiDosen($id)
    {
        $absensi = AbsensiDosen::findOrFail($id);
        $absensi->delete();

        return redirect()->route('asisten.absensi-dosen')->with('success', 'Data absensi dosen berhasil dihapus!');
    }

    /**
     * Get list absensi dosen by matakuliah (JSON)
     */
    public function listByMatakuliah($matakuliah_id)
    {
        $absensi = AbsensiDosen::with(['dosen'])
            ->where('matakuliah_id', $matakuliah_id)
            ->orderBy('pertemuan', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'dosen'     => $item->dosen->nama ?? 'N/A',
                    'email'     => $item->dosen->email ?? '',
                    'pertemuan' => $item->pertemuan,
                    'tanggal'   => $item->tanggal->format('d M Y'),
                    'materi'    => $item->materi ?? '-',
                    'status'    => $item->status,
                ];
            });

        return response()->json($absensi);
    }

    /**
     * Export Excel Absensi Dosen (Asisten view)
     */
    public function exportExcelDosen(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DosenAbsensiExport(),
            'Rekap_Absensi_Dosen_Semua_' . date('Y-m-d') . '.xlsx'
        );
    }
}