<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Absensi;
use App\Models\NilaiResponsi;
use App\Models\Mahasiswa;
use App\Models\AbsensiDosen;
use App\Exports\DosenAbsensiExport;
use App\Exports\AbsensiExport;
use App\Exports\NilaiResponsiExport;
use Maatwebsite\Excel\Facades\Excel;

class DosenPortalController extends Controller
{
    /**
     * Export Excel Nilai Responsi (Dosen)
     */
    public function exportNilaiResponsi($matakuliah_id)
    {
        $dosen = Auth::guard('dosen')->user();
        $mk = Matakuliah::where('id', $matakuliah_id)->where('dosen_id', $dosen->id)->firstOrFail();
        
        $fileName = 'Nilai_Responsi_' . str_replace(' ', '_', $mk->nama) . '.xlsx';
        return Excel::download(new NilaiResponsiExport($matakuliah_id), $fileName);
    }



    /**
     * Dashboard Dosen
     */
    public function dashboard()
    {
        $dosen = Auth::guard('dosen')->user();
        
        // Ambil data mata kuliah yang diampu
        $matakuliah = $dosen->matakuliah()->with(['mahasiswa', 'jadwal'])->get();

        // Hitung statistik
        $totalMatakuliah = $matakuliah->count();
        $totalMahasiswa = $matakuliah->sum(function($mk) {
            return $mk->mahasiswa->count();
        });

        // Absensi hari ini
        $today = now()->format('Y-m-d');
        $absensiHariIni = Absensi::whereIn('matakuliah_id', $matakuliah->pluck('id'))
            ->whereDate('tanggal', $today)
            ->where('status', 'Hadir')
            ->count();

        return view('dosen.dashboard', compact('dosen', 'matakuliah', 'totalMatakuliah', 'totalMahasiswa', 'absensiHariIni'));
    }

    /**
     * Menampilkan absensi mahasiswa
     */
    public function absensi()
    {
        return view('dosen.absensi');
    }

    /**
     * Menampilkan dan mengelola nilai responsi
     */
    public function nilaiResponsi(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        // Tahun ajaran filter
        $tahunAjaranList = $dosen->matakuliah()
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        if ($request->has('tahun_ajaran')) {
            $tahunAjaran = $request->query('tahun_ajaran');
            if ($tahunAjaran === 'all') {
                $tahunAjaran = '';
            }
        } else {
            // Default current semester
            $currentYear = (int) date('Y');
            $currentMonth = (int) date('m');
            $tahunAjaran = $currentMonth >= 7
                ? $currentYear . '-1'
                : ($currentYear - 1) . '-2';
        }

        // Matakuliah filter
        $matakuliahId = $request->query('matakuliah_id', '');

        // Hanya mata kuliah yang diampu, filtered by tahun_ajaran
        $matakuliah = $dosen->matakuliah()
            ->when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('nama')
            ->get();

        $mkIds = $matakuliah->pluck('id')->toArray();

        // Get peserta who attended Responsi (pertemuan 9) grouped by matakuliah
        $peserta = \DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->join('absensi', function($join) {
                $join->on('absensi.mahasiswa_id', '=', 'mahasiswa.id')
                     ->on('absensi.matakuliah_id', '=', 'mahasiswa_matakuliah.matakuliah_id');
            })
            ->select('mahasiswa.*', 'mahasiswa_matakuliah.matakuliah_id')
            ->where('absensi.pertemuan', 9)
            ->where('absensi.status', 'Hadir')
            ->whereIn('mahasiswa_matakuliah.matakuliah_id', $mkIds)
            ->when($matakuliahId, fn($q) =>
                $q->where('mahasiswa_matakuliah.matakuliah_id', $matakuliahId)
            )
            ->orderBy('mahasiswa.nim', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        // Get grades grouped by matakuliah
        $nilaiResponsi = \App\Models\NilaiResponsi::whereIn('matakuliah_id', $mkIds)
            ->get()
            ->groupBy('matakuliah_id');

        return view('dosen.nilai-responsi', compact(
            'matakuliah', 'peserta', 'nilaiResponsi',
            'tahunAjaranList', 'tahunAjaran', 'matakuliahId'
        ));
    }

    /**
     * Store nilai responsi
     */
    public function storeNilaiResponsi(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        // Pastikan dosen mengampu mata kuliah ini
        if (!$dosen->matakuliah()->where('id', $request->matakuliah_id)->exists()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak mengampu mata kuliah ini!'], 403);
            }
            return back()->with('error', 'Anda tidak mengampu mata kuliah ini!');
        }

        NilaiResponsi::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'matakuliah_id' => $request->matakuliah_id,
            ],
            [
                'nilai' => $request->nilai,
                'catatan' => $request->catatan,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Nilai berhasil disimpan!']);
        }

        return back()->with('success', 'Nilai responsi berhasil disimpan!');
    }

    /**
     * Delete nilai responsi
     */
    public function deleteNilaiResponsi($id)
    {
        $dosen = Auth::guard('dosen')->user();
        $nilai = NilaiResponsi::findOrFail($id);

        // Pastikan dosen mengampu mata kuliah ini
        if (!$dosen->matakuliah()->where('id', $nilai->matakuliah_id)->exists()) {
            return response()->json(['message' => 'Anda tidak mengampu mata kuliah ini!'], 403);
        }

        $nilai->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nilai responsi berhasil dihapus.'
        ]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::guard('dosen')->logout();
        return redirect()->route('login');
    }

    /**
     * Export absensi per matakuliah
     */
    public function exportExcel(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
        ]);

        // Pastikan dosen mengampu matakuliah ini
        if (!$dosen->matakuliah()->where('id', $request->matakuliah_id)->exists()) {
            abort(403, 'Anda tidak mengampu mata kuliah ini.');
        }

        $matkul = Matakuliah::findOrFail($request->matakuliah_id);

        return Excel::download(
            new AbsensiExport($matkul->id),
            'Rekap_Presensi_' . str_replace(' ', '_', $matkul->nama) . '.xlsx'
        );
    }

    /**
     * Export semua absensi matakuliah yang diampu dosen
     */
    public function exportAllExcel()
    {
        $dosen = Auth::guard('dosen')->user();
        $matakuliahIds = $dosen->matakuliah()->pluck('id')->toArray();

        return Excel::download(
            new class($matakuliahIds) implements \Maatwebsite\Excel\Concerns\WithMultipleSheets {
                private $matakuliahIds;
                public function __construct($ids) { $this->matakuliahIds = $ids; }
                public function sheets(): array {
                    $sheets = [];
                    $matakuliah = \App\Models\Matakuliah::whereIn('id', $this->matakuliahIds)->orderBy('nama')->get();
                    foreach ($matakuliah as $mk) {
                        $sheets[] = new \App\Exports\AbsensiPerMatkulSheet($mk);
                    }
                    return empty($sheets) ? [new class implements \Maatwebsite\Excel\Concerns\WithTitle, \Maatwebsite\Excel\Concerns\WithHeadings {
                        public function title(): string { return 'Kosong'; }
                        public function headings(): array { return ['Tidak ada matakuliah yang diampu']; }
                    }] : $sheets;
                }
            },
            'rekap_presensi_mahasiswa_all.xlsx'
        );
    }

    /**
     * Export kehadiran Dosen itu sendiri
     */
    public function exportAbsensiSaya(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        $matakuliahId = $request->query('matakuliah_id');
        
        $fileName = 'Rekap_Kehadiran_Saya_' . date('Y-m-d') . '.xlsx';
        
        if ($matakuliahId) {
            $mk = Matakuliah::findOrFail($matakuliahId);
            $fileName = 'Rekap_Kehadiran_' . str_replace(' ', '_', $mk->nama) . '_' . date('Y-m-d') . '.xlsx';
            
            return Excel::download(
                new class($mk, $dosen->id) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping, \Maatwebsite\Excel\Concerns\WithTitle, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithEvents {
                    protected $matakuliah;
                    protected $dosen_id;

                    public function __construct($matakuliah, $dosen_id) {
                        $this->matakuliah = $matakuliah;
                        $this->dosen_id = $dosen_id;
                    }

                    public function collection() {
                        return \App\Models\AbsensiDosen::with(['dosen'])
                            ->where('matakuliah_id', $this->matakuliah->id)
                            ->where('dosen_id', $this->dosen_id)
                            ->orderBy('pertemuan', 'asc')
                            ->get();
                    }

                    public function headings(): array {
                        return ['Nama Dosen', 'Pertemuan', 'Tanggal', 'Materi', 'Status'];
                    }

                    public function map($absensi): array {
                        return [
                            $absensi->dosen->nama ?? 'N/A',
                            'Pertemuan ' . $absensi->pertemuan,
                            $absensi->tanggal ? $absensi->tanggal->format('Y-m-d') : '-',
                            $absensi->materi ?? '-',
                            $absensi->status,
                        ];
                    }

                    public function title(): string {
                        return substr($this->matakuliah->nama, 0, 31);
                    }

                    public function registerEvents(): array {
                        return [
                            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                                $event->sheet->insertNewRowBefore(1, 2);
                                $event->sheet->setCellValue('A1', 'MATA KULIAH : ' . strtoupper($this->matakuliah->nama));
                                $event->sheet->mergeCells('A1:E1');
                                $event->sheet->getStyle('A1')->applyFromArray([
                                    'font' => ['bold' => true, 'size' => 13],
                                    'alignment' => ['horizontal' => 'center'],
                                ]);
                                $event->sheet->getStyle('A3:E3')->applyFromArray([
                                    'font' => ['bold' => true],
                                    'fill' => [
                                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                        'startColor' => ['rgb' => 'E2E8F0'],
                                    ],
                                ]);
                            },
                        ];
                    }
                },
                $fileName
            );
        }

        return Excel::download(
            new \App\Exports\DosenAbsensiExport($dosen->id),
            $fileName
        );
    }

    /**
     * Edit data absensi (Dosen hanya bisa edit, tidak bisa tambah)
     */
    public function editAbsensi($mahasiswaId, $matakuliahId)
    {
        $dosen = Auth::guard('dosen')->user();

        // Validasi: Dosen harus mengampu matakuliah ini
        if (!$dosen->matakuliah()->where('id', $matakuliahId)->exists()) {
            abort(403, 'Anda tidak berhak mengedit absensi matakuliah ini.');
        }

        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $matakuliah = Matakuliah::findOrFail($matakuliahId);

        // Ambil pertemuan yang SUDAH ADA datanya (karena dosen tidak boleh tambah baru)
        $pertemuanAda = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->where('matakuliah_id', $matakuliahId)
            ->pluck('pertemuan')
            ->sort()
            ->values();

        return view('dosen.edit-absensi', compact('mahasiswa', 'matakuliah', 'pertemuanAda'));
    }

    /**
     * Update data absensi
     */
    public function updateAbsensi(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'mahasiswa_id' => 'required',
            'matakuliah_id' => 'required',
            'pertemuan' => 'required',
            'status' => 'required|in:Hadir,Tidak Hadir',
        ]);

        // Validasi kepemilikan matakuliah
        if (!$dosen->matakuliah()->where('id', $request->matakuliah_id)->exists()) {
            abort(403, 'Akses Ditolak');
        }

        // Cek apakah data sudah ada (Dosen hanya boleh edit)
        $absensi = Absensi::where([
            'mahasiswa_id' => $request->mahasiswa_id,
            'matakuliah_id' => $request->matakuliah_id,
            'pertemuan' => $request->pertemuan,
        ])->first();

        if (!$absensi) {
            return back()->with('error', 'Data presensi untuk pertemuan ini belum ada. Dosen hanya dapat mengedit data yang sudah ada.');
        }

        $absensi->update([
            'status' => $request->status,
        ]);

        return redirect()->route('dosen.absensi', ['matakuliah_id' => $request->matakuliah_id])
            ->with('success', 'Data presensi berhasil diperbarui!');
    }

    /**
     * Lihat rekap kehadiran diri sendiri
     */
    /**
     * Lihat rekap kehadiran diri sendiri
     */
    public function rekapAbsensiSaya(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        // Tahun ajaran filter
        $tahunAjaranList = $dosen->matakuliah()
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        $tahunAjaran = $request->query('tahun_ajaran', '');

        // Get matakuliah filtered
        $matakuliah = $dosen->matakuliah()
            ->when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('nama')
            ->get();

        $mkIds = $matakuliah->pluck('id')->toArray();

        // Get absensi dosen grouped by matakuliah
        $absensiDosen = AbsensiDosen::where('dosen_id', $dosen->id)
            ->whereIn('matakuliah_id', $mkIds)
            ->with('matakuliah')
            ->orderBy('pertemuan', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        return view('dosen.rekap-absensi-saya', compact(
            'matakuliah', 'absensiDosen', 'tahunAjaranList', 'tahunAjaran'
        ));
    }

    /**
     * Update materi perkuliahan dosen (AJAX)
     */
    public function updateMateriDosen(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $request->validate([
            'id' => 'required|exists:absensi_dosen,id',
            'materi' => 'nullable|string|max:255',
        ]);

        $absen = AbsensiDosen::findOrFail($request->id);

        // Pastikan dosen hanya bisa edit punya sendiri
        if ($absen->dosen_id != $dosen->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $absen->update([
            'materi' => $request->materi
        ]);

        return response()->json(['message' => 'Materi berhasil diupdate']);
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('dosen.profile', compact('dosen'));
    }

    /**
     * Update profile (nama & email)
     */
    public function updateProfile(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:dosen,email,' . $dosen->id,
        ]);

        $dosen->update([
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(6)],
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $dosen->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $dosen->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
