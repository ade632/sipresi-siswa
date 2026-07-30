<?php

namespace App\Providers;

use App\Repositories\AbsensiRepository;
use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use App\Repositories\SiswaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SiswaRepositoryInterface::class, SiswaRepository::class);
        $this->app->bind(AbsensiRepositoryInterface::class, AbsensiRepository::class);
    }
}
