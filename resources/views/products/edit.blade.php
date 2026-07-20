<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="page-wrap">
    <main class="shell px-4 py-8">
        <section class="form-panel mx-auto max-w-4xl rounded-4xl p-6 md:p-8">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="eyebrow">Admin Form</span>
                    <h1 class="mt-5 text-3xl font-extrabold text-slate-950">Edit Produk</h1>
                    <p class="section-copy mt-3">Perbarui detail produk, kategori, stok, status, dan gambar dengan layout yang lebih rapi.</p>
                </div>

                <a href="/products" class="btn-soft">Kembali ke Produk</a>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/products/{{ $product->id }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
                        <select name="category" class="form-input">
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Harga</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-input">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-input" min="0">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="form-input">
                            @foreach($statuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" @selected(old('status', $product->status) === $statusKey)>{{ $statusLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-textarea">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Gambar Saat Ini</label>
                        @if($product->image)
                            <img src="/images/{{ $product->image }}" alt="{{ $product->name }}" class="h-56 w-full rounded-3xl object-cover shadow-md">
                        @else
                            <div class="flex h-56 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Ganti Gambar</label>
                        <div class="flex h-56 flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50/80 p-6 text-center transition hover:border-sky-400 hover:bg-white">
                            <input type="file" name="image" id="imageInput" class="hidden" onchange="previewImage(event)">
                            <label for="imageInput" class="cursor-pointer text-sm font-semibold text-slate-600">
                                Klik untuk upload gambar baru
                            </label>
                            <p class="mt-2 text-xs text-slate-400">Format JPG, JPEG, atau PNG dengan ukuran maksimal 10 MB.</p>
                            <img id="preview" class="mx-auto mt-4 hidden max-h-36 rounded-2xl shadow-md" alt="Preview gambar baru">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a href="/products" class="btn-soft rounded-2xl px-5 py-3 text-center">Batal</a>
                    <button class="btn-warm rounded-2xl px-6 py-3">Update Produk</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');

            if (!input.files || !input.files[0]) {
                preview.classList.add('hidden');
                preview.removeAttribute('src');
                return;
            }

            preview.src = URL.createObjectURL(input.files[0]);
            preview.classList.remove('hidden');
        }
    </script>
</body>
</html>
