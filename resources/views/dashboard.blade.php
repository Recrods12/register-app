<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="page-wrap">
    <header class="sticky top-0 z-20 px-4 pt-4">
        <div class="shell glass-nav radius-28 flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-black">M</div>
                <div>
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-500">Control Center</p>
                    <h1 class="text-xl font-black text-slate-900">MyApp Workspace</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                <a href="/dashboard" class="pill rounded-full px-4 py-2 text-slate-900">Dashboard</a>
                <a href="/products" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Produk</a>
                <a href="/profile" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Profil</a>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="btn-soft px-5 py-2.5">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="shell px-4 pb-16 pt-8">
        <section class="hero-card float-in rounded-4xl p-6 md:p-10">
            <div class="dashboard-hero-grid grid gap-8 lg:items-center">
                <div>
                    <span class="eyebrow">Dashboard Overview</span>
                    <h2 class="mt-5 max-w-3xl text-4xl font-extrabold leading-tight text-slate-950 md:text-5xl">
                        Selamat datang, {{ $userName }}. Area kerja Anda sekarang lebih lengkap dan lebih siap dipakai.
                    </h2>
                    <p class="section-copy mt-5 max-w-2xl text-base md:text-lg">
                        Dashboard ini sekarang menampilkan ringkasan katalog, status akun, dan produk terbaru agar informasi penting langsung terlihat saat Anda masuk.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="/products" class="btn-primary">Buka Produk</a>
                        <a href="/profile" class="btn-soft">Kelola Profil</a>
                        @if($isAdmin)
                            <a href="/products/create" class="btn-warm">Tambah Produk</a>
                        @endif
                    </div>
                </div>

                <div class="feature-card radius-28 p-5">
                    <div class="rounded-3xl bg-slate-950 p-6 text-white shadow-xl">
                        <p class="tracking-20 text-xs font-semibold uppercase text-slate-300">Akun aktif</p>
                        <p class="mt-4 text-2xl font-extrabold">{{ $userEmail }}</p>
                        <div class="mt-6 flex flex-wrap gap-3 text-sm">
                            <span class="rounded-full bg-white/10 px-3 py-2 text-slate-200">{{ strtoupper($userRole) }}</span>
                            <span class="rounded-full bg-emerald-400/15 px-3 py-2 text-emerald-200">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats-grid mt-8 grid gap-4">
            <article class="metric-card">
                <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Catalog</p>
                <h3 class="mt-3 text-lg font-extrabold text-slate-950">Total Produk</h3>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalProducts }}</p>
            </article>

            <article class="metric-card">
                <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Media</p>
                <h3 class="mt-3 text-lg font-extrabold text-slate-950">Produk Bergambar</h3>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $productsWithImages }}</p>
            </article>

            <article class="metric-card">
                <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Status</p>
                <h3 class="mt-3 text-lg font-extrabold text-slate-950">Siap Dijual</h3>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $availableProducts }}</p>
            </article>
        </section>

        <section class="mt-8 grid gap-6 lg:grid-cols-3">
            <article class="feature-card radius-28 p-6">
                <span class="eyebrow">Profile</span>
                <h3 class="section-title mt-5">Ringkasan akun</h3>
                <p class="section-copy mt-3">
                    Kelola data akun dan pantau informasi penting tanpa perlu berpindah halaman terlalu jauh.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="pill rounded-3xl px-4 py-4">
                        <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Nama</p>
                        <p class="mt-2 font-semibold text-slate-950">{{ $userName }}</p>
                    </div>
                    <div class="pill rounded-3xl px-4 py-4">
                        <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Email</p>
                        <p class="mt-2 font-semibold text-slate-950">{{ $userEmail }}</p>
                    </div>
                </div>

                <a href="/profile" class="btn-soft mt-6">Edit Profil</a>
            </article>

            <article class="hero-card radius-28 p-6 lg:col-span-2">
                <span class="eyebrow eyebrow-warm">Latest Products</span>
                <h3 class="section-title mt-5">Produk terbaru</h3>
                <p class="section-copy mt-3 max-w-2xl">
                    Daftar singkat produk terakhir membantu Anda melihat pembaruan terbaru di katalog tanpa harus membuka seluruh halaman manajemen.
                </p>

                <div class="mt-6 space-y-4">
                    @forelse($latestProducts as $product)
                        <div class="pill flex flex-col gap-3 rounded-3xl px-4 py-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-lg font-bold text-slate-950">{{ $product->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $product->category }} • {{ \App\Models\Product::STATUSES[$product->status] ?? $product->status }} • Stok {{ $product->stock }}</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <span class="text-sm font-semibold text-slate-700">Rp {{ number_format($product->price) }}</span>
                                <a href="/products/{{ $product->id }}" class="text-sm font-semibold text-sky-700 hover:underline">Lihat detail</a>
                            </div>
                        </div>
                    @empty
                        <div class="pill rounded-3xl px-4 py-4 text-slate-500">
                            Belum ada produk terbaru untuk ditampilkan.
                        </div>
                    @endforelse
                </div>
            </article>
        </section>
    </main>
</body>
</html>
