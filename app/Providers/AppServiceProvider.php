<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->batasiLajuPermintaan();
    }

    /**
     * Pembatas laju untuk jalur yang terbuka ke internet.
     *
     * Semua endpoint di bawah ini bisa diakses tanpa login, jadi tanpa pembatas
     * satu skrip bisa menebak kata sandi, membanjiri panitia dengan pendaftar
     * palsu, atau menyisir kombinasi nomor + email pada halaman cek status.
     */
    protected function batasiLajuPermintaan(): void
    {
        // Dibatasi per akun sekaligus per alamat IP: menebak satu kata sandi pada
        // banyak akun harus sama tertahannya dengan menebak banyak kata sandi
        // pada satu akun.
        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(5)->by(Str::lower((string) $request->input('email')).'|'.$request->ip()),
            Limit::perMinute(20)->by((string) $request->ip()),
        ]);

        RateLimiter::for('pendaftaran', fn (Request $request) => Limit::perHour(5)->by((string) $request->ip()));

        RateLimiter::for('cek-status', fn (Request $request) => Limit::perMinute(10)->by((string) $request->ip()));

        RateLimiter::for('unggah-dokumen', fn (Request $request) => Limit::perMinute(20)->by((string) $request->ip()));
    }
}
