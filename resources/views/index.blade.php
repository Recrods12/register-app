@php
    $formatCountdown = function ($targetTime): string {
        $totalMinutes = (int) ceil(max(now()->diffInMinutes($targetTime, false), 0));
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}j {$minutes}m" : "{$hours}j";
        }

        return "{$totalMinutes} menit";
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-600 text-white font-bold">🏢</div>
                <h1 class="text-2xl font-black text-slate-900">Booking Ruang Rapat</h1>
            </div>
            
            <nav class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-slate-600">Hi,</span>
                        <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>
                    </div>
                    <a href="/bookings" class="px-4 py-2 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 font-semibold">
                        📋 Booking Saya
                    </a>
                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-semibold">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-semibold">
                        Login
                    </a>
                    <a href="/register" class="px-4 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 font-semibold">
                        Register
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Ruang Rapat (2/3 width) -->
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-slate-900">Daftar Ruang Rapat</h2>
                    <p class="text-slate-600 mt-2">Status real-time | Update setiap 30 detik</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($rooms as $room)
                        <div class="flex min-h-[300px] flex-col rounded-2xl overflow-hidden border transition-all hover:shadow-lg {{ $room['status'] === 'booked' ? 'border-red-200 bg-red-50' : 'border-green-200 bg-green-50' }}">
                            <!-- Header dengan Status -->
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900">Lantai {{ $room['floor'] }}</h3>
                                    <p class="text-slate-600 text-sm">{{ $room['name'] }}</p>
                                </div>
                                @if($room['status'] === 'booked')
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-red-200 animate-pulse">
                                        <span class="text-2xl">🔴</span>
                                        <span class="font-bold text-red-900">SEDANG DIGUNAKAN</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-green-200">
                                        <span class="text-2xl">🟢</span>
                                        <span class="font-bold text-green-900">TERSEDIA</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1">
                            <!-- Detail Booking Aktif -->
                            @if($room['activeBooking'])
                                <div class="px-6 pb-4">
                                    <div class="rounded-xl border border-red-300 bg-white p-4">
                                        <p class="text-xs font-bold text-red-600 uppercase tracking-wider">📌 Sedang Digunakan</p>
                                        <h4 class="text-lg font-bold text-slate-900 mt-2">{{ $room['activeBooking']->title }}</h4>
                                        <p class="text-sm text-slate-600 mt-1">Perihal: <span class="font-semibold">{{ $room['activeBooking']->description ?: '-' }}</span></p>
                                        <div class="flex gap-4 mt-3 text-sm">
                                            <div>
                                                <p class="text-xs text-slate-500 font-semibold">Mulai</p>
                                                <p class="font-semibold text-slate-900">{{ $room['activeBooking']->start_time->format('H:i') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 font-semibold">Selesai</p>
                                                <p class="font-semibold text-slate-900">{{ $room['activeBooking']->end_time->format('H:i') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 font-semibold">Sisa Waktu</p>
                                                @php
                                                    $remainingMinutes = (int) ceil(max(now()->diffInMinutes($room['activeBooking']->end_time, false), 0));
                                                @endphp
                                                <p class="font-semibold text-red-600">
                                                    @if($remainingMinutes > 0)
                                                        {{ $formatCountdown($room['activeBooking']->end_time) }}
                                                    @else
                                                        Selesai
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Next Booking Info -->
                            @if($room['nextBooking'] && !$room['activeBooking'])
                                <div class="px-6 pb-4">
                                    <div class="rounded-xl border border-blue-300 bg-white p-4">
                                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">📅 Booking Selanjutnya</p>
                                        <h4 class="text-lg font-bold text-slate-900 mt-2">{{ $room['nextBooking']->title }}</h4>
                                        <p class="text-sm text-slate-600 mt-1">Perihal: <span class="font-semibold">{{ $room['nextBooking']->description ?: '-' }}</span></p>
                                        <div class="flex gap-4 mt-3 text-sm">
                                            <div>
                                                <p class="text-xs text-slate-500 font-semibold">Mulai</p>
                                                <p class="font-semibold text-slate-900">{{ $room['nextBooking']->start_time->format('H:i') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 font-semibold">Dalam</p>
                                                @php
                                                    $diffMinutes = (int) ceil(max(now()->diffInMinutes($room['nextBooking']->start_time, false), 0));
                                                @endphp
                                                @if($diffMinutes > 0)
                                                    <p class="font-semibold text-blue-600">
                                                        {{ $formatCountdown($room['nextBooking']->start_time) }}
                                                    </p>
                                                @else
                                                    <p class="font-semibold text-red-600">Sudah dimulai</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            </div>

                            <!-- Action Buttons -->
                            @auth
                                <div class="px-6 py-4 border-t border-slate-200 bg-white">
                                    @if($room['status'] === 'booked')
                                        <div class="block w-full text-center py-3 rounded-lg bg-slate-300 text-slate-600 font-bold cursor-not-allowed">
                                            Ruang Sedang Digunakan
                                        </div>
                                    @else
                                        <a href="/bookings/create?room_id={{ $room['id'] }}" class="block w-full text-center py-3 rounded-lg bg-sky-600 text-white font-bold hover:bg-sky-700 transition">
                                            + Booking Ruang Ini
                                        </a>
                                    @endif
                                </div>
                            @endauth
                        </div>
                    @endforeach
                </div>

                @guest
                    <div class="mt-6">
                        <a href="/login" class="block w-full text-center rounded-2xl bg-slate-300 px-6 py-4 font-bold text-slate-700 transition hover:bg-slate-400">
                            Login untuk Booking
                        </a>
                    </div>
                @endguest
            </div>

            <!-- Right Column: Activity Log (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="sticky top-20">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2">
                            📊 Log Aktivitas
                        </h3>
                        
                        @if($activeBookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($activeBookings as $booking)
                                    <div class="rounded-lg border-l-4 border-l-red-500 bg-red-50 p-4">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm">{{ $booking->title }}</p>
                                                <p class="text-xs text-slate-600 mt-1">👤 {{ $booking->user->name }}</p>
                                            </div>
                                            <span class="px-2 py-1 rounded bg-red-200 text-red-900 font-bold text-xs">LIVE</span>
                                        </div>
                                        <div class="space-y-1 text-xs text-slate-600">
                                            <p>🏢 {{ $booking->room->name }} (Lantai {{ $booking->room->floor }})</p>
                                            <p>⏰ {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                                            @php
                                                $minutesUntilEnd = (int) ceil(max(now()->diffInMinutes($booking->end_time, false), 0));
                                            @endphp
                                            <p class="text-red-600 font-semibold">
                                                @if($minutesUntilEnd > 0)
                                                    Berakhir dalam: {{ $formatCountdown($booking->end_time) }}
                                                @else
                                                    Booking selesai
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-4xl mb-2">😊</p>
                                <p class="text-slate-600 text-sm">Semua ruang kosong</p>
                                <p class="text-slate-500 text-xs mt-2">Tidak ada booking aktif saat ini</p>
                            </div>
                        @endif

                        <!-- Info Box -->
                        <div class="mt-6 rounded-lg bg-sky-50 border border-sky-200 p-4">
                            <p class="text-xs font-bold text-sky-900 uppercase mb-2">💡 Tips</p>
                            <ul class="text-xs text-sky-900 space-y-1">
                                <li>✓ Durasi setiap booking maksimal 24 jam</li>
                                <li>✓ Tidak bisa overlap dengan booking lain</li>
                                <li>✓ Status ruang diperbarui secara real-time</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
                        <h3 class="text-xl font-black text-slate-900 mb-4">Daftar Booking</h3>

                        @if($bookingSchedules->count() > 0)
                            <div class="space-y-3">
                                @foreach($bookingSchedules as $booking)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm">{{ $booking->title }}</p>
                                                <p class="text-xs text-slate-600 mt-1">{{ $booking->room->name }} (Lantai {{ $booking->room->floor }})</p>
                                            </div>
                                            @if($booking->isActive())
                                                <span class="rounded-full bg-red-100 px-2 py-1 text-[11px] font-bold text-red-700">AKTIF</span>
                                            @else
                                                <span class="rounded-full bg-blue-100 px-2 py-1 text-[11px] font-bold text-blue-700">TERJADWAL</span>
                                            @endif
                                        </div>
                                        <div class="mt-3 space-y-1 text-xs text-slate-600">
                                            <p>Pemesan: {{ $booking->user->name }}</p>
                                            <p>Tanggal: {{ $booking->start_time->translatedFormat('d M Y') }}</p>
                                            <p>Jam: {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center">
                                <p class="text-sm font-semibold text-slate-700">Belum ada booking terjadwal</p>
                                <p class="mt-1 text-xs text-slate-500">Nama pemesan, tanggal, dan jam booking akan tampil di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @auth
            <!-- CTA: Lihat Booking Saya -->
            <div class="mt-12 rounded-2xl border-2 border-sky-300 bg-sky-50 p-8 text-center">
                <h3 class="text-2xl font-black text-slate-900">📋 Lihat Semua Booking Anda</h3>
                <p class="text-slate-600 mt-2">Kelola, edit, atau batalkan booking ruang rapat Anda</p>
                <a href="/bookings" class="inline-block mt-6 px-8 py-3 rounded-lg bg-sky-600 text-white font-bold hover:bg-sky-700">
                    Lihat Booking Saya
                </a>
            </div>
        @endauth
    </main>

    <script>
        // Auto-refresh setiap 30 detik
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>

