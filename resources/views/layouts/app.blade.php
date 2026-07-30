<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#09090b">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIPRESI SISWA</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <link rel="icon" href="{{ asset('icons/icon-192.png') }}">

    @vite(['resources/css/app.css'])
    @stack('scripts-head')
    @vite(['resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-gray-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-dvh overflow-hidden w-full">
        {{-- Sidebar dengan Background Hitam Pekat & Teks Terang --}}
        <aside
            class="fixed inset-y-0 left-0 z-30 w-72 bg-black text-white transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-xl border-r border-neutral-800"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center gap-3 px-6 h-20 border-b border-neutral-800 bg-neutral-950">
                <img src="{{ $logoSekolahGlobal }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow">
                <div>
                    <p class="font-bold leading-tight text-white tracking-wide text-sm">SIPRESI SISWA</p>
                    <p class="text-[11px] text-neutral-400 font-medium">{{ $namaSekolahGlobal }}</p>
                </div>
            </div>

            <nav class="px-3.5 py-5 space-y-1 overflow-y-auto" style="height: calc(100% - 5rem)">
                @include('components.nav-menu')
            </nav>
        </aside>

        <div class="fixed inset-0 bg-black/40 z-20 lg:hidden" x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"></div>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-5 lg:px-8 shrink-0 shadow-sm">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>

                <h1 class="text-xl font-semibold text-gray-800 hidden sm:block">@yield('page-title', 'Dashboard')</h1>

                <div class="flex items-center gap-4 ml-auto">
                    <div class="flex items-center gap-3 pl-1">
                        <div class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-bold shrink-0">
                            {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => mb_substr($w,0,1))->take(2)->implode('') }}
                        </div>
                        <div class="hidden sm:block leading-tight">
                            <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-400">{{ auth()->user()->role->nama }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-600 font-medium transition px-2.5 py-2 rounded-lg hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-50">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" /></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>