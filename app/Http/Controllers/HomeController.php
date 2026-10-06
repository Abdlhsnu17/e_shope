<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home', [
            'products' => Product::query()->where('is_active', true)->latest()->take(4)->get(),
        ]);
    }

    public function shop()
    {
        $category = request('category');
        $products = Product::query()->where('is_active', true)
            ->when($category && $category !== 'new', fn ($query) => $query->where('category', $category))
            ->latest()->get();

        return view('public.shop', compact('products', 'category'));
    }

    public function product(string $slug)
    {
        return view('public.product', [
            'product' => Product::query()->where('is_active', true)->where('slug', $slug)->firstOrFail(),
        ]);
    }
}
