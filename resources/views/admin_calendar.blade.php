<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Booking - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-600">Panel Admin</p>
                <h1 class="text-2xl font-black text-slate-900">Kalender Booking</h1>
            </div>
            <nav class="flex flex-wrap gap-3">
                <a href="/" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Kelola User</a>
                <a href="{{ route('admin.activity-logs') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">Audit Log</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Jadwal Bulanan</h2>
                    <p class="mt-1 text-sm text-slate-600">Lihat kepadatan booking per tanggal dan per ruang rapat.</p>
                </div>
                <form method="GET" action="{{ route('admin.calendar') }}" class="grid gap-3 md:grid-cols-[180px_260px_auto]">
                    <input type="month" name="month" value="{{ $month }}" class="form-input">
                    <select name="room_id" class="form-input">
                        <option value="">Semua ruang</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected((string) $selectedRoomId === (string) $room->id)>{{ $room->name }} - Lantai {{ $room->floor }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-xl bg-sky-600 px-5 py-3 font-bold text-white hover:bg-sky-700">Tampilkan</button>
                </form>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach($calendarDays as $day)
                    @php($dateKey = $day->toDateString())
                    @php($dayBookings = $bookingsByDate->get($dateKey, collect()))
                    <div class="min-h-[190px] rounded-2xl border {{ $day->isToday() ? 'border-sky-400 bg-sky-50' : 'border-slate-200 bg-slate-50' }} p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $day->translatedFormat('D') }}</p>
                                <h3 class="text-xl font-black text-slate-900">{{ $day->format('d M') }}</h3>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-700">{{ $dayBookings->count() }} booking</span>
                        </div>

                        <div class="space-y-2">
                            @forelse($dayBookings as $booking)
                                <a href="{{ route('bookings.edit', $booking) }}" class="block rounded-xl border border-slate-200 bg-white p-3 transition hover:border-sky-300 hover:shadow-sm">
                                    <p class="font-bold text-slate-900">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $booking->room->name }} / {{ $booking->title }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $booking->user->name }} - {{ $booking->description ?: 'Tanpa perihal' }}</p>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-white/70 p-3 text-sm text-slate-500">Belum ada booking.</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>
