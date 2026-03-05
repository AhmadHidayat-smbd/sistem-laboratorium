<?php

namespace App\Livewire;

use App\Models\Absensi;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAttendanceList extends Component
{
    public $tahunAjaran = '';

    public function mount()
    {
        // Default: tampilkan semua (kosong = tanpa filter)
        $this->tahunAjaran = '';
    }

    public function render()
    {
        // Get all distinct tahun_ajaran for the dropdown
        $tahunAjaranList = Matakuliah::select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Get matakuliah filtered by tahun_ajaran
        $allMatakuliah = Matakuliah::with('dosen')
            ->when($this->tahunAjaran, fn($q) => $q->where('tahun_ajaran', $this->tahunAjaran))
            ->orderBy('nama')
            ->get();

        $mkIds = $allMatakuliah->pluck('id')->toArray();

        // Fetch attendance data
        $absensi = Absensi::with(['mahasiswa', 'matakuliah'])
            ->whereIn('matakuliah_id', $mkIds)
            ->get()
            ->groupBy('matakuliah_id');

        // Fetch participants per course
        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->select('mahasiswa.*', 'mahasiswa_matakuliah.matakuliah_id')
            ->whereIn('mahasiswa_matakuliah.matakuliah_id', $mkIds)
            ->orderBy('mahasiswa.nim', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        return view('livewire.admin-attendance-list', [
            'tahunAjaranList' => $tahunAjaranList,
            'allMatakuliah' => $allMatakuliah,
            'absensi' => $absensi,
            'peserta' => $peserta
        ]);
    }

    public function resetFilter()
    {
        // Reset ke tampilkan semua
        $this->tahunAjaran = '';
    }
}
