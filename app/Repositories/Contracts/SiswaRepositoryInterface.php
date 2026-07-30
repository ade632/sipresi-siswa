<?php

namespace App\Repositories\Contracts;

use App\Models\Siswa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SiswaRepositoryInterface
{
    public function paginateWithFilter(array $filter, int $perPage = 20): LengthAwarePaginator;

    public function cariByKartu(string $kode, string $tipe): ?Siswa;

    public function siswaBermasalah(int $minPoin = 40): Collection;
}
