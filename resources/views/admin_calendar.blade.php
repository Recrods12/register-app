<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Booking - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="page-wrap">
    <x-admin-header title="Kalender Booking" active="calendar" />

    <main id="main" class="shell animate-fade-in px-4 py-8">
        @php
            $monthBookings = $bookingsByDate->flatten();
            $totalBookings = $monthBookings->count();
            $roomsUsed = $monthBookings->pluck('room_id')->unique()->count();
            $daysScheduled = $bookingsByDate->filter(fn ($items) => $items->isNotEmpty())->count();
        @endphp

        <section class="hero-card animate-scale-in rounded-[34px] p-6 md:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="eyebrow">Jadwal Bulanan</span>
                    <h2 class="section-title mt-4">Peta Kepadatan Booking</h2>
                    <p class="section-copy mt-2">Lihat kepadatan reservasi per tanggal dan per ruang rapat dalam satu tampilan.</p>
                </div>

                <form method="GET" action="{{ route('admin.calendar') }}" data-loading-on-submit class="grid gap-3 md:grid-cols-[180px_260px_auto]">
                    <input type="month" name="month" value="{{ $month }}" aria-label="Pilih bulan" class="form-input">
                    <select name="room_id" aria-label="Pilih ruang rapat" class="form-input">
                        <option value="">Semua ruang</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected((string) $selectedRoomId === (string) $room->id)>{{ $room->name }} - Lantai {{ $room->floor }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary whitespace-nowrap">Tampilkan</button>
                </form>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Total Booking</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $totalBookings }}</p>
                </article>
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Ruang Terpakai</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $roomsUsed }}</p>
                </article>
                <article class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Hari Terjadwal</p>
                    <p class="mt-3 text-3xl font-black text-slate-950">{{ $daysScheduled }}</p>
                </article>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500">
                <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-sky-400"></span> Hari ini</span>
                <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-slate-300"></span> Tersedia</span>
                <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-emerald-400"></span> Ada booking</span>
            </div>
        </section>

        @if($totalBookings === 0)
            <div class="mt-6">
                <x-empty-state
                    icon="🗓️"
                    title="Belum ada booking di periode ini"
                    description="Coba ubah bulan atau ruang yang dipilih untuk melihat jadwal reservasi." />
            </div>
        @else
            <div class="stagger mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4" data-stagger>
                @foreach($calendarDays as $day)
                    @php($dateKey = $day->toDateString())
                    @php($dayBookings = $bookingsByDate->get($dateKey, collect()))
                    <div class="min-h-[190px] rounded-2xl border {{ $day->isToday() ? 'border-sky-400 bg-sky-50' : 'border-slate-200 bg-white/80' }} p-4 transition duration-300 hover:shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $day->translatedFormat('D') }}</p>
                                <h3 class="text-xl font-black text-slate-900">{{ $day->format('d M') }}</h3>
                            </div>
                            <span class="rounded-full {{ $dayBookings->isNotEmpty() ? 'bg-emerald-100 text-emerald-700' : 'bg-white text-slate-500' }} px-3 py-1 text-xs font-bold">{{ $dayBookings->count() }} booking</span>
                        </div>

                        <div class="space-y-2">
                            @forelse($dayBookings as $booking)
                                <a href="{{ route('bookings.edit', $booking) }}"
                                   class="block rounded-xl border border-slate-200 bg-white p-3 transition duration-200 hover:-translate-y-0.5 hover:scale-[1.02] hover:border-sky-300 hover:shadow-md focus-visible:ring-2 focus-visible:ring-sky-500">
                                    <p class="font-bold text-slate-900">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $booking->room->name }} / {{ $booking->title }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $booking->user->name }} · {{ $booking->description ?: 'Tanpa perihal' }}</p>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-white/70 p-3 text-sm text-slate-400">Tidak ada reservasi.</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
