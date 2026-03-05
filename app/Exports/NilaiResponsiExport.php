<?php

namespace App\Exports;

use App\Models\NilaiResponsi;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiResponsiExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $matakuliah;

    public function __construct($matakuliahId)
    {
        $this->matakuliah = Matakuliah::with('dosen')->findOrFail($matakuliahId);
    }

    public function collection()
    {
        // Get peserta who attended Responsi (pertemuan 9)
        return DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->join('absensi', function($join) {
                $join->on('absensi.mahasiswa_id', '=', 'mahasiswa.id')
                     ->on('absensi.matakuliah_id', '=', 'mahasiswa_matakuliah.matakuliah_id');
            })
            ->leftJoin('nilai_responsi', function($join) {
                $join->on('nilai_responsi.mahasiswa_id', '=', 'mahasiswa.id')
                     ->on('nilai_responsi.matakuliah_id', '=', 'mahasiswa_matakuliah.matakuliah_id');
            })
            ->select('mahasiswa.nim', 'mahasiswa.nama', 'nilai_responsi.nilai', 'nilai_responsi.catatan')
            ->where('mahasiswa_matakuliah.matakuliah_id', $this->matakuliah->id)
            ->where('absensi.pertemuan', 9)
            ->where('absensi.status', 'Hadir')
            ->orderBy('mahasiswa.nim', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['REKAP NILAI RESPONSI'],
            ['Mata Kuliah: ' . $this->matakuliah->nama . ' (' . $this->matakuliah->kode . ')'],
            ['Dosen: ' . ($this->matakuliah->dosen->nama ?? 'N/A')],
            ['Tahun Ajaran: ' . ($this->matakuliah->tahun_ajaran ?? '-')],
            [''],
            ['NO', 'NIM', 'NAMA MAHASISWA', 'NILAI RESPONSI', 'CATATAN DOSEN']
        ];
    }

    public function map($row): array
    {
        static $no = 1;
        return [
            $no++,
            $row->nim,
            $row->nama,
            $row->nilai ?? '-',
            $row->catatan ?? '-'
        ];
    }

    public function title(): string
    {
        return 'Nilai Responsi - ' . substr($this->matakuliah->nama, 0, 20);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            6    => ['font' => ['bold' => true]],
        ];
    }
}
