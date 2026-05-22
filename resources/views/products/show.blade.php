<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    @vite('resources/css/app.css')
</head>
<body class="page-wrap">
    <header class="sticky top-0 z-20 px-4 pt-4">
        <div class="shell glass-nav radius-28 flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-black">M</div>
                <div>
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-500">Product Detail</p>
                    <h1 class="text-xl font-black text-slate-900">MyApp Catalog</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                <a href="/dashboard" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Dashboard</a>
                <a href="/products" class="pill rounded-full px-4 py-2 text-slate-900">Produk</a>
                <a href="/profile" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Profil</a>
            </nav>
        </div>
    </header>

    <main class="shell px-4 pb-16 pt-8">
        <section class="hero-card rounded-4xl p-6 md:p-10">
            <div class="grid gap-8 lg:grid-cols-[1fr_1fr] lg:items-start">
                <div>
                    @if($product->image)
                        <img src="/images/{{ $product->image }}" alt="{{ $product->name }}" class="h-full max-h-[520px] w-full rounded-4xl object-cover shadow-lg">
                    @else
                        <div class="bg-linear-to-br flex min-h-96 items-center justify-center rounded-4xl from-slate-100 via-blue-50 to-orange-50">
                            <span class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-500 shadow-sm">No Image</span>
                        </div>
                    @endif
                </div>

                <div>
                    <span class="eyebrow">{{ $product->category }}</span>
                    <h2 class="mt-5 text-4xl font-extrabold text-slate-950">{{ $product->name }}</h2>
                    <p class="mt-4 text-3xl font-extrabold text-slate-900">Rp {{ number_format($product->price) }}</p>
                    <p class="section-copy mt-5 text-base md:text-lg">
                        {{ $product->description ?: 'Produk ini belum memiliki deskripsi lengkap.' }}
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        <div class="metric-card">
                            <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Status</p>
                            <p class="mt-3 text-xl font-bold text-slate-950">{{ $statusLabel }}</p>
                        </div>
                        <div class="metric-card">
                            <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Stok</p>
                            <p class="mt-3 text-xl font-bold text-slate-950">{{ $product->stock }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="/products" class="btn-soft">Kembali ke Produk</a>
                        @if(auth()->user()?->role === 'admin')
                            <a href="/products/{{ $product->id }}/edit" class="btn-primary">Edit Produk</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
