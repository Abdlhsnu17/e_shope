<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Announcement extends Model
{
    public const KATEGORI = ['pengumuman', 'berita', 'agenda'];

    protected $fillable = [
        'judul', 'slug', 'kategori', 'ringkasan', 'konten',
        'gambar', 'is_published', 'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            if (blank($item->slug)) {
                $item->slug = static::uniqueSlug($item->judul, $item->id);
            }
            if ($item->is_published && blank($item->published_at)) {
                $item->published_at = now();
            }
        });
    }

    public static function uniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul) ?: 'artikel';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderByDesc('published_at');
    }

    public function getRingkasanTeksAttribute(): string
    {
        return $this->ringkasan ?: Str::limit(strip_tags($this->konten), 140);
    }
}
