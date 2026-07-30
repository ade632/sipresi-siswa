<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaImportTemplateExport implements FromArray, WithColumnWidths, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                '2526001', '0091234567', 'Contoh Nama Siswa', 'X TKJ 1', 'L',
                '2009-05-14', 'Jl. Contoh No. 1, Rejang Lebong',
                'Contoh Nama Orang Tua', 'ortu.contoh@email.com', '081234567890', '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nis', 'nisn', 'nama', 'kelas', 'jenis_kelamin',
            'tanggal_lahir', 'alamat', 'nama_ortu', 'email_ortu', 'no_hp_ortu', 'rfid_uid',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, 'B' => 14, 'C' => 28, 'D' => 14, 'E' => 12,
            'F' => 14, 'G' => 32, 'H' => 28, 'I' => 28, 'J' => 16, 'K' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D4ED8'],
            ], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
