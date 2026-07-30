<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface AbsensiRepositoryInterface
{
    public function rekapHarian(string $tanggal): array;

    public function rekapPerKelas(int $kelasId, string $dariTanggal, string $sampaiTanggal): Collection;

    public function rankingKedisiplinanKelas(string $periode): Collection;
}
