<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isAdmin ? 'Dashboard Admin' : 'Dashboard' }} - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-600 text-white font-bold text-lg">🏢</div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900">Booking Ruang Rapat</h1>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $isAdmin ? 'Panel Admin' : 'Dashboard User' }}</p>
                </div>
            </div>

            <nav class="flex items-center gap-4">
                <div class="text-sm">
                    <p class="text-slate-600">Halo,</p>
                    <p class="font-bold text-slate-900">{{ auth()->user()->name }}</p>
                </div>
                <a href="/profile" class="rounded-lg border border-slate-200 px-4 py-2 font-semibold text-slate-600 transition hover:bg-slate-100">
                    Edit Profil
                </a>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-semibold">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-10">
        <div class="mb-12 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 text-white p-8">
            <h2 class="text-3xl font-black mb-2">
                {{ $isAdmin ? 'Panel Admin Booking' : 'Selamat Datang, ' . auth()->user()->name . '!' }}
            </h2>
            <p class="text-sky-100 mb-6">
                {{ $isAdmin ? 'Pantau semua booking, kelola data pemesan, dan export rekap harian atau rentang tanggal.' : 'Kelola booking ruang rapat Anda dengan mudah dan cepat' }}
            </p>
            @if($isAdmin)
                <div class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <a href="/bookings/create" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                            + Buat Booking Baru
                        </a>
                        <a href="/bookings" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-900/10 bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                            Kelola Semua Booking
                        </a>
                        <a href="{{ route('rooms.index') }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-amber-400 bg-amber-50 px-6 py-3 text-center font-extrabold text-amber-900 shadow-[0_14px_28px_rgba(217,119,6,0.16)] transition hover:-translate-y-0.5 hover:bg-amber-100 hover:text-amber-950">
                            Lihat Semua Ruang
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-900/10 bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                            Kelola User
                        </a>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <a href="{{ route('admin.calendar') }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-900/10 bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                            Kalender
                        </a>
                        <a href="{{ route('admin.activity-logs') }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-300 bg-slate-50 px-6 py-3 text-center font-extrabold text-slate-700 shadow-[0_14px_28px_rgba(15,23,42,0.12)] transition hover:-translate-y-0.5 hover:bg-slate-100">
                            Audit Log
                        </a>
                        <a href="{{ route('admin.backup') }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-900/10 bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                            Backup Data
                        </a>
                        <a href="{{ route('bookings.export', ['start_date' => $filters['start_date'], 'end_date' => $filters['end_date'], 'user_id' => $filters['user_id'], 'search' => $filters['search']]) }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-emerald-300 bg-emerald-50 px-6 py-3 text-center font-extrabold text-emerald-700 shadow-[0_14px_28px_rgba(12,148,90,0.16)] transition hover:-translate-y-0.5 hover:bg-emerald-100">
                            Export Excel
                        </a>
                    </div>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <a href="/bookings/create" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                        + Buat Booking Baru
                    </a>
                    <a href="/bookings" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-900/10 bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                        Tampilkan Booking Saya
                    </a>
                    <a href="/profile" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-slate-900/10 bg-white px-6 py-3 text-center font-extrabold text-sky-700 shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-sky-50">
                        Edit Profil
                    </a>
                    <a href="{{ route('rooms.index') }}" class="inline-flex min-h-[56px] items-center justify-center rounded-2xl border border-amber-400 bg-amber-50 px-6 py-3 text-center font-extrabold text-amber-900 shadow-[0_14px_28px_rgba(217,119,6,0.16)] transition hover:-translate-y-0.5 hover:bg-amber-100 hover:text-amber-950">
                        Lihat Semua Ruang
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
            <div class="rounded-2xl bg-white border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-600">Total Booking</h3>
                    <span class="text-2xl">📋</span>
                </div>
                <p class="text-4xl font-black text-slate-900">{{ $totalBookings }}</p>
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-600">Sedang Berlangsung</h3>
                    <span class="text-2xl">🔴</span>
                </div>
                <p class="text-4xl font-black text-red-600">{{ $activeBookings }}</p>
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-600">Mendatang</h3>
                    <span class="text-2xl">🟦</span>
                </div>
                <p class="text-4xl font-black text-blue-600">{{ $upcomingBookings }}</p>
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-600">Ruang Tersedia</h3>
                    <span class="text-2xl">🟢</span>
                </div>
                <p class="text-4xl font-black text-green-600">{{ $availableRooms }}</p>
            </div>
        </div>

        @if($isAdmin)
            <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-8 mb-10">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">Rekap Booking Admin</h3>
                            <p class="mt-1 text-sm text-slate-600">Filter data booking berdasarkan tanggal tunggal atau rentang tanggal, lalu export ke Excel-compatible CSV.</p>
                        </div>
                        <a href="{{ route('bookings.export', ['start_date' => $filters['start_date'], 'end_date' => $filters['end_date'], 'user_id' => $filters['user_id'], 'search' => $filters['search']]) }}" class="rounded-lg bg-emerald-500 px-4 py-2 font-bold text-white hover:bg-emerald-600 transition">
                            Export Excel
                        </a>
                    </div>

                    <form method="GET" action="/" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-input">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-input">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Pemesan</label>
                            <select name="user_id" class="form-input">
                                <option value="">Semua pemesan</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected((string) $filters['user_id'] === (string) $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Search</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ $filters['search'] }}"
                                placeholder="Cari bidang, perihal, ruang, nama..."
                                class="form-input"
                            >
                        </div>
                        <div class="flex items-end gap-3">
                            <button type="submit" class="btn-primary w-full justify-center">Tampilkan</button>
                        </div>
                    </form>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-left text-slate-500">
                                    <th class="px-3 py-3 font-semibold">Tanggal</th>
                                    <th class="px-3 py-3 font-semibold">Jam</th>
                                    <th class="px-3 py-3 font-semibold">Bidang</th>
                                    <th class="px-3 py-3 font-semibold">Nama Pemesan</th>
                                    <th class="px-3 py-3 font-semibold">Perihal</th>
                                    <th class="px-3 py-3 font-semibold">Ruang</th>
                                    <th class="px-3 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportBookings as $booking)
                                    <tr class="border-b border-slate-100 align-top">
                                        <td class="px-3 py-3 font-semibold text-slate-900">{{ $booking->start_time->format('d M Y') }}</td>
                                        <td class="px-3 py-3 text-slate-600">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</td>
                                        <td class="px-3 py-3 text-slate-700">{{ $booking->title ?: '-' }}</td>
                                        <td class="px-3 py-3">
                                            <p class="font-semibold text-slate-900">{{ $booking->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $booking->user->email }}</p>
                                        </td>
                                        <td class="px-3 py-3 text-slate-700">{{ $booking->description ?: '-' }}</td>
                                        <td class="px-3 py-3 text-slate-700">{{ $booking->room->name }}<div class="text-xs text-slate-500">Lantai {{ $booking->room->floor }}</div></td>
                                        <td class="px-3 py-3">
                                            @if($booking->isActive())
                                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">Berlangsung</span>
                                            @elseif($booking->isEnded())
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Selesai</span>
                                            @else
                                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">Mendatang</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-8 text-center text-slate-500">Tidak ada data booking untuk filter yang dipilih.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="space-y-8">
                    <div class="rounded-2xl bg-white border border-slate-200 p-6">
                        <h3 class="text-xl font-black text-slate-900 mb-4">Booking Sedang Berlangsung</h3>
                        <div class="space-y-3">
                            @forelse($activeBookingsList as $booking)
                                <div class="rounded-xl border-2 border-red-300 bg-red-50 p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-slate-900">{{ $booking->room->name }} (Lantai {{ $booking->room->floor }})</h4>
                                            <p class="text-sm text-slate-600 mt-1">{{ $booking->title }}</p>
                                            <p class="text-xs text-slate-500 mt-1">Pemesan: {{ $booking->user->name }}</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full bg-red-200 text-red-900 font-bold text-xs">BERLANGSUNG</span>
                                    </div>
                                    <div class="flex gap-2 mt-3">
                                        <a href="/bookings/{{ $booking->id }}/edit" class="flex-1 text-center px-3 py-2 rounded-lg bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition">Ganti Jadwal</a>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center text-sm text-slate-500">Tidak ada booking aktif saat ini.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white border border-slate-200 p-6">
                        <h3 class="text-xl font-black text-slate-900 mb-4">Ruang Tersedia Sekarang</h3>
                        <div class="space-y-3">
                            @foreach($availableRoomsList as $room)
                                <div class="rounded-xl border-2 border-green-300 bg-green-50 p-4">
                                    <h4 class="font-bold text-slate-900">{{ $room['name'] }}</h4>
                                    <p class="text-sm text-slate-600 mt-1">Lantai {{ $room['floor'] }}</p>
                                    <a href="/bookings/create?room_id={{ $room['id'] }}" class="block w-full mt-3 px-3 py-2 text-center rounded-lg bg-green-600 text-white font-semibold text-sm hover:bg-green-700 transition">Booking Sekarang</a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white border border-slate-200 p-6">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <h3 class="text-xl font-black text-slate-900">Audit Log Terbaru</h3>
                            <a href="{{ route('admin.activity-logs') }}" class="text-sm font-bold text-sky-600 hover:text-sky-700">Lihat semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($activityLogs as $log)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <p class="text-xs font-black uppercase tracking-wide text-sky-600">{{ str_replace('_', ' ', $log->action) }}</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ $log->message }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $log->user?->name ?? 'Sistem' }} | {{ $log->created_at->format('d M Y H:i') }}</p>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center text-sm text-slate-500">Belum ada aktivitas.</div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    @if($activeBookingsList->isNotEmpty())
                        <div>
                            <h3 class="text-xl font-black text-slate-900 mb-4">🔴 Booking Sedang Berlangsung</h3>
                            <div class="space-y-3">
                                @foreach($activeBookingsList as $booking)
                                    <div class="rounded-xl border-2 border-red-300 bg-red-50 p-4">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-bold text-slate-900">{{ $booking->room->name }} (Lantai {{ $booking->room->floor }})</h4>
                                                <p class="text-sm text-slate-600 mt-1">{{ $booking->title }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full bg-red-200 text-red-900 font-bold text-xs">BERLANGSUNG</span>
                                        </div>
                                        <div class="flex gap-4 mt-3 text-sm text-slate-600">
                                            <span>⏰ {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</span>
                                        </div>
                                        <div class="flex gap-2 mt-3">
                                            <a href="/bookings/{{ $booking->id }}/edit" class="flex-1 text-center px-3 py-2 rounded-lg bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition">✏️ Ganti Jadwal</a>
                                            <a href="{{ route('bookings.activity', $booking) }}" class="flex-1 text-center px-3 py-2 rounded-lg bg-white text-slate-800 font-semibold text-sm hover:bg-slate-100 transition">📷 Upload Kegiatan</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($upcomingBookingsList->isNotEmpty())
                        <div>
                            <h3 class="text-xl font-black text-slate-900 mb-4">🟦 Booking Mendatang</h3>
                            <div class="space-y-3">
                                @foreach($upcomingBookingsList as $booking)
                                    <div class="rounded-xl border-2 border-blue-300 bg-blue-50 p-4">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-bold text-slate-900">{{ $booking->room->name }} (Lantai {{ $booking->room->floor }})</h4>
                                                <p class="text-sm text-slate-600 mt-1">{{ $booking->title }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full bg-blue-200 text-blue-900 font-bold text-xs">MENDATANG</span>
                                        </div>
                                        <div class="flex gap-4 mt-3 text-sm text-slate-600">
                                            <span>📅 {{ $booking->start_time->format('d M Y') }}</span>
                                            <span>⏰ {{ $booking->start_time->format('H:i') }}</span>
                                        </div>
                                        <div class="flex gap-2 mt-3">
                                            <a href="/bookings/{{ $booking->id }}/edit" class="flex-1 text-center px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition">✏️ Edit</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($totalBookings == 0)
                        <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-12 text-center">
                            <p class="text-4xl mb-4">📭</p>
                            <h3 class="text-xl font-bold text-slate-900">Belum Ada Booking</h3>
                            <p class="text-slate-600 mt-2">Mulai buat booking ruang rapat sekarang!</p>
                            <a href="/bookings/create" class="inline-block mt-6 px-8 py-3 rounded-lg bg-sky-600 text-white font-bold hover:bg-sky-700">+ Buat Booking Pertama</a>
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-4">🟢 Ruang Tersedia Sekarang</h3>
                    <div class="space-y-3">
                        @foreach($availableRoomsList as $room)
                            <div class="rounded-xl border-2 border-green-300 bg-green-50 p-4">
                                <h4 class="font-bold text-slate-900">{{ $room['name'] }}</h4>
                                <p class="text-sm text-slate-600 mt-1">Lantai {{ $room['floor'] }}</p>
                                <a href="/bookings/create?room_id={{ $room['id'] }}" class="block w-full mt-3 px-3 py-2 text-center rounded-lg bg-green-600 text-white font-semibold text-sm hover:bg-green-700 transition">Booking Sekarang</a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 rounded-2xl bg-white border border-slate-200 p-6">
                        <h4 class="font-bold text-slate-900 mb-4">📊 Info Penting</h4>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li>✓ Durasi booking max 24 jam</li>
                            <li>✓ Tidak bisa overlap waktu</li>
                            <li>✓ Bisa edit jika ada slot kosong</li>
                            <li>✓ Bisa batalkan kapan saja</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </main>
</body>
</html>
