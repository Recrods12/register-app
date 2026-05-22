<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isAdmin ? 'Kelola Semua Booking' : 'Booking Saya' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-600 text-white font-bold">🏢</a>
                <h1 class="text-2xl font-black text-slate-900">{{ $isAdmin ? 'Kelola Semua Booking' : 'Booking Saya' }}</h1>
            </div>

            <nav class="flex items-center gap-4">
                <a href="/" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-semibold">← Kembali</a>
                <a href="/bookings/create" class="px-4 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 font-semibold">+ Booking Baru</a>
                @if($isAdmin)
                    <a href="{{ route('bookings.export', ['start_date' => $filters['start_date'], 'end_date' => $filters['end_date'], 'user_id' => $filters['user_id']]) }}" class="px-4 py-2 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 font-semibold">Export Excel</a>
                @endif
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-semibold">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-10">
        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700 font-semibold">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="mb-10">
            <h2 class="text-3xl font-black text-slate-900">{{ $isAdmin ? 'Daftar Semua Booking' : 'Daftar Booking Anda' }}</h2>
            <p class="text-slate-600 mt-2">
                {{ $isAdmin ? 'Admin dapat melihat, mengedit, menghapus, dan merekap semua booking dari seluruh user.' : 'Kelola semua booking ruang rapat Anda di sini' }}
            </p>
        </div>

        @if($isAdmin)
            <form method="GET" action="/bookings" class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">
                <div class="grid gap-4 md:grid-cols-4">
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
                    <div class="flex items-end gap-3">
                        <button type="submit" class="btn-primary w-full justify-center">Tampilkan Data</button>
                    </div>
                </div>
            </form>
        @endif

        @if($bookings->isEmpty())
            <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="text-5xl mb-4">📭</p>
                <h3 class="text-2xl font-black text-slate-900">Belum Ada Booking</h3>
                <p class="text-slate-600 mt-3">{{ $isAdmin ? 'Belum ada data booking untuk filter yang dipilih.' : 'Mulai buat booking ruang rapat sekarang untuk melihat jadwal Anda' }}</p>
                <a href="/bookings/create" class="inline-block mt-6 px-8 py-3 rounded-lg bg-sky-600 text-white font-bold hover:bg-sky-700">+ Buat Booking</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    @php($cardClass = $booking->isActive() ? 'border-red-300 bg-red-50' : ($booking->isEnded() ? 'border-slate-200 bg-slate-100 opacity-75' : 'border-blue-300 bg-blue-50'))
                    <div class="rounded-2xl border-2 {{ $cardClass }} p-6">
                        <div class="flex items-start justify-between gap-6 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="text-2xl">{{ $booking->room->floor == 1 ? '1️⃣' : ($booking->room->floor == 2 ? '2️⃣' : ($booking->room->floor == 3 ? '3️⃣' : '4️⃣')) }}</div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900">{{ $booking->room->name }}</h3>
                                        <p class="text-sm text-slate-600">Lantai {{ $booking->room->floor }}</p>
                                    </div>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mt-2">{{ $booking->title }}</h4>
                                @if($booking->description)
                                    <p class="text-sm text-slate-600 mt-1">Perihal: {{ $booking->description }}</p>
                                @endif
                                @if($isAdmin)
                                    <div class="mt-3 rounded-lg bg-white/80 px-4 py-3">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Pemesan</p>
                                        <p class="mt-1 font-semibold text-slate-900">{{ $booking->user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $booking->user->email }}</p>
                                    </div>
                                @endif
                            </div>

                            <div>
                                @if($booking->isActive())
                                    <span class="inline-block px-4 py-2 rounded-full bg-red-200 text-red-900 font-bold text-sm">🔴 BERLANGSUNG</span>
                                @elseif($booking->isEnded())
                                    <span class="inline-block px-4 py-2 rounded-full bg-slate-300 text-slate-900 font-bold text-sm">⚪ SELESAI</span>
                                @else
                                    <span class="inline-block px-4 py-2 rounded-full bg-blue-200 text-blue-900 font-bold text-sm">🟦 MENDATANG</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white rounded-lg p-4">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</p>
                                <p class="mt-2 font-bold text-slate-900">{{ $booking->start_time->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</p>
                                <p class="mt-2 font-bold text-slate-900">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Durasi</p>
                                <p class="mt-2 font-bold text-slate-900">{{ $booking->end_time->diff($booking->start_time)->format('%h jam %i menit') }}</p>
                            </div>
                        </div>

                        @if($booking->activity_photo_path)
                            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="flex items-start gap-4">
                                    <img src="{{ asset('storage/' . $booking->activity_photo_path) }}" alt="Foto kegiatan" class="h-24 w-24 flex-none rounded-xl border border-slate-200 bg-white object-contain p-2 sm:h-28 sm:w-28">
                                    <div class="flex-1">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Dokumentasi Kegiatan</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">Foto kegiatan sudah terupload.</p>
                                        @if($booking->activity_note)
                                            <p class="mt-2 text-sm text-slate-600">{{ $booking->activity_note }}</p>
                                        @endif
                                        <a href="{{ asset('storage/' . $booking->activity_photo_path) }}" target="_blank" class="mt-3 inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-200">
                                            Lihat Foto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3 border-t border-white pt-4">
                            @if(!$booking->isEnded() || $isAdmin)
                                <a href="/bookings/{{ $booking->id }}/edit" class="flex-1 px-4 py-3 text-center rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                    ✏️ {{ $booking->isActive() ? 'Ganti Jadwal' : 'Edit Jadwal' }}
                                </a>
                            @endif

                            @if($booking->isActive() || $booking->isEnded())
                                <a href="{{ route('bookings.activity', $booking) }}" class="flex-1 px-4 py-3 text-center rounded-lg bg-white text-slate-800 font-bold hover:bg-slate-100 transition">
                                    📷 {{ $booking->activity_photo_path ? 'Edit Upload Kegiatan' : 'Upload Kegiatan' }}
                                </a>
                            @endif

                            @if($booking->activity_photo_path)
                                <form method="POST" action="{{ route('bookings.activity.delete', $booking) }}" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus foto kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full rounded-lg border border-amber-700 bg-amber-600 px-4 py-3 font-bold text-white shadow-sm transition hover:bg-amber-700">
                                        🗑 Hapus Foto
                                    </button>
                                </form>
                            @endif

                            @if($booking->isEnded() && !$isAdmin)
                                <div class="flex-1">
                                    <button type="button" disabled class="w-full cursor-not-allowed rounded-lg bg-slate-300 px-4 py-3 font-bold text-slate-600 opacity-90">⚪ Selesai</button>
                                </div>
                            @else
                                <form method="POST" action="/bookings/{{ $booking->id }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus booking ini?')" class="w-full px-4 py-3 rounded-lg bg-red-600 text-white font-bold hover:bg-red-700 transition">
                                        🗑️ {{ $isAdmin ? 'Hapus Booking' : 'Batalkan' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-6">
                <h4 class="font-bold text-slate-900 mb-4">📊 Ringkasan {{ $isAdmin ? 'Booking Admin' : 'Booking Anda' }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-slate-600">Total Booking</p>
                        <p class="text-3xl font-black text-slate-900">{{ $bookings->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Sedang Berlangsung</p>
                        <p class="text-3xl font-black text-red-600">{{ $bookings->filter(fn($b) => $b->isActive())->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Mendatang</p>
                        <p class="text-3xl font-black text-blue-600">{{ $bookings->filter(fn($b) => !$b->isEnded() && !$b->isActive())->count() }}</p>
                    </div>
                </div>
            </div>
        @endif
    </main>
</body>
</html>
