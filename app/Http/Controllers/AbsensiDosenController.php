<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\AbsensiDosen;
use Illuminate\Http\Request;

class AbsensiDosenController extends Controller
{
    /**
     * Display the absensi dosen page
     */
    public function index(Request $request)
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

        return view('admin.absensi-dosen.index', compact('matakuliah', 'absensiAll', 'absensiGrouped', 'tahunAjaranList', 'tahunAjaran'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $matakuliah = Matakuliah::active()->with('dosen')->orderBy('nama')->get();
        return view('admin.absensi-dosen.create', compact('matakuliah'));
    }

    /**
     * Store absensi via RFID
     */
    public function storeByRFID(Request $request)
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
     * Delete absensi dosen
     */
    public function destroy($id)
    {
        $absensi = AbsensiDosen::findOrFail($id);
        $absensi->delete();

        return redirect()->route('admin.absensi-dosen')->with('success', 'Data absensi dosen berhasil dihapus!');
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
     * Export Excel Rekap Absensi Dosen (Semua / Filtered)
     */
    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DosenAbsensiExport(),
            'Rekap_Absensi_Semua_Dosen_' . date('Y-m-d') . '.xlsx'
        );
    }
}
