<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index() { return view('admin.products.index', ['products' => Product::latest()->paginate(15)]); }
    public function create() { return view('admin.products.form', ['product' => new Product]); }
    public function store(Request $request) { Product::create($this->data($request)); return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.'); }
    public function edit(Product $product) { return view('admin.products.form', compact('product')); }
    public function update(Request $request, Product $product) { $product->update($this->data($request, $product)); return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.'); }
    public function destroy(Product $product) { $product->delete(); return back()->with('success', 'Produk dihapus.'); }
    private function data(Request $request, ?Product $product = null): array { $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'category' => ['required', 'in:home,wear,ritual'], 'type' => ['required', 'string', 'max:80'], 'description' => ['required', 'string'], 'price' => ['required', 'integer', 'min:0'], 'old_price' => ['nullable', 'integer', 'min:0'], 'image' => ['nullable', 'url'], 'tag' => ['nullable', 'string', 'max:40'], 'stock' => ['required', 'integer', 'min:0'], 'is_active' => ['nullable', 'boolean']]); $data['slug'] = Str::slug($data['name']).'-'.($product?->id ?? Str::lower(Str::random(5))); $data['is_active'] = $request->boolean('is_active'); return $data; }
}
