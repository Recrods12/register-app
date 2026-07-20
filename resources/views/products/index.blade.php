<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="page-wrap">
    <header class="sticky top-0 z-20 px-4 pt-4">
        <div class="shell glass-nav radius-28 flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="brand-mark flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-black">M</div>
                <div>
                    <p class="tracking-20 text-xs font-semibold uppercase text-slate-500">Product Directory</p>
                    <h1 class="text-xl font-black text-slate-900">MyApp Catalog</h1>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-3 text-sm font-semibold">
                <a href="/dashboard" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-white/80 hover:text-slate-900">Dashboard</a>
                <a href="/products" class="pill rounded-full px-4 py-2 text-slate-900">Produk</a>
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
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <span class="eyebrow">Catalog Management</span>
                    <h2 class="mt-5 text-4xl font-extrabold leading-tight text-slate-950 md:text-5xl">
                        Katalog produk yang lebih terstruktur, lengkap, dan nyaman dipakai.
                    </h2>
                    <p class="section-copy mt-5 max-w-2xl text-base md:text-lg">
                        Sekarang Anda bisa melihat kategori, stok, status produk, masuk ke halaman detail, dan memfilter katalog dengan lebih akurat.
                    </p>
                </div>

                @if($isAdmin)
                    <a href="/products/create" class="btn-primary">Tambah Produk</a>
                @endif
            </div>
        </section>

        <section class="mt-8 grid gap-4 xl:grid-cols-[1.2fr_0.7fr_0.7fr_auto] xl:items-center">
            <form method="GET" action="/products" class="feature-card radius-28 p-3 xl:col-span-4">
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-[1.5fr_1fr_1fr_auto]">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, deskripsi, atau kategori..."
                        class="form-input border-none shadow-none focus:shadow-none"
                    >

                    <select name="category" class="form-input border-none shadow-none focus:shadow-none">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="form-input border-none shadow-none focus:shadow-none">
                        <option value="">Semua status</option>
                        @foreach($statuses as $statusKey => $statusLabel)
                            <option value="{{ $statusKey }}" @selected(request('status') === $statusKey)>{{ $statusLabel }}</option>
                        @endforeach
                    </select>

                    <button class="btn-primary justify-center">Cari</button>
                </div>
            </form>

            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <span class="pill rounded-full px-4 py-2">
                    Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                </span>
                @if(request('search') || request('category') || request('status'))
                    <a href="/products" class="btn-soft px-4 py-2">Reset Filter</a>
                @endif
            </div>
        </section>

        @if(session('success'))
            <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @php($productItems = $products->items())

        <section class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse($productItems as $product)
                <article class="product-card radius-28 overflow-hidden">
                    <div class="relative">
                        @if($product->image)
                            <img src="/images/{{ $product->image }}" alt="{{ $product->name }}" class="h-56 w-full object-cover">
                        @else
                            <div class="bg-linear-to-br flex h-56 items-center justify-center from-slate-100 via-blue-50 to-orange-50">
                                <span class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-500 shadow-sm">No Image</span>
                            </div>
                        @endif

                        <div class="surface-white-85 tracking-20 absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-bold uppercase text-slate-700 backdrop-blur">
                            {{ $product->category }}
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-xl font-extrabold text-slate-950">{{ $product->name }}</h3>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ $statuses[$product->status] ?? $product->status }}
                            </span>
                        </div>

                        <p class="mt-3 min-h-12 text-sm leading-7 text-slate-500">
                            {{ $product->description ?: 'Produk ini belum memiliki deskripsi, tetapi sudah siap ditampilkan di katalog.' }}
                        </p>

                        <div class="mt-5 flex items-end justify-between gap-3">
                            <div>
                                <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Harga</p>
                                <p class="mt-2 text-2xl font-extrabold text-slate-950">Rp {{ number_format($product->price) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="tracking-20 text-xs font-semibold uppercase text-slate-400">Stok</p>
                                <p class="mt-2 text-sm font-bold text-slate-700">{{ $product->stock }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 text-sm font-semibold">
                            <a href="/products/{{ $product->id }}" class="text-sky-700 transition hover:text-sky-900">
                                Detail
                            </a>

                            @if($isAdmin)
                                <div class="flex items-center gap-4">
                                    <a href="/products/{{ $product->id }}/edit" class="text-slate-700 transition hover:text-slate-950">
                                        Edit
                                    </a>
                                    <form action="/products/{{ $product->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 transition hover:text-red-800">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="feature-card radius-28 col-span-full p-10 text-center">
                    <span class="eyebrow">Empty Catalog</span>
                    <h3 class="mt-5 text-3xl font-extrabold text-slate-950">Belum ada produk</h3>
                    <p class="section-copy mt-3">Tambahkan item pertama agar katalog mulai terlihat aktif dan profesional.</p>
                </div>
            @endforelse
        </section>

        <div class="surface-white-70 mt-10 rounded-3xl p-4 shadow-sm">
            {{ $products->links() }}
        </div>
    </main>
</body>
</html>
