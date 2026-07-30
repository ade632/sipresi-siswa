<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PerangkatPiket;
use Illuminate\Http\Request;

class PerangkatPiketController extends Controller
{
    public function index()
    {
        return view('pengaturan.perangkat-piket', [
            'perangkat' => PerangkatPiket::with('didaftarkanOleh')->latest()->paginate(15),
        ]);
    }

    /**
     * Registrasi device baru. Fingerprint dikirim otomatis oleh browser
     * (lihat resources/js/device-fingerprint.js) saat Admin membuka
     * halaman ini dari device yang ingin didaftarkan.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_device' => ['required', 'string', 'max:100'],
            'device_fingerprint' => ['required', 'string', 'unique:perangkat_piket,device_fingerprint'],
            'lokasi_pos' => ['nullable', 'string', 'max:100'],
        ]);

        $perangkat = PerangkatPiket::create([
            ...$data,
            'didaftarkan_oleh' => $request->user()->id,
        ]);

        AuditLog::catat("Mendaftarkan perangkat piket: {$perangkat->nama_device}", 'perangkat_piket');

        return back()->with('success', "Perangkat '{$perangkat->nama_device}' berhasil didaftarkan.");
    }

    public function toggleAktif(PerangkatPiket $perangkatPiket)
    {
        $perangkatPiket->update(['is_aktif' => ! $perangkatPiket->is_aktif]);

        $status = $perangkatPiket->is_aktif ? 'diaktifkan' : 'dinonaktifkan';
        AuditLog::catat("Perangkat piket {$perangkatPiket->nama_device} {$status}", 'perangkat_piket');

        return back()->with('success', "Perangkat berhasil {$status}.");
    }

    public function destroy(PerangkatPiket $perangkatPiket)
    {
        $nama = $perangkatPiket->nama_device;
        $perangkatPiket->delete();

        AuditLog::catat("Menghapus perangkat piket: {$nama}", 'perangkat_piket');

        return back()->with('success', 'Perangkat berhasil dihapus dari whitelist.');
    }
}
