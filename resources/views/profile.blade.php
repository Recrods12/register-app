<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.12),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(14,165,233,0.08),_transparent_24%),linear-gradient(180deg,_#f8fbff_0%,_#eef4fb_100%)] text-slate-900">
    <header class="sticky top-0 z-40 border-b border-white/60 bg-white/85 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-sky-700 text-lg font-black text-white shadow-[0_16px_28px_rgba(2,132,199,0.28)]">
                    B
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600">Account Center</p>
                    <h1 class="text-3xl font-black leading-none text-slate-950">Edit Profil</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3">
                <a href="/" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-2.5 font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50">
                    Dashboard
                </a>
                <a href="/bookings" class="inline-flex min-h-[46px] items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-2.5 font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50">
                    Booking Saya
                </a>
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button class="inline-flex min-h-[46px] items-center justify-center rounded-2xl bg-slate-900 px-5 py-2.5 font-bold text-white shadow-[0_14px_28px_rgba(15,23,42,0.18)] transition hover:-translate-y-0.5 hover:bg-slate-800">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 lg:py-10">
        <section class="overflow-hidden rounded-[32px] border border-sky-500/10 bg-gradient-to-br from-sky-600 via-sky-700 to-cyan-500 px-6 py-7 text-white shadow-[0_30px_80px_rgba(2,132,199,0.24)] lg:px-8 lg:py-8">
            <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-end">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-sky-100/90">Profil Pengguna</p>
                    <h2 class="mt-3 max-w-2xl text-3xl font-black leading-tight lg:text-4xl">Kelola data akun Anda dengan tampilan yang lebih rapi dan aman</h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-sky-100/95 lg:text-base">
                        Perbarui nama, email, dan password dari satu halaman yang lebih nyaman dibaca agar pengelolaan akun terasa profesional dan tidak membingungkan.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/20 bg-white/12 px-5 py-4 backdrop-blur-md">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-sky-100/85">Total Booking</p>
                        <p class="mt-3 text-4xl font-black">{{ $totalBookings }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/20 bg-white/12 px-5 py-4 backdrop-blur-md">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-sky-100/85">Sedang Berlangsung</p>
                        <p class="mt-3 text-4xl font-black">{{ $activeBookings }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-8 grid gap-8 xl:grid-cols-[0.82fr_1.18fr]">
            <aside class="rounded-[30px] border border-white/60 bg-white/90 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.08)] backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600">Informasi Akun</p>
                <h3 class="mt-3 text-2xl font-black text-slate-950">Ringkasan profil</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Identitas ini digunakan untuk login dan pengelolaan booking ruang rapat di dashboard Anda.
                </p>

                <div class="mt-7 space-y-4">
                    <div class="rounded-3xl border border-slate-200/80 bg-slate-50 px-5 py-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">Nama Lengkap</p>
                        <p class="mt-2 text-xl font-black text-slate-900">{{ $user->name }}</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200/80 bg-slate-50 px-5 py-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">Email</p>
                        <p class="mt-2 break-all text-xl font-black text-slate-900">{{ $user->email }}</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200/80 bg-slate-50 px-5 py-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">Role</p>
                        <div class="mt-3 inline-flex rounded-full bg-sky-100 px-4 py-2 text-sm font-extrabold text-sky-700">
                            {{ strtoupper($user->role) }}
                        </div>
                    </div>
                </div>
            </aside>

            <section class="rounded-[30px] border border-white/60 bg-white/92 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.08)] backdrop-blur lg:p-7">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600">Form Profil</p>
                        <h3 class="mt-3 text-2xl font-black text-slate-950">Perbarui data dan password</h3>
                    </div>
                    <p class="max-w-xs text-sm leading-6 text-slate-500">Kosongkan password baru jika Anda tidak ingin menggantinya saat ini.</p>
                </div>

                @if(session('success'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/profile" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" placeholder="nama@perusahaan.com">
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-slate-50/90 p-5 lg:p-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Security</p>
                            <h4 class="mt-2 text-xl font-black text-slate-950">Ubah Password</h4>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Untuk keamanan akun, isi password saat ini sebelum mengganti password baru.</p>
                        </div>

                        <div class="mt-5 grid gap-5">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-input" placeholder="Masukkan password saat ini">
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-1">
                        <button class="inline-flex min-h-[52px] items-center justify-center rounded-2xl bg-sky-600 px-6 py-3 font-extrabold text-white shadow-[0_16px_28px_rgba(2,132,199,0.22)] transition hover:-translate-y-0.5 hover:bg-sky-700">
                            Simpan Perubahan
                        </button>
                        <a href="/" class="inline-flex min-h-[52px] items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 font-bold text-slate-700 transition hover:-translate-y-0.5 hover:bg-slate-50">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </section>
        </section>
    </main>
</body>
</html>
