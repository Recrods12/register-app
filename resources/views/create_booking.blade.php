<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Booking</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-600 text-white font-bold">🏢</a>
                <h1 class="text-2xl font-black text-slate-900">Buat Booking</h1>
            </div>

            <a href="/" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-semibold">
                ← Batal
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-10">
        <div class="mb-10">
            <h2 class="text-3xl font-black text-slate-900">Pesan Ruang Rapat Sekarang</h2>
            <p class="text-slate-600 mt-2">Lengkapi form di bawah untuk booking ruang sesuai tanggal dan jam yang Anda butuhkan</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3">
                <p class="font-bold text-red-900 mb-2">Terjadi Kesalahan:</p>
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/bookings" class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
            @csrf

            @if($isAdmin)
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-700 font-black">A</div>
                        <h3 class="text-xl font-bold text-slate-900">Pilih Pemesan</h3>
                    </div>

                    <label class="block text-sm font-bold text-slate-700 mb-2">Akun yang Membooking</label>
                    <select name="user_id" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-sky-600 focus:ring-2 focus:ring-sky-100 outline-none transition font-semibold">
                        <option value="">Pilih user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="border-t border-slate-200 my-10"></div>
            @endif

            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-600 text-white font-black">1</div>
                    <h3 class="text-xl font-bold text-slate-900">Pilih Ruang Rapat</h3>
                </div>

                <div class="grid gap-3">
                    @foreach($rooms as $room)
                        <label class="relative flex items-center p-4 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-sky-400 transition">
                            <input type="radio" name="room_id" value="{{ $room->id }}" required @checked(old('room_id', request('room_id')) == $room->id) class="w-4 h-4 text-sky-600">
                            <div class="ml-4">
                                <p class="font-bold text-slate-900">{{ $room->name }}</p>
                                <p class="text-sm text-slate-600">Lantai {{ $room->floor }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-slate-200 my-10"></div>

            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-600 text-white font-black">2</div>
                    <h3 class="text-xl font-bold text-slate-900">Tentukan Waktu Booking</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal & Jam Mulai</label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-sky-600 focus:ring-2 focus:ring-sky-100 outline-none transition font-semibold">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal & Jam Selesai</label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-sky-600 focus:ring-2 focus:ring-sky-100 outline-none transition font-semibold">
                    </div>
                </div>

                <div class="mt-4 rounded-lg bg-sky-50 border border-sky-200 p-4">
                    <p class="text-sm text-sky-700">
                        <span class="font-bold">Info Durasi:</span> Maksimal 24 jam per booking. Jika ruang terpakai, booking akan ditolak.
                    </p>
                </div>
            </div>

            <div class="border-t border-slate-200 my-10"></div>

            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-300 text-slate-700 font-black">3</div>
                    <h3 class="text-xl font-bold text-slate-900">Detail Meeting (Opsional)</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Bidang</label>
                        <div class="relative">
                            <select id="department-select" class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-3 pr-12 font-semibold text-slate-700 outline-none transition hover:border-sky-400 focus:border-sky-600 focus:ring-2 focus:ring-sky-100">
                                <option value="">Pilih bidang</option>
                                @foreach($departmentOptions as $departmentOption)
                                    <option value="{{ $departmentOption }}" @selected(old('title') === $departmentOption)>{{ $departmentOption }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                                <span class="text-sm">▼</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Dibooking Oleh</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Bidang Sekretariat" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-sky-600 focus:ring-2 focus:ring-sky-100 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Meeting</label>
                        <textarea name="description" rows="3" placeholder="Contoh: Rapat koordinasi mingguan" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-sky-600 focus:ring-2 focus:ring-sky-100 outline-none transition resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-amber-50 border border-amber-200 p-4 mb-10">
                <p class="text-sm font-bold text-amber-900 mb-3">Peraturan Booking:</p>
                <ul class="space-y-2 text-sm text-amber-800">
                    <li>✓ Durasi maksimal 24 jam per booking</li>
                    <li>✓ Tidak boleh overlap dengan booking lain</li>
                    <li>✓ Booking dapat diubah jika ada waktu kosong</li>
                    <li>✓ Booking dapat dibatalkan kapan saja</li>
                </ul>
            </div>

            <button type="submit" class="w-full px-6 py-4 rounded-lg bg-sky-600 text-white font-bold text-lg hover:bg-sky-700 transition shadow-lg">
                ✓ Konfirmasi Booking
            </button>

            <a href="/" class="block mt-4 text-center text-slate-600 hover:text-slate-900 font-semibold">
                ← Kembali ke Halaman Utama
            </a>
        </form>
    </main>

    <script>
        const createDepartmentSelect = document.getElementById('department-select');
        const createTitleInput = document.getElementById('title');

        createDepartmentSelect?.addEventListener('change', () => {
            if (!createTitleInput) return;

            createTitleInput.value = createDepartmentSelect.value;
            createTitleInput.focus();
        });
    </script>
</body>
</html>
