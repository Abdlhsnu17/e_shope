<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * NIS berikutnya untuk tahun ajaran berjalan.
     *
     * Diturunkan dari NIS terakhir, bukan dari jumlah baris: menghitung baris
     * membuat nomor terpakai ulang setiap kali ada siswa yang dihapus. Baris
     * dikunci agar dua promosi pendaftar yang berbarengan tidak bertabrakan —
     * pemanggil wajib menjalankannya di dalam transaksi.
     */
    public static function generateNis(): string
    {
        $prefix = 'S'.date('y');

        $terakhir = static::where('nis', 'like', $prefix.'%')
            ->orderByDesc('nis')
            ->lockForUpdate()
            ->value('nis');

        $urut = $terakhir ? ((int) substr($terakhir, -4)) + 1 : 1;

        return $prefix.str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
    }

    protected $fillable = [
        'nis', 'nisn', 'nama', 'email', 'jenis_kelamin', 'kelas', 'jurusan',
        'tanggal_lahir', 'alamat', 'nama_wali', 'telepon_wali', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($q) use ($term) {
            $q->where('nama', 'like', "%{$term}%")
                ->orWhere('nis', 'like', "%{$term}%")
                ->orWhere('nisn', 'like', "%{$term}%");
        }));
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
