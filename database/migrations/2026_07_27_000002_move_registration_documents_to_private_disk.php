<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Pindahkan berkas pendaftaran dari disk publik ke disk privat.
 *
 * Berkas persyaratan berisi kartu keluarga, akta kelahiran, dan rapor. Selama
 * tersimpan di storage/app/public, berkas itu dapat diunduh siapa pun yang
 * menebak URL-nya begitu symlink storage dibuat. Pemindahan ini mengosongkan
 * jalur publik; aplikasi kini menyajikan berkas lewat rute unduh yang memeriksa
 * hak akses. Kolom path tidak berubah, hanya diskonya.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->pindahkan('public', 'local');
    }

    public function down(): void
    {
        $this->pindahkan('local', 'public');
    }

    protected function pindahkan(string $dari, string $ke): void
    {
        $paths = DB::table('registration_documents')->pluck('path');

        foreach ($paths as $path) {
            if (! Storage::disk($dari)->exists($path) || Storage::disk($ke)->exists($path)) {
                continue;
            }

            Storage::disk($ke)->writeStream($path, Storage::disk($dari)->readStream($path));
            Storage::disk($dari)->delete($path);
        }
    }
};
