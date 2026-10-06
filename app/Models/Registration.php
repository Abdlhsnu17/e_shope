<?php

namespace App\Models;

use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Registration extends Model
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    public const STATUSES = ['menunggu', 'diverifikasi', 'diterima', 'ditolak'];

    /** Batas berkas per pendaftar — menahan unggahan yang membanjiri penyimpanan. */
    public const MAKS_DOKUMEN = 12;

    /** Kunci sesi berisi nomor pendaftaran yang pemiliknya sudah terbukti. */
    public const KUNCI_SESI = 'pendaftaran_terverifikasi';

    protected $fillable = [
        'nomor_pendaftaran', 'nama_lengkap', 'nisn', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'asal_sekolah', 'jurusan_pilihan',
        'email', 'telepon', 'alamat', 'nama_wali', 'telepon_wali',
        'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q) => $q->where(function ($q) use ($term) {
            $q->where('nama_lengkap', 'like', "%{$term}%")
                ->orWhere('nomor_pendaftaran', 'like', "%{$term}%")
                ->orWhere('asal_sekolah', 'like', "%{$term}%");
        }));
    }

    /**
     * Simpan pendaftar baru beserta nomor urutnya.
     *
     * Penomoran dan penyimpanan harus berada dalam satu transaksi dengan baris
     * terkunci: tanpa itu, dua pendaftar yang menekan "kirim" pada detik yang
     * sama mendapat nomor identik dan salah satunya gagal karena unique index —
     * dan itu justru terjadi pada jam sibuk PSB.
     */
    public static function daftarkan(array $data): self
    {
        return DB::transaction(function () use ($data) {
            $prefix = 'PSB-'.date('Y').'-';

            $terakhir = static::where('nomor_pendaftaran', 'like', $prefix.'%')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->value('nomor_pendaftaran');

            $urut = $terakhir ? ((int) substr($terakhir, -4)) + 1 : 1;

            return static::create([
                ...$data,
                'nomor_pendaftaran' => $prefix.str_pad((string) $urut, 4, '0', STR_PAD_LEFT),
                'status' => 'menunggu',
            ]);
        });
    }

    /*
     * Kepemilikan berkas pendaftaran.
     *
     * Halaman dokumen hanya boleh dibuka oleh orang yang terbukti memegang data
     * pendaftaran: baru saja mengisi formulir, atau berhasil melewati cek status
     * dengan nomor + email yang cocok. Buktinya disimpan di sesi, jadi nomor
     * pendaftaran yang berurutan tidak lagi cukup untuk membuka berkas orang lain.
     */

    public function beriAksesSesi(): void
    {
        session()->put(self::KUNCI_SESI.'.'.$this->nomor_pendaftaran, true);
    }

    public static function sesiBerhakAtas(?string $nomor): bool
    {
        return $nomor !== null && session()->get(self::KUNCI_SESI.'.'.$nomor) === true;
    }
}
