<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware RBAC sederhana berbasis kolom role_id pada users.
 * Dipakai di routes: ->middleware('role:admin,guru_piket')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if(! $user, 403, 'Silakan login terlebih dahulu.');

        abort_if(! $user->status_aktif, 403, 'Akun Anda dinonaktifkan. Hubungi Administrator.');

        abort_unless(in_array($user->role?->kode, $roles, true), 403,
            'Anda tidak memiliki hak akses ke halaman ini.');

        return $next($request);
    }
}
