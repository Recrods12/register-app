@props(['title' => 'Tidak ada data', 'description' => '', 'icon' => '📭'])

<div class="flex animate-fade-in-up flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white/70 px-6 py-14 text-center">
    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl" aria-hidden="true">{{ $icon }}</div>
    <h3 class="mt-5 text-lg font-bold text-slate-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-2 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @endif
</div>
