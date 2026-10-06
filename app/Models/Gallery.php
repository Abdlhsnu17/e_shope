<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public const KATEGORI = ['kegiatan', 'fasilitas', 'prestasi', 'ekstrakurikuler'];

    protected $fillable = ['judul', 'kategori', 'deskripsi', 'path'];
}
