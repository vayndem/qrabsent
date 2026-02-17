<?php

namespace App\Exports;

use App\Models\Absen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $bulan;

    public function __construct($bulan)
    {
        $this->bulan = $bulan;
    }

    public function collection()
    {
        return Absen::where('tanggal', 'like', $this->bulan . '%')
            ->orderBy('nama')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('nama');
    }

    public function headings(): array
    {
        return [
            ['REKAPITULASI PRESENSI SISWA'],
            ['PERIODE: ' . strtoupper($this->bulan)],
            [''],
            ['NAMA LENGKAP', 'TANGGAL', 'WAKTU', 'PRESENSI']
        ];
    }

    public function map($group): array
    {
        $rows = [];
        foreach ($group as $data) {
            $rows[] = [
                $data->nama,
                $data->tanggal,
                $data->waktu,
                $group->count() . ' Kali',
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Style Header Utama (Baris 1 & 2)
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '111827'] // Hitam Navy sesuai tema Dark Mode
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set alignment judul ke tengah
                $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Ambil data mentah untuk hitung merging
                $dataGroups = $this->collection();
                $startRow = 5;

                foreach ($dataGroups as $group) {
                    /** @var \Illuminate\Support\Collection $group */
                    $rowCount = $group->count();
                    $endRow = $startRow + $rowCount - 1;

                    if ($rowCount > 1) {
                        // Merge Nama (A) & Total Presensi (D)
                        $sheet->mergeCells("A{$startRow}:A{$endRow}");
                        $sheet->mergeCells("D{$startRow}:D{$endRow}");
                    }

                    $startRow = $endRow + 1;
                }

                // Styling tabel: Border & Alignment Tengah
                $lastRow = $startRow - 1;
                $sheet->getStyle("A4:D{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
