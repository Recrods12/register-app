<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="page-wrap">
    <header class="px-4 pt-4">
        <div class="shell glass-nav radius-28 flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-black">M</div>
                <div>
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-500">Create Account</p>
                    <h1 class="text-xl font-black text-slate-900">Join MyApp</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                <a href="/login" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Login</a>
                <a href="/register" class="pill rounded-full px-4 py-2 text-slate-900">Register</a>
            </nav>
        </div>
    </header>

    <main class="shell register-layout grid items-center gap-8 px-4 py-10">
        <section class="auth-panel radius-28 p-6 md:p-8">
            <span class="eyebrow eyebrow-warm">Registration</span>
            <h2 class="mt-5 text-3xl font-extrabold text-slate-950">Buat akun baru</h2>
            <p class="section-copy mt-3">Isi data di bawah untuk mulai menggunakan workspace secara penuh.</p>

            @if(session('success'))
                <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/register" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" class="form-input">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="form-input">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" class="form-input">
                </div>

                <button class="btn-warm mt-2 w-full justify-center rounded-2xl px-5 py-3">
                    Daftar Sekarang
                </button>
            </form>
        </section>

        <section class="float-in-delay">
            <span class="eyebrow">Modern Interface</span>
            <h3 class="mt-5 max-w-2xl text-4xl font-extrabold leading-tight text-slate-950 md:text-5xl">
                Mulai dengan pengalaman yang lebih konsisten dan lebih meyakinkan.
            </h3>
            <p class="section-copy mt-5 max-w-xl text-base md:text-lg">
                Registrasi sekarang terhubung dengan bahasa visual yang sama seperti dashboard dan katalog, sehingga aplikasi terasa lebih profesional dari awal.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="feature-card radius-28 p-5">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Focused Form</p>
                    <h4 class="mt-3 text-xl font-extrabold text-slate-950">Input lebih jelas</h4>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Spasi, hirarki, dan state input dibuat lebih nyaman untuk mengurangi rasa penuh di layar.</p>
                </div>
                <div class="feature-card radius-28 p-5">
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Unified Style</p>
                    <h4 class="mt-3 text-xl font-extrabold text-slate-950">Brand lebih konsisten</h4>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Aksen warna, kartu, tombol, dan tipografi kini lebih seragam di seluruh aplikasi.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
