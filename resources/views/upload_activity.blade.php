<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Kegiatan - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-slate-50">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Upload Kegiatan</p>
                <h1 class="text-2xl font-black text-slate-900">{{ $booking->room->name }} - Lantai {{ $booking->room->floor }}</h1>
            </div>
            <a href="{{ route('bookings.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-bold text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)]">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Detail Booking</p>
                <h2 class="mt-3 text-2xl font-black text-slate-900">{{ $booking->title }}</h2>
                <p class="mt-2 text-slate-600">Perihal: {{ $booking->description ?: '-' }}</p>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Waktu</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $booking->start_time->format('d M Y H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Pemesan</p>
                        <p class="mt-2 font-bold text-slate-900">{{ $booking->user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $booking->user->email }}</p>
                    </div>
                    @if($booking->activity_photo_path)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Foto Saat Ini</p>
                                <form method="POST" action="{{ route('bookings.activity.delete', $booking) }}" onsubmit="return confirm('Yakin ingin menghapus foto kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700">
                                        Hapus Foto
                                    </button>
                                </form>
                            </div>
                            <img src="{{ asset('storage/' . $booking->activity_photo_path) }}" alt="Foto kegiatan" class="mt-3 h-44 w-full max-w-md rounded-2xl object-contain bg-white p-2">
                            @if($booking->activity_note)
                                <p class="mt-3 text-sm text-slate-600">{{ $booking->activity_note }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)]">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Form Upload</p>
                <h2 class="mt-3 text-2xl font-black text-slate-900">Upload Foto Kegiatan</h2>
                <p class="mt-2 text-sm text-slate-600">Foto ini akan tersimpan di database backend dan ikut masuk ke export admin sebagai link file. Catatan kegiatan otomatis mengambil isi dari perihal booking.</p>
                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    Ukuran upload maksimum saat ini: <span class="font-bold">{{ $maxUploadMb }} MB</span>. Jika file lebih besar, kompres dulu atau naikkan limit PHP lokal.
                </div>

                <form method="POST" action="{{ route('bookings.activity.store', $booking) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Foto Kegiatan</label>
                        <input type="file" id="activity_photo" name="activity_photo" accept="image/*" class="form-input">
                        <p id="activity-photo-help" class="mt-2 text-xs text-slate-500">Pilih gambar dengan ukuran maksimal {{ $maxUploadMb }} MB.</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan Kegiatan</label>
                        <textarea name="activity_note" rows="6" class="form-input bg-slate-50 text-slate-500" readonly>{{ $booking->description ?: 'Belum ada perihal yang diisi pada booking ini.' }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button class="inline-flex min-h-[52px] items-center justify-center rounded-2xl bg-sky-600 px-6 py-3 font-extrabold text-white shadow-[0_14px_28px_rgba(2,132,199,0.22)] transition hover:-translate-y-0.5 hover:bg-sky-700">
                            {{ $booking->activity_photo_path ? 'Ganti Foto Kegiatan' : 'Simpan Foto Kegiatan' }}
                        </button>
                        <a href="{{ route('bookings.index') }}" class="inline-flex min-h-[52px] items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 font-bold text-slate-700 transition hover:bg-slate-50">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const input = document.getElementById('activity_photo');
            const help = document.getElementById('activity-photo-help');
            const maxBytes = {{ $maxUploadMb }} * 1024 * 1024;

            if (!input || !help) {
                return;
            }

            input.addEventListener('change', () => {
                const file = input.files?.[0];

                if (!file) {
                    help.textContent = 'Pilih gambar dengan ukuran maksimal {{ $maxUploadMb }} MB.';
                    help.className = 'mt-2 text-xs text-slate-500';
                    return;
                }

                if (file.size > maxBytes) {
                    input.value = '';
                    help.textContent = 'Ukuran file terlalu besar. Maksimal {{ $maxUploadMb }} MB.';
                    help.className = 'mt-2 text-xs font-semibold text-red-600';
                    return;
                }

                help.textContent = 'File siap diupload: ' + file.name;
                help.className = 'mt-2 text-xs font-semibold text-emerald-600';
            });
        })();
    </script>
</body>
</html>
