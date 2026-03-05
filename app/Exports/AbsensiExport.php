<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Matakuliah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromCollection, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{
    protected $matakuliah_id;
    protected $matakuliah;

    public function __construct($matakuliah_id)
    {
        $this->matakuliah_id = $matakuliah_id;
        $this->matakuliah = Matakuliah::find($matakuliah_id);
    }

    public function collection()
    {
        // Ambil peserta matkul urut NIM
        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->where('mahasiswa_matakuliah.matakuliah_id', $this->matakuliah_id)
            ->orderBy('mahasiswa.nim', 'asc')
            ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nama')
            ->get();

        $data = [];

        foreach ($peserta as $mhs) {

            $row = [
                'nim'  => $mhs->nim,
                'nama' => $mhs->nama,
            ];

            // Loop P1–P8
            $hadirCount = 0;
            for ($p = 1; $p <= 8; $p++) {
                $absen = Absensi::where([
                    'mahasiswa_id'  => $mhs->id,
                    'matakuliah_id' => $this->matakuliah_id,
                    'pertemuan'     => $p,
                ])->first();

                $status = $absen ? $absen->status : '-';
                $row['P' . $p] = $status;
                
                if ($status === 'Hadir') {
                    $hadirCount++;
                }
            }

            // Responsi (Pertemuan 9)
            $responsi = Absensi::where([
                'mahasiswa_id'  => $mhs->id,
                'matakuliah_id' => $this->matakuliah_id,
                'pertemuan'     => 9,
            ])->first();
            $row['responsi'] = $responsi ? $responsi->status : '-';

            // Total Kehadiran
            $row['total'] = $hadirCount . '/8';

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'P1',
            'P2',
            'P3',
            'P4',
            'P5',
            'P6',
            'P7',
            'P8',
            'Responsi',
            'Total Kehadiran',
        ];
    }

    public function title(): string
    {
        return $this->matakuliah ? substr($this->matakuliah->nama, 0, 31) : 'Absensi';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Tambah 2 baris di atas
                $event->sheet->insertNewRowBefore(1, 2);

                // Set Judul Matakuliah
                $courseName = $this->matakuliah ? strtoupper($this->matakuliah->nama) : 'N/A';
                $event->sheet->setCellValue('A1', 'REKAP ABSENSI MAHASISWA - MATA KULIAH : ' . $courseName);
                
                // Merge cells untuk judul
                $event->sheet->mergeCells('A1:L1');

                // Styling Judul
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '1D4ED8'], // Biru Dosen
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Styling Header Tabel (sekarang di baris 3 karena nambah 2 baris)
                $event->sheet->getStyle('A3:L3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2563EB'], // Biru Premium
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
