<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    @vite('resources/css/app.css')
</head>
<body class="page-wrap">
    <header class="px-4 pt-4">
        <div class="shell glass-nav radius-28 flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-black bg-sky-100 text-sky-600">📍</div>
                <div>
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-500">Meeting Room</p>
                    <h1 class="text-xl font-black text-slate-900">Booking Ruang Rapat</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                <a href="/" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Beranda</a>
                <a href="/bookings" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Booking Saya</a>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="shell px-4 py-10 max-w-2xl">
        <div class="mb-10">
            <a href="/bookings" class="text-sky-600 hover:text-sky-700 font-semibold text-sm mb-4 inline-block">&larr; Kembali</a>
            <h2 class="text-3xl font-extrabold text-slate-950">Edit Booking</h2>
            <p class="mt-2 text-slate-600">Ubah jadwal booking Anda ke waktu yang kosong</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/bookings/{{ $booking->id }}" class="radius-28 border border-slate-200 p-6 space-y-6">
            @csrf
            @method('PUT')

            @if($isAdmin)
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Akun yang Membooking</label>
                    <select name="user_id" required class="form-input">
                        <option value="">Pilih user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($booking->user_id == $user->id)>{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Pilih Ruang Rapat</label>
                <select name="room_id" required class="form-input">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" @selected($booking->room_id == $room->id)>
                            Lantai {{ $room->floor }} - {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal & Jam Mulai</label>
                    <input type="datetime-local" name="start_time" value="{{ $booking->start_time->format('Y-m-d\TH:i') }}" required class="form-input">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal & Jam Selesai</label>
                    <input type="datetime-local" name="end_time" value="{{ $booking->end_time->format('Y-m-d\TH:i') }}" required class="form-input">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Pilih Bidang</label>
                <div class="relative">
                    <select id="department-select" class="w-full appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 font-semibold text-slate-700 outline-none transition hover:border-sky-400 focus:border-sky-600 focus:ring-2 focus:ring-sky-100">
                        <option value="">Pilih bidang</option>
                        @foreach($departmentOptions as $departmentOption)
                            <option value="{{ $departmentOption }}" @selected($booking->title === $departmentOption)>{{ $departmentOption }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <span class="text-sm">▼</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Dibooking Oleh</label>
                <input id="title" type="text" name="title" value="{{ $booking->title }}" class="form-input">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Perihal</label>
                <textarea name="description" rows="4" class="form-input">{{ $booking->description }}</textarea>
            </div>

            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-4">
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Catatan Penting</p>
                <ul class="mt-2 text-sm text-blue-700 space-y-1">
                    <li>• Ubah ke waktu yang kosong saja</li>
                    <li>• Durasi maksimal booking adalah 24 jam</li>
                    <li>• Tidak bisa mengubah jika waktu baru sudah terpakai</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1 justify-center rounded-2xl px-5 py-3">
                    Simpan Perubahan
                </button>
                <a href="/bookings" class="flex-1 rounded-2xl border border-slate-300 text-slate-700 font-semibold px-5 py-3 text-center hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </main>

    <script>
        const editDepartmentSelect = document.getElementById('department-select');
        const editTitleInput = document.getElementById('title');

        editDepartmentSelect?.addEventListener('change', () => {
            if (!editTitleInput) return;

            editTitleInput.value = editDepartmentSelect.value;
            editTitleInput.focus();
        });
    </script>
</body>
</html>
