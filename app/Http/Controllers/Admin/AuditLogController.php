<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->modul, fn ($q, $v) => $q->where('modul', $v))
            ->when($request->cari, fn ($q, $v) => $q->where('aktivitas', 'like', "%{$v}%"))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('pengaturan.audit-log', ['logs' => $logs]);
    }
}
