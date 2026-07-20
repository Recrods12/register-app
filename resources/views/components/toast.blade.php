@props(['type' => 'success', 'message' => ''])

@php
    $variants = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'error' => 'border-red-200 bg-red-50 text-red-700',
        'info' => 'border-sky-200 bg-sky-50 text-sky-700',
    ];
    $variant = $variants[$type] ?? $variants['success'];
    $icon = $type === 'error' ? '!' : ($type === 'success' ? '✓' : 'i');
@endphp

<div data-toast role="status" class="toast-enter mb-5 flex items-start gap-3 rounded-2xl border px-4 py-3 font-semibold {{ $variant }}">
    <span aria-hidden="true" class="mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full bg-white/70 text-sm">{{ $icon }}</span>
    <span class="flex-1">{{ $message }}</span>
</div>
