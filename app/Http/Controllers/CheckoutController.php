<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function cart() { return view('public.cart', ['items' => $this->items(), 'subtotal' => collect($this->items())->sum('subtotal')]); }
    public function add(Request $request, Product $product) { $quantity = $request->integer('quantity', 1); abort_if($quantity < 1 || $quantity > $product->stock, 422); $cart = session('cart', []); $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity; session(['cart' => $cart]); return back()->with('success', 'Produk ditambahkan ke keranjang.'); }
    public function checkout() { $items = $this->items(); abort_if(empty($items), 422, 'Keranjang Anda masih kosong.'); return view('public.checkout', ['items' => $items, 'subtotal' => collect($items)->sum('subtotal')]); }
    public function place(Request $request) { $data = $request->validate(['customer_name' => ['required', 'string', 'max:255'], 'phone' => ['required', 'string', 'max:30'], 'address' => ['required', 'string', 'max:1000'], 'payment_method' => ['required', 'in:bank_transfer,ewallet,cod']]); $items = $this->items(); abort_if(empty($items), 422, 'Keranjang kosong.'); $order = DB::transaction(function () use ($data, $items, $request) { $subtotal = collect($items)->sum('subtotal'); $order = Order::create($data + ['user_id' => $request->user()->id, 'email' => $request->user()->email, 'order_number' => 'NVA-'.now()->format('ymd').'-'.Str::upper(Str::random(5)), 'subtotal' => $subtotal, 'shipping_cost' => 20000, 'total' => $subtotal + 20000]); foreach ($items as $item) { $order->items()->create(['product_id' => $item['product']->id, 'name' => $item['product']->name, 'price' => $item['product']->price, 'quantity' => $item['quantity'], 'subtotal' => $item['subtotal']]); $item['product']->decrement('stock', $item['quantity']); } return $order; }); session()->forget('cart'); return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat.'); }
    public function order(Order $order) { abort_unless($order->user_id === auth()->id() || auth()->user()->isAdmin(), 403); return view('public.order', compact('order')); }
    private function items(): array { $cart = session('cart', []); return Product::whereIn('id', array_keys($cart))->where('is_active', true)->get()->map(fn ($product) => ['product' => $product, 'quantity' => $cart[$product->id], 'subtotal' => $product->price * $cart[$product->id]])->all(); }
}
