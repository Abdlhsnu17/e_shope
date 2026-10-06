<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        'nama', 'npsn', 'jenjang', 'akreditasi', 'kepala_sekolah',
        'email', 'telepon', 'alamat', 'visi', 'misi', 'sejarah',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], ['nama' => 'Portal Sekolah']);
    }

    public function misiList(): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $this->misi))));
    }
}
