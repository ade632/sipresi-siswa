<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1d4ed8">
    <title>Masuk - SIPRESI SISWA</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-primary-900 via-primary-700 to-primary-500 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-md">
        <div class="text-center mb-6 text-white">
            <img src="{{ $logoSekolahGlobal }}" alt="Logo" class="w-20 h-20 mx-auto mb-3 drop-shadow-lg">
            <h1 class="text-2xl font-bold tracking-tight">SIPRESI SISWA</h1>
            <p class="text-primary-100/80 text-sm mt-1">Sistem Presensi & Kedisiplinan Siswa</p>
            <p class="text-primary-100/60 text-xs">{{ $namaSekolahGlobal }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 p-7 sm:p-9">
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@smkn1rl.sch.id"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-base py-2.5 pl-11">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2"/></svg>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-base py-2.5 pl-11">
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600">
                    Ingat saya di perangkat ini
                </label>
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold text-base py-3 rounded-lg transition shadow-md shadow-primary-600/20">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-primary-100/70 text-xs mt-6">&copy; {{ date('Y') }} {{ $namaSekolahGlobal }}</p>
    </div>
</body>
</html>
