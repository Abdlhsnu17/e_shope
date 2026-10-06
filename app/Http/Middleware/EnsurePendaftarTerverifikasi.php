<?php

namespace App\Http\Middleware;

use App\Models\Registration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menjaga halaman berkas pendaftaran.
 *
 * Nomor pendaftaran berurutan dan mudah ditebak, sehingga nomor saja tidak boleh
 * menjadi kunci akses. Pengunjung harus lebih dulu membuktikan kepemilikan —
 * dengan mengirim formulir pendaftaran atau melewati cek status memakai nomor +
 * email yang cocok — yang menaruh bukti di sesi.
 *
 * Balasannya sengaja sama untuk nomor yang ada maupun tidak ada, agar halaman ini
 * tidak bisa dipakai memastikan apakah suatu nomor pendaftaran terdaftar.
 */
class EnsurePendaftarTerverifikasi
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Registration::sesiBerhakAtas($request->route('nomor'))) {
            return redirect()->route('pendaftaran.status')
                ->withInput(['nomor' => $request->route('nomor')])
                ->withErrors([
                    'nomor' => 'Untuk membuka berkas pendaftaran, masukkan nomor pendaftaran '
                        .'beserta email yang Anda gunakan saat mendaftar.',
                ]);
        }

        return $next($request);
    }
}
