<?php

namespace App\Exports;

use App\Models\AbsensiAsisten;
use App\Models\Matakuliah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * Export multi-sheet: tiap matakuliah = 1 sheet
 */
class AbsensiAsistenExport implements WithMultipleSheets
{
    protected $matakuliah_id;

    public function __construct($matakuliah_id = null)
    {
        $this->matakuliah_id = $matakuliah_id;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->matakuliah_id) {
            $mk = Matakuliah::find($this->matakuliah_id);
            if ($mk) {
                $sheets[] = new AbsensiAsistenPerMatkulSheet($mk);
            }
        } else {
            $mkIds = AbsensiAsisten::distinct()->pluck('matakuliah_id');
            $matakuliah = Matakuliah::whereIn('id', $mkIds)
                ->orderBy('nama')
                ->get();

            foreach ($matakuliah as $mk) {
                $sheets[] = new AbsensiAsistenPerMatkulSheet($mk);
            }
        }

        return $sheets;
    }
}

/**
 * Sheet per matakuliah
 */
class AbsensiAsistenPerMatkulSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $matakuliah;

    public function __construct(Matakuliah $matakuliah)
    {
        $this->matakuliah = $matakuliah;
    }

    public function collection()
    {
        return AbsensiAsisten::with(['user'])
            ->where('matakuliah_id', $this->matakuliah->id)
            ->orderBy('pertemuan', 'asc')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_hadir', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return ['Nama Asisten', 'Pertemuan', 'Tanggal', 'Jam Hadir', 'Status'];
    }

    public function map($absensi): array
    {
        return [
            $absensi->user->name ?? 'N/A',
            'Pertemuan ' . $absensi->pertemuan,
            $absensi->tanggal,
            $absensi->jam_hadir,
            $absensi->status,
        ];
    }

    public function title(): string
    {
        $nama = $this->matakuliah->nama;
        return mb_strlen($nama) > 31 ? mb_substr($nama, 0, 28) . '...' : $nama;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->insertNewRowBefore(1, 2);

                $event->sheet->setCellValue(
                    'A1',
                    'MATA KULIAH : ' . strtoupper($this->matakuliah->nama)
                );

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
}
