<?php

namespace App\Livewire;

use App\Models\Absensi;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AsistenAttendanceList extends Component
{
    public $matakuliahId = '';

    public function mount()
    {
        // Default: kosong (tampilkan semua matakuliah aktif)
        $this->matakuliahId = '';
    }

    public function render()
    {
        // Hanya matakuliah aktif untuk dropdown
        $matakuliahList = Matakuliah::active()
            ->orderBy('nama')
            ->get();

        // Filter berdasarkan matakuliah yang dipilih
        if ($this->matakuliahId) {
            $matakuliah = Matakuliah::active()
                ->with('dosen')
                ->where('id', $this->matakuliahId)
                ->get();
        } else {
            $matakuliah = Matakuliah::active()
                ->with('dosen')
                ->orderBy('nama')
                ->get();
        }

        $mkIds = $matakuliah->pluck('id')->toArray();

        // Ambil data absensi
        $absensi = Absensi::with(['mahasiswa', 'matakuliah'])
            ->whereIn('matakuliah_id', $mkIds)
            ->get()
            ->groupBy('matakuliah_id');

        // Ambil peserta per mata kuliah
        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->select('mahasiswa.*', 'mahasiswa_matakuliah.matakuliah_id')
            ->whereIn('mahasiswa_matakuliah.matakuliah_id', $mkIds)
            ->orderBy('mahasiswa.nim', 'asc')
            ->get()
            ->groupBy('matakuliah_id');

        return view('livewire.asisten-attendance-list', [
            'matakuliahList' => $matakuliahList,
            'matakuliah' => $matakuliah,
            'absensi' => $absensi,
            'peserta' => $peserta
        ]);
    }

    public function exportExcel($id)
    {
        $matkul = Matakuliah::findOrFail($id);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiPerMatkulSheet($matkul),
            'Rekap_Presensi_' . str_replace(' ', '_', $matkul->nama) . '_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportAll()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiAllMatkulExport(),
            'Rekap_Presensi_Semua_Matkul_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function resetFilter()
    {
        $this->matakuliahId = '';
    }
}
