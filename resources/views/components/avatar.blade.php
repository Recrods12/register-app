@props(['name' => '', 'size' => 'md'])

@php
    $initials = collect(explode(' ', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $palette = [
        'from-sky-500 to-sky-700',
        'from-emerald-500 to-emerald-700',
        'from-orange-500 to-orange-600',
        'from-violet-500 to-violet-700',
        'from-rose-500 to-rose-700',
        'from-cyan-500 to-cyan-700',
    ];
    $gradient = $palette[abs(crc32($name ?? '')) % count($palette)];

    $sizes = [
        'sm' => 'h-9 w-9 text-sm',
        'md' => 'h-11 w-11 text-base',
        'lg' => 'h-14 w-14 text-lg',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span class="inline-flex {{ $sizeClass }} flex-none items-center justify-center rounded-2xl bg-gradient-to-br {{ $gradient }} font-bold text-white shadow-sm" aria-hidden="true">
    {{ $initials ?: '?' }}
</span>
