<?php

namespace App\Livewire;

use App\Models\Mahasiswa;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MahasiswaAttendanceDashboard extends Component
{
    public $mahasiswaId;

    public function mount()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        if (!$mahasiswa) {
            return abort(403, 'Akses ditolak.');
        }

        $this->mahasiswaId = $mahasiswa->id;
    }

    public function render()
    {
        $mahasiswa = Mahasiswa::with(['matakuliah' => function($q) {
            // Hanya tampilkan matakuliah yang aktif (is_active = true)
            $q->where('is_active', true);
        }])->findOrFail($this->mahasiswaId);

        // Hanya hitung absensi dari matakuliah yang aktif
        $activeMkIds = $mahasiswa->matakuliah->pluck('id')->toArray();
        
        $absensi = Absensi::where('mahasiswa_id', $this->mahasiswaId)
            ->whereIn('matakuliah_id', $activeMkIds)
            ->get();
            
        $absensi_grouped = $absensi->groupBy('matakuliah_id');
        $totalHadir = $absensi->where('status', 'Hadir')->count();
        $totalMatkul = $mahasiswa->matakuliah->count();

        return view('livewire.mahasiswa-attendance-dashboard', [
            'mahasiswa' => $mahasiswa,
            'matakuliah' => $mahasiswa->matakuliah,
            'absensi_grouped' => $absensi_grouped,
            'totalHadir' => $totalHadir,
            'totalMatkul' => $totalMatkul,
        ]);
    }
}
