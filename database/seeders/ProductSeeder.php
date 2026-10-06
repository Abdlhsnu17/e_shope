<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Luna Table Lamp', 'slug' => 'luna-table-lamp', 'category' => 'home', 'type' => 'Pencahayaan', 'price' => 685000, 'old_price' => null, 'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=85', 'tag' => 'Best seller', 'stock' => 18, 'description' => 'Lampu meja berkarakter hangat dengan siluet sederhana untuk sudut yang terasa lebih tenang.'],
            ['name' => 'Solace Throw', 'slug' => 'solace-throw', 'category' => 'home', 'type' => 'Living', 'price' => 449000, 'old_price' => null, 'image' => 'https://images.unsplash.com/photo-1583845112203-454c8d4f8c72?auto=format&fit=crop&w=900&q=85', 'tag' => 'New', 'stock' => 25, 'description' => 'Selimut katun lembut yang menambah tekstur dan kenyamanan di rumah.'],
            ['name' => 'Daily Overshirt', 'slug' => 'daily-overshirt', 'category' => 'wear', 'type' => 'Pakaian', 'price' => 529000, 'old_price' => 649000, 'image' => 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85', 'tag' => 'Sale', 'stock' => 12, 'description' => 'Overshirt santai dengan potongan rileks, mudah dipadankan sepanjang hari.'],
            ['name' => 'Stillness Cup', 'slug' => 'stillness-cup', 'category' => 'home', 'type' => 'Tableware', 'price' => 189000, 'old_price' => null, 'image' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=900&q=85', 'tag' => null, 'stock' => 30, 'description' => 'Cangkir keramik berlapis matte untuk ritual minum yang lebih pelan.'],
            ['name' => 'Form Canvas Tote', 'slug' => 'form-canvas-tote', 'category' => 'wear', 'type' => 'Aksesori', 'price' => 269000, 'old_price' => null, 'image' => 'https://images.unsplash.com/photo-1594223274512-ad4803739b7c?auto=format&fit=crop&w=900&q=85', 'tag' => 'New', 'stock' => 22, 'description' => 'Tas kanvas kuat dengan ruang yang cukup untuk kebutuhan harian.'],
            ['name' => 'Amber Incense', 'slug' => 'amber-incense', 'category' => 'ritual', 'type' => 'Ritual', 'price' => 159000, 'old_price' => null, 'image' => 'https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&w=900&q=85', 'tag' => null, 'stock' => 40, 'description' => 'Aroma amber dan cedar untuk membangun suasana rumah yang intim.'],
        ] as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
