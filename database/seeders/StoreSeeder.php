<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProductSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@abdishope.test'],
            [
                'name' => 'Admin Abdishopee 17',
                'password' => 'password',
                'role' => 'admin',
                'telepon' => '081234567890',
                'is_active' => true,
            ]
        );
    }
}
