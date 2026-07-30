<?php

namespace App\Exports;

use App\Models\Pelanggaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelanggaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        protected string $dari,
        protected string $sampai,
        protected ?int $kelasId = null,
    ) {}

    public function collection()
    {
        return Pelanggaran::whereBetween('tanggal', [$this->dari, $this->sampai])
            ->when($this->kelasId, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->with(['siswa.kelas', 'jenisPelanggaran', 'dicatatOleh'])
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Pelanggaran', 'Kategori', 'Poin', 'Dicatat Oleh'];
    }

    public function map($pelanggaran): array
    {
        return [
            $pelanggaran->tanggal->format('d-m-Y'),
            $pelanggaran->siswa->nis,
            $pelanggaran->siswa->nama,
            $pelanggaran->siswa->kelas->nama,
            $pelanggaran->jenisPelanggaran->nama,
            ucfirst($pelanggaran->jenisPelanggaran->kategori),
            $pelanggaran->poin_saat_ini,
            $pelanggaran->dicatatOleh->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
