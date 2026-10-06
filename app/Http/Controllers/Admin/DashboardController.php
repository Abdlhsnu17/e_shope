<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'statistik' => [
                'produk' => Product::count(),
                'stok' => Product::sum('stock'),
                'pelanggan' => User::where('role', 'customer')->count(),
                'pesanan' => Order::count(),
                'pendapatan' => Order::where('payment_status', 'paid')->sum('total'),
            ],
            'pesananTerbaru' => Order::latest()->take(7)->get(),
        ]);
    }
}
