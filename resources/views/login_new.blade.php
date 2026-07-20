<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Booking Ruang Rapat</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="page-wrap">
    <div class="shell py-6">
        <header class="glass-nav mx-auto flex max-w-6xl items-center justify-between rounded-[30px] px-5 py-4 sm:px-7">
            <a href="/" class="flex items-center gap-4">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-xl font-black">
                    🏢
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-sky-700">Workspace Booking</p>
                    <h1 class="text-xl font-extrabold text-slate-950">Booking Ruang Rapat</h1>
                </div>
            </a>

            <nav class="flex items-center gap-3">
                <a href="/" class="btn-soft px-4 py-2 text-sm">
                    Beranda
                </a>
                <a href="/register" class="btn-primary px-4 py-2 text-sm">
                    Register
                </a>
            </nav>
        </header>
    </div>

    <main class="shell flex min-h-[calc(100vh-116px)] flex-col items-center justify-center gap-4 pb-10 pt-4">
        @if ($errors->any())
            <x-toast type="error" :message="implode(' ', $errors->all())" />
        @endif

        <section class="float-in-delay grid w-full max-w-6xl gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
            <div class="hero-card relative hidden overflow-hidden rounded-[34px] p-9 lg:flex lg:flex-col lg:justify-between">
                <div>
                    <div class="eyebrow">
                        Premium Workspace
                    </div>

                    <h2 class="mt-8 max-w-lg text-5xl font-black leading-[1.05] text-slate-950">
                        Masuk ke sistem booking yang terasa lebih rapi dan profesional.
                    </h2>
                    <p class="mt-6 max-w-xl text-base leading-8 text-slate-600">
                        Pantau ruang aktif, atur ulang jadwal meeting, dan kelola reservasi tim dengan antarmuka yang lebih tenang, jelas, dan elegan.
                    </p>

                    <div class="mt-10 grid gap-4">
                        <article class="feature-card rounded-[28px] p-5">
                            <p class="text-xs font-bold uppercase tracking-[0.28em] text-sky-700">Realtime Control</p>
                            <p class="mt-3 text-xl font-bold text-slate-950">Status ruang, log aktivitas, dan jadwal booking tampil dalam satu alur.</p>
                        </article>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <article class="feature-card rounded-[26px] p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-orange-600">Smart Rules</p>
                                <p class="mt-3 text-lg font-bold text-slate-900">Anti overlap</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600">Jadwal bentrok langsung tertolak agar reservasi tetap aman.</p>
                            </article>

                            <article class="feature-card rounded-[26px] p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Fast Workflow</p>
                                <p class="mt-3 text-lg font-bold text-slate-900">Kelola cepat</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600">Masuk sekali, lalu semua booking tim bisa dipantau dengan cepat.</p>
                            </article>
                        </div>
                    </div>
                </div>

                <div class="mt-10 rounded-[28px] border border-white/70 bg-white/70 p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-500">Sistem Internal</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">Dirancang untuk workflow kantor yang lebih tertib.</p>
                        </div>
                        <div class="rounded-full bg-sky-100 px-4 py-2 text-sm font-bold text-sky-700">
                            v1.0
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-panel overflow-hidden rounded-[34px]">
                <div class="relative px-6 py-7 sm:px-8 md:px-10">
                    <div class="absolute inset-x-0 top-0 h-36 bg-[radial-gradient(circle_at_top,rgba(14,165,233,0.20),transparent_72%)]"></div>

                    <div class="relative">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <a href="/" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/85 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-sky-200 hover:text-sky-700">
                                <span>←</span>
                                <span>Kembali</span>
                            </a>

                            <a href="/register" class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-4 py-2 text-sm font-bold text-sky-700 transition hover:bg-sky-100">
                                <span>Belum punya akun?</span>
                                <span>Daftar</span>
                            </a>
                        </div>

                        <div class="mt-8 flex items-center gap-4">
                            <div class="brand-mark flex h-15 w-15 items-center justify-center rounded-[22px] text-2xl font-black">
                                🏢
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.3em] text-sky-700">Secure Login</p>
                                <h2 class="mt-2 text-4xl font-black leading-tight text-slate-950">Masuk ke akun Anda</h2>
                            </div>
                        </div>

                        <p class="mt-5 max-w-xl text-base leading-8 text-slate-600">
                            Akses dashboard booking untuk memantau ruang aktif, melihat jadwal tim, dan mengelola perubahan reservasi dengan lebih mudah.
                        </p>

                        <div class="mt-8 rounded-[30px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_28px_60px_rgba(15,23,42,0.08)] sm:p-6">
                            <form method="POST" action="/login" data-loading-on-submit class="space-y-5">
                                @csrf

                                <div>
                                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-900">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                        placeholder="nama@perusahaan.com"
                                        class="form-input"
                                    />
                                </div>

                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-3">
                                        <label for="password" class="block text-sm font-semibold text-slate-900">
                                            Password
                                        </label>
                                        <span class="text-xs font-medium text-slate-400">Akses aman</span>
                                    </div>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        autocomplete="current-password"
                                        required
                                        placeholder="Masukkan password"
                                        class="form-input"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    class="btn-primary w-full py-4 text-base"
                                >
                                    Masuk ke Dashboard
                                </button>
                            </form>
                        </div>

                        <div class="mt-7 text-center text-sm text-slate-600">
                            Belum punya akun?
                            <a href="/register" class="font-bold text-sky-700 transition hover:text-sky-800">
                                Buat akun baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
