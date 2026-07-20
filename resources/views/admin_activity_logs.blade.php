<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="page-wrap">
    <x-admin-header title="Audit Log" active="logs" />

    <main id="main" class="shell animate-fade-in px-4 py-8">
        @php
            $initialLogs = $logs->take(3);
            $restLogs = $logs->slice(3);
        @endphp

        <section class="hero-card animate-scale-in rounded-[34px] p-6 md:p-8">
            <div>
                <span class="eyebrow">Keamanan &amp; Traceability</span>
                <h2 class="section-title mt-4">Riwayat Aktivitas Sistem</h2>
                <p class="section-copy mt-2">Mencatat aktivitas penting seperti membuat, mengubah, menghapus booking, dan pembaruan akun pengguna.</p>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Total Aktivitas</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $logs->count() }}</p>
                </article>
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Hari Ini</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $logs->filter(fn ($log) => $log->created_at->isToday())->count() }}</p>
                </article>
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Terkait Booking</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $logs->filter(fn ($log) => $log->booking_id)->count() }}</p>
                </article>
            </div>
        </section>

        @if($logs->isEmpty())
            <div class="mt-6">
                <x-empty-state
                    icon="📝"
                    title="Belum ada aktivitas tercatat"
                    description="Riwayat aktivitas sistem akan muncul di sini ketika ada perubahan pada booking atau akun." />
            </div>
        @else
            <div class="stagger mt-6 space-y-4" data-stagger>
                @foreach($initialLogs as $log)
                    <x-log-item :log="$log" />
                @endforeach
            </div>

            @if($restLogs->isNotEmpty())
                <div id="extra-logs" class="mt-6 hidden space-y-4" aria-hidden="true">
                    @foreach($restLogs as $log)
                        <x-log-item :log="$log" class="animate-fade-in-up" />
                    @endforeach
                </div>

                <div class="mt-6 flex justify-center">
                    <button
                        id="see-more-logs"
                        type="button"
                        class="btn-soft"
                        aria-expanded="false"
                        aria-controls="extra-logs"
                    >
                        Lihat selengkapnya ({{ $restLogs->count() }})
                    </button>
                </div>
            @endif
        @endif
    </main>

    <script>
        (function () {
            const btn = document.getElementById('see-more-logs');
            const extra = document.getElementById('extra-logs');
            if (!btn || !extra) return;

            btn.addEventListener('click', function () {
                const isHidden = extra.classList.toggle('hidden');
                extra.setAttribute('aria-hidden', String(isHidden));
                btn.setAttribute('aria-expanded', String(!isHidden));
                btn.textContent = isHidden
                    ? 'Lihat selengkapnya ({{ $restLogs->count() }})'
                    : 'Sembunyikan';

                if (!isHidden) {
                    extra.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        })();
    </script>
</body>
</html>
