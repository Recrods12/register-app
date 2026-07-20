@props(['log' => null])

@php
    $actor = $log->user?->name ?? 'Sistem';
@endphp

<div {{ $attributes->merge(['class' => 'feature-card rounded-3xl p-5 transition duration-300 hover:shadow-[0_22px_50px_rgba(15,23,42,0.10)]']) }}>
    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div class="flex items-start gap-4">
            <x-avatar :name="$actor" size="md" />
            <div class="min-w-0">
                <span class="inline-block rounded-full bg-sky-100 px-3 py-1 text-xs font-black uppercase tracking-wide text-sky-700">
                    {{ str_replace('_', ' ', $log->action) }}
                </span>
                <p class="mt-2 font-bold text-slate-950">{{ $log->message }}</p>
                <p class="mt-1 text-sm text-slate-500">
                    Oleh: {{ $actor }}
                    @if($log->booking?->room)
                        · Booking: {{ $log->booking->room->name }} - {{ $log->booking->title }}
                    @endif
                </p>
            </div>
        </div>
        <p class="whitespace-nowrap text-sm font-semibold text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</p>
    </div>
</div>
