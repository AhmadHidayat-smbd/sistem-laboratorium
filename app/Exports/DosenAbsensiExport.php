<?php

namespace App\Exports;

use App\Models\AbsensiDosen;
use App\Models\Matakuliah;
use App\Models\Dosen;
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
class DosenAbsensiExport implements WithMultipleSheets
{
    protected $dosen_id;

    public function __construct($dosen_id = null)
    {
        $this->dosen_id = $dosen_id;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Ambil matakuliah yang memiliki data absensi dosen
        $mkQuery = AbsensiDosen::distinct()->select('matakuliah_id');
        if ($this->dosen_id) {
            $mkQuery->where('dosen_id', $this->dosen_id);
        }
        $mkIds = $mkQuery->pluck('matakuliah_id');

        $matakuliah = Matakuliah::whereIn('id', $mkIds)->orderBy('nama')->get();

        foreach ($matakuliah as $mk) {
            $sheets[] = new DosenAbsensiPerMatkulSheet($mk, $this->dosen_id);
        }

        if (empty($sheets)) {
            // Jika kosong, beri 1 sheet kosong agar tidak error
            $sheets[] = new DosenAbsensiKosongSheet();
        }

        return $sheets;
    }
}

/**
 * Sheet per matakuliah
 */
class DosenAbsensiPerMatkulSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $matakuliah;
    protected $dosen_id;

    public function __construct(Matakuliah $matakuliah, $dosen_id = null)
    {
        $this->matakuliah = $matakuliah;
        $this->dosen_id = $dosen_id;
    }

    public function collection()
    {
        $query = AbsensiDosen::with(['dosen'])
            ->where('matakuliah_id', $this->matakuliah->id)
            ->orderBy('pertemuan', 'asc')
            ->orderBy('tanggal', 'asc');

        if ($this->dosen_id) {
            $query->where('dosen_id', $this->dosen_id);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Nama Dosen', 'Pertemuan', 'Tanggal', 'Materi', 'Status'];
    }

    public function map($absensi): array
    {
        return [
            $absensi->dosen->nama ?? 'N/A',
            'Pertemuan ' . $absensi->pertemuan,
            $absensi->tanggal ? $absensi->tanggal->format('Y-m-d') : '-',
            $absensi->materi ?? '-',
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

class DosenAbsensiKosongSheet implements WithTitle, WithHeadings, FromCollection
{
    public function title(): string
    {
        return 'Data Kosong';
    }

    public function headings(): array
    {
        return ['Pesan'];
    }

    public function collection()
    {
        return collect([['Belum ada data presensi dosen.']]);
    }
}
