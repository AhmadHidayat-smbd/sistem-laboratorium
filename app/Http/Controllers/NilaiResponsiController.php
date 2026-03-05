<?php

namespace App\Http\Controllers;

use App\Models\NilaiResponsi;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Exports\NilaiResponsiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiResponsiController extends Controller
{
    /**
     * Export Excel for Nilai Responsi
     */
    public function exportExcel($matakuliah_id)
    {
        $mk = Matakuliah::findOrFail($matakuliah_id);
        $fileName = 'Nilai_Responsi_' . str_replace(' ', '_', $mk->nama) . '.xlsx';
        
        return Excel::download(new NilaiResponsiExport($matakuliah_id), $fileName);
    }

    /**
     * Display the grading page.
     */
    public function index(Request $request)
    {
        // Filter tahun ajaran
        $tahunAjaranList = Matakuliah::select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        if ($request->has('tahun_ajaran')) {
            $tahunAjaran = $request->query('tahun_ajaran');
            if ($tahunAjaran === 'all') {
                $tahunAjaran = '';
            }
        } else {
            $tahunAjaran = '';
        }

        // Filter matakuliah
        $matakuliahId = $request->query('matakuliah_id', '');

        // Get matakuliah filtered
        $matakuliah = Matakuliah::when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('nama')
            ->get();

        // Get student data per matakuliah who have attended the Responsi session (pertemuan 9)
        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->join('absensi', function($join) {
                $join->on('absensi.mahasiswa_id', '=', 'mahasiswa.id')
                     ->on('absensi.matakuliah_id', '=', 'mahasiswa_matakuliah.matakuliah_id');
            })
            ->select('mahasiswa.*', 'mahasiswa_matakuliah.matakuliah_id')
            ->where('absensi.pertemuan', 9)
            ->where('absensi.status', 'Hadir')
            ->when($matakuliahId, fn($q) =>
                $q->where('mahasiswa_matakuliah.matakuliah_id', $matakuliahId)
            )
            ->when(!$matakuliahId && $tahunAjaran, function($q) use ($matakuliah) {
                $q->whereIn('mahasiswa_matakuliah.matakuliah_id', $matakuliah->pluck('id')->toArray());
            })
            ->orderBy('mahasiswa.nim', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        // Get grades
        $nilaiResponsi = NilaiResponsi::get()->groupBy('matakuliah_id');

        return view('admin.nilai-responsi.index', compact(
            'matakuliah',
            'peserta',
            'nilaiResponsi',
            'tahunAjaranList',
            'tahunAjaran',
            'matakuliahId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'nilai'         => 'required|numeric|min:0|max:100',
            'catatan'       => 'nullable|string',
        ]);

        NilaiResponsi::updateOrCreate(
            [
                'mahasiswa_id'  => $request->mahasiswa_id,
                'matakuliah_id' => $request->matakuliah_id,
            ],
            [
                'nilai'   => $request->nilai,
                'catatan' => $request->catatan,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Nilai responsi berhasil disimpan.'
        ]);
    }

    /**
     * Get grade details for a student via AJAX.
     */
    public function show($mahasiswa_id, $matakuliah_id)
    {
        $nilai = NilaiResponsi::where([
            'mahasiswa_id'  => $mahasiswa_id,
            'matakuliah_id' => $matakuliah_id,
        ])->first();

        return response()->json([
            'success' => true,
            'data'    => $nilai
        ]);
    }

    /**
     * Delete a grade.
     */
    public function destroy($id)
    {
        NilaiResponsi::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Nilai responsi berhasil dihapus.'
        ]);
    }
}
