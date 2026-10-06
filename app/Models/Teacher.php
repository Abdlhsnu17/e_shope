<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'nip', 'nama', 'email', 'telepon', 'jenis_kelamin',
        'mata_pelajaran', 'jabatan', 'tanggal_bergabung', 'alamat', 'status',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
    ];

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($q) use ($term) {
            $q->where('nama', 'like', "%{$term}%")
                ->orWhere('nip', 'like', "%{$term}%")
                ->orWhere('mata_pelajaran', 'like', "%{$term}%");
        }));
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
