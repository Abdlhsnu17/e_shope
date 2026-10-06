<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category', 'type', 'description', 'price', 'old_price',
        'image', 'tag', 'stock', 'is_active',
    ];

    protected function casts(): array
    {
        return ['price' => 'integer', 'old_price' => 'integer', 'stock' => 'integer', 'is_active' => 'boolean'];
    }
}
