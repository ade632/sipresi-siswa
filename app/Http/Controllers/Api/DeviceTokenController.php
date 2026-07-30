<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Dipanggil dari resources/js/firebase-messaging.js setiap kali browser
 * berhasil mendapatkan/registrasi ulang FCM token (misalnya setelah user
 * mengizinkan notifikasi, atau token expired & di-refresh otomatis
 * oleh Firebase SDK).
 */
class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'token_fcm' => ['required', 'string'],
            'platform' => ['required', 'in:android,ios,web'],
        ]);

        $request->user()->deviceTokens()->updateOrCreate(
            ['token_fcm' => $data['token_fcm']],
            ['platform' => $data['platform'], 'last_used_at' => now()],
        );

        return response()->json(['sukses' => true]);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate(['token_fcm' => ['required', 'string']]);

        $request->user()->deviceTokens()->where('token_fcm', $data['token_fcm'])->delete();

        return response()->json(['sukses' => true]);
    }
}
