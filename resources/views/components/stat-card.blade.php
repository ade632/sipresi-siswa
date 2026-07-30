@props(['label' => '', 'value' => 0, 'icon' => '📊', 'color' => 'primary'])

@php
    $colors = [
        'primary' => 'bg-primary-50 text-primary-700',
        'green' => 'bg-green-50 text-green-700',
        'yellow' => 'bg-yellow-50 text-yellow-700',
        'red' => 'bg-red-50 text-red-700',
        'blue' => 'bg-blue-50 text-blue-700',
        'gray' => 'bg-gray-100 text-gray-700',
    ];
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
    <div class="w-11 h-11 rounded-lg flex items-center justify-center text-xl shrink-0 {{ $colors[$color] ?? $colors['primary'] }}">
        {{ $icon }}
    </div>
    <div>
        <p class="text-2xl font-bold text-gray-800 leading-tight">{{ $value }}</p>
        <p class="text-xs text-gray-500">{{ $label }}</p>
    </div>
</div>
