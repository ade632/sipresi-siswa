<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleIndonesia
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('id');
        Carbon::setLocale('id');

        return $next($request);
    }
}
