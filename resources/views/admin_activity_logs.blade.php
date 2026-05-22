<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-600">Panel Admin</p>
                <h1 class="text-2xl font-black text-slate-900">Audit Log</h1>
            </div>
            <nav class="flex flex-wrap gap-3">
                <a href="/" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Kelola User</a>
                <a href="{{ route('admin.calendar') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Kalender</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)]">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-900">Riwayat Aktivitas Sistem</h2>
                <p class="mt-1 text-sm text-slate-600">Mencatat aktivitas penting seperti membuat, mengubah, menghapus booking, dan update user.</p>
            </div>

            <div class="space-y-3">
                @forelse($logs as $log)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-black uppercase text-sky-700">{{ str_replace('_', ' ', $log->action) }}</span>
                                    <p class="font-bold text-slate-900">{{ $log->message }}</p>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">
                                    Oleh: {{ $log->user?->name ?? 'Sistem' }}
                                    @if($log->booking?->room)
                                        | Booking: {{ $log->booking->room->name }} - {{ $log->booking->title }}
                                    @endif
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-500">
                        Belum ada aktivitas yang tercatat.
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
