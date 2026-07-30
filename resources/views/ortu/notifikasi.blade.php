@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('scripts-head')
    @vite('resources/js/firebase-messaging.js')
@endpush

@section('content')
<div class="max-w-2xl space-y-3">
    <button onclick="aktifkanNotifikasi()" class="w-full bg-primary-50 text-primary-700 text-sm font-medium py-3 rounded-lg border border-primary-200 mb-2">
        🔔 Aktifkan Notifikasi Push di Perangkat Ini
    </button>

    @forelse ($notifikasi as $n)
        <form action="{{ route('ortu.notifikasi.baca', $n) }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left bg-white rounded-xl border {{ $n->dibaca_pada ? 'border-gray-100' : 'border-primary-300 bg-primary-50/30' }} shadow-sm p-4">
                <div class="flex justify-between items-start">
                    <p class="font-semibold text-gray-800 text-sm">{{ $n->judul }}</p>
                    @unless ($n->dibaca_pada)
                        <span class="w-2 h-2 rounded-full bg-primary-600 mt-1"></span>
                    @endunless
                </div>
                <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $n->pesan }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $n->created_at->diffForHumans() }}</p>
            </button>
        </form>
    @empty
        <p class="text-center text-gray-400 text-sm py-10">Belum ada notifikasi.</p>
    @endforelse

    {{ $notifikasi->links() }}
</div>
@endsection
