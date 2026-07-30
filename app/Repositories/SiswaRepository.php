<?php

namespace App\Repositories;

use App\Models\Siswa;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Repository dipakai selektif untuk entitas dengan query kompleks (lihat
 * catatan arsitektur Tahap 1) -- Siswa termasuk di dalamnya karena
 * sering difilter kombinasi kelas/jurusan/status/poin dari berbagai modul
 * (Admin, Guru Piket, Guru BK, Kepsek).
 */
class SiswaRepository implements SiswaRepositoryInterface
{
    public function paginateWithFilter(array $filter, int $perPage = 20): LengthAwarePaginator
    {
        return Siswa::query()
            ->with(['kelas.jurusan', 'orangTua'])
            ->when($filter['kelas_id'] ?? null, fn ($q, $v) => $q->where('kelas_id', $v))
            ->when($filter['jurusan_id'] ?? null, fn ($q, $v) => $q->whereHas('kelas', fn ($k) => $k->where('jurusan_id', $v)))
            ->when($filter['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filter['cari'] ?? null, fn ($q, $v) => $q->where(fn ($qq) => $qq
                ->where('nama', 'like', "%{$v}%")
                ->orWhere('nis', 'like', "%{$v}%")
                ->orWhere('nisn', 'like', "%{$v}%")))
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function cariByKartu(string $kode, string $tipe): ?Siswa
    {
        return Siswa::whereHas('kartuAktif', fn ($q) => $q->where('kode', $kode)->where('tipe', $tipe))->first();
    }

    public function siswaBermasalah(int $minPoin = 40): Collection
    {
        return Siswa::query()
            ->where('status', 'aktif')
            ->withSum('pelanggaran as total_poin', 'poin_saat_ini')
            ->having('total_poin', '>=', $minPoin)
            ->orderByDesc('total_poin')
            ->with('kelas')
            ->get();
    }
}
