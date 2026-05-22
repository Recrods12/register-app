<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="page-wrap">
    <header class="px-4 pt-4">
        <div class="shell glass-nav radius-28 flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-black">M</div>
                <div>
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-500">Secure Access</p>
                    <h1 class="text-xl font-black text-slate-900">MyApp Sign In</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                <a href="/login" class="pill rounded-full px-4 py-2 text-slate-900">Login</a>
                <a href="/register" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Register</a>
            </nav>
        </div>
    </header>

    <main class="shell auth-layout grid items-center gap-8 px-4 py-10">
        <section class="float-in">
            <span class="eyebrow">Professional Workspace</span>
            <h2 class="mt-5 max-w-2xl text-4xl font-extrabold leading-tight text-slate-950 md:text-5xl">
                Masuk ke workspace yang lebih rapi, lebih fokus, dan siap untuk digunakan setiap hari.
            </h2>
            <p class="section-copy mt-5 max-w-xl text-base md:text-lg">
                Gunakan akun Anda untuk mengakses dashboard, meninjau katalog produk, dan bekerja dengan tampilan yang terasa lebih matang serta lebih terpercaya.
            </p>

            <div class="stats-grid mt-8 grid gap-4">
                <div class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Fast Access</p>
                    <p class="mt-3 text-lg font-bold text-slate-950">Masuk lebih cepat</p>
                </div>
                <div class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Clear Layout</p>
                    <p class="mt-3 text-lg font-bold text-slate-950">Tampilan lebih profesional</p>
                </div>
                <div class="metric-card">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Trusted Flow</p>
                    <p class="mt-3 text-lg font-bold text-slate-950">Alur kerja lebih nyaman</p>
                </div>
            </div>
        </section>

        <section class="auth-panel radius-28 p-6 md:p-8">
            <span class="eyebrow eyebrow-warm">Sign In</span>
            <h3 class="mt-5 text-3xl font-extrabold text-slate-950">Masuk ke akun Anda</h3>
            <p class="section-copy mt-3">Gunakan email dan password yang sudah terdaftar untuk melanjutkan.</p>

            @if(session('error'))
                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/login" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="form-input">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" class="form-input">
                </div>

                <button class="btn-primary mt-2 w-full justify-center rounded-2xl px-5 py-3">
                    Login Sekarang
                </button>
            </form>

            <p class="mt-6 text-sm text-slate-500">
                Belum punya akun?
                <a href="/register" class="font-semibold text-sky-700 hover:underline">Daftar di sini</a>
            </p>
        </section>
    </main>
</body>
</html>
