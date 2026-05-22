<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function productCategories(): array
    {
        return Product::CATEGORIES;
    }

    private function productStatuses(): array
    {
        return Product::STATUSES;
    }

    private function productValidationRules(): array
    {
        return [
            'name' => 'required',
            'category' => 'required|string|in:' . implode(',', $this->productCategories()),
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'status' => 'required|string|in:' . implode(',', array_keys($this->productStatuses())),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ];
    }

    private function productValidationMessages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'category.in' => 'Kategori produk tidak valid.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa angka bulat.',
            'stock.min' => 'Stok produk tidak boleh kurang dari 0.',
            'status.required' => 'Status produk wajib dipilih.',
            'status.in' => 'Status produk tidak valid.',
            'description.string' => 'Deskripsi produk harus berupa teks.',
            'image.image' => 'File yang dipilih harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 10 MB.',
            'image.uploaded' => 'Upload gambar gagal. Biasanya file terlalu besar atau melebihi batas upload PHP.',
        ];
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->value();
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->value());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        $products = $query->latest()->paginate(6)->withQueryString();
        $isAdmin = $request->user()?->role === 'admin';

        return view('products.index', [
            'products' => $products,
            'isAdmin' => $isAdmin,
            'categories' => $this->productCategories(),
            'statuses' => $this->productStatuses(),
        ]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('products.show', [
            'product' => $product,
            'statusLabel' => $this->productStatuses()[$product->status] ?? $product->status,
        ]);
    }

    public function create()
    {
        return view('products.create', [
            'categories' => $this->productCategories(),
            'statuses' => $this->productStatuses(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->productValidationRules(), $this->productValidationMessages());

        $fileName = null;

        if ($request->hasFile('image')) {
            $fileName = Str::uuid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $fileName);
        }

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'description' => $request->description,
            'image' => $fileName
        ]);

        return redirect('/products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', [
            'product' => $product,
            'categories' => $this->productCategories(),
            'statuses' => $this->productStatuses(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate($this->productValidationRules(), $this->productValidationMessages());

        $fileName = $product->image;

        if ($request->hasFile('image')) {
            $oldImagePath = $product->image ? public_path('images/' . $product->image) : null;

            $fileName = Str::uuid() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $fileName);

            if ($oldImagePath && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
        }

        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'description' => $request->description,
            'image' => $fileName
        ]);

        return redirect('/products')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            $imagePath = public_path('images/' . $product->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect('/products')->with('success', 'Produk berhasil dihapus');
    }
}
