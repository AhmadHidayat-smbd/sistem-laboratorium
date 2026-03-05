<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AbsensiPerMatkulSheet implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    protected $matakuliah;

    public function __construct(Matakuliah $matakuliah)
    {
        $this->matakuliah = $matakuliah;
    }

    public function title(): string
    {
        return $this->matakuliah->nama;
    }

    /**
     * ==========================
     * DATA ISI EXCEL
     * ==========================
     */
    public function collection()
    {
        $peserta = DB::table('mahasiswa_matakuliah')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_matakuliah.mahasiswa_id')
            ->where('mahasiswa_matakuliah.matakuliah_id', $this->matakuliah->id)
            ->orderBy('mahasiswa.nim')
            ->select('mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nama')
            ->get();

        $data = [];

        foreach ($peserta as $mhs) {

            $row = [
                $mhs->nim,
                $mhs->nama,
            ];

            $totalHadir = 0;

            for ($p = 1; $p <= 8; $p++) {

                $absen = Absensi::where([
                    'mahasiswa_id'  => $mhs->id,
                    'matakuliah_id' => $this->matakuliah->id,
                    'pertemuan'     => $p,
                ])->first();

                if ($absen && $absen->status === 'Hadir') {
                    $row[] = 'Hadir';
                    $totalHadir++;
                } elseif ($absen) {
                    $row[] = $absen->status;
                } else {
                    $row[] = '-';
                }
            }

            // ➕ KOLOM RESPONSI (P9)
            $responsi = Absensi::where([
                'mahasiswa_id'  => $mhs->id,
                'matakuliah_id' => $this->matakuliah->id,
                'pertemuan'     => 9,
            ])->first();
            $row[] = $responsi ? $responsi->status : '-';

            // ➕ KOLOM TOTAL HADIR
            $row[] = $totalHadir . '/8';

            $data[] = $row;
        }

        return collect($data);
    }

    /**
     * ==========================
     * HEADER TABEL
     * ==========================
     */
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

    /**
     * ==========================
     * HEADER NAMA MATA KULIAH
     * ==========================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Tambah 2 baris di atas
                $event->sheet->insertNewRowBefore(1, 2);

                // Judul mata kuliah
                $event->sheet->setCellValue(
                    'A1',
                    'MATA KULIAH : ' . strtoupper($this->matakuliah->nama)
                );

                // Merge A1 sampai L1 (karena kolom sekarang 12: NIM, Nama, P1-P8, Responsi, Total)
                $event->sheet->mergeCells('A1:L1');

                // Styling
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                ]);
            },
        ];
    }
}
