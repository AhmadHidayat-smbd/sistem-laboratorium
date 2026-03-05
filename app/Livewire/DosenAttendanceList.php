<?php

namespace App\Livewire;

use App\Models\Absensi;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DosenAttendanceList extends Component
{
    public $tahunAjaran = '';

    public function mount()
    {
        // Default: semester aktif saat ini
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $this->tahunAjaran = $currentMonth >= 7
            ? $currentYear . '-1'
            : ($currentYear - 1) . '-2';
    }

    public function render()
    {
        $dosen = Auth::guard('dosen')->user();

        // Get distinct tahun_ajaran from this dosen's matakuliah
        $tahunAjaranList = $dosen->matakuliah()
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Get matakuliah for this dosen, filtered by tahun_ajaran
        $matakuliah = $dosen->matakuliah()
            ->when($this->tahunAjaran, fn($q) => $q->where('tahun_ajaran', $this->tahunAjaran))
            ->orderBy('nama')
            ->get();

        $mkIds = $matakuliah->pluck('id')->toArray();

        $absensi = Absensi::whereIn('matakuliah_id', $mkIds)
            ->get()
            ->groupBy('matakuliah_id');

        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->select('mahasiswa.*', 'mahasiswa_matakuliah.matakuliah_id')
            ->whereIn('mahasiswa_matakuliah.matakuliah_id', $mkIds)
            ->orderBy('mahasiswa.nim', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        return view('livewire.dosen-attendance-list', [
            'tahunAjaranList' => $tahunAjaranList,
            'matakuliahList' => $matakuliah,
            'absensi' => $absensi,
            'peserta' => $peserta
        ]);
    }

    public function resetFilter()
    {
        $this->tahunAjaran = '';
    }
}
