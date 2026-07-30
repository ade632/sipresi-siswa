<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        protected string $dari,
        protected string $sampai,
        protected ?int $kelasId = null,
    ) {}

    public function collection()
    {
        return Absensi::whereBetween('tanggal', [$this->dari, $this->sampai])
            ->when($this->kelasId, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->with('siswa.kelas')
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 'Jam Masuk', 'Jam Pulang', 'Status', 'Metode'];
    }

    public function map($absensi): array
    {
        return [
            $absensi->tanggal->format('d-m-Y'),
            $absensi->siswa->nis,
            $absensi->siswa->nama,
            $absensi->siswa->kelas->nama,
            $absensi->jam_masuk,
            $absensi->jam_pulang,
            ucfirst($absensi->status),
            strtoupper($absensi->metode),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
