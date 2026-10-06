<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/products/{slug}', 'product')->name('product');
});

Route::post('/cart/{product}', [CheckoutController::class, 'add'])->name('cart.add');
Route::get('/cart', [CheckoutController::class, 'cart'])->name('cart');
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/orders/{order}', [CheckoutController::class, 'order'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Penerimaan Siswa Baru
|--------------------------------------------------------------------------
*/

Route::prefix('pendaftaran')->name('pendaftaran.')->controller(RegistrationController::class)->group(function () {
    Route::get('/', 'create')->name('form');
    Route::post('/', 'store')->middleware('throttle:pendaftaran')->name('store');
    Route::match(['get', 'post'], '/cek-status', 'cekStatus')->middleware('throttle:cek-status')->name('status');

    /*
     * Berkas pendaftaran. Nomor pendaftaran berurutan dan mudah ditebak, jadi
     * setiap rute di bawah ini menuntut bukti kepemilikan yang tersimpan di sesi
     * (lihat EnsurePendaftarTerverifikasi) — bukan sekadar nomor yang benar.
     */
    Route::middleware('pendaftar')->group(function () {
        Route::get('/{nomor}/dokumen', 'dokumen')->name('dokumen');
        Route::post('/{nomor}/dokumen', 'unggahDokumen')->middleware('throttle:unggah-dokumen')->name('dokumen.unggah');
        Route::get('/{nomor}/dokumen/{dokumen}', 'unduhDokumen')->name('dokumen.unduh');
        Route::delete('/{nomor}/dokumen/{dokumen}', 'hapusDokumen')->name('dokumen.hapus');
    });
});

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:login')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Panel Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'aktif'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Pendaftaran
    Route::get('/pendaftaran', [Admin\RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/{registration}', [Admin\RegistrationController::class, 'show'])->name('registrations.show');
    Route::get('/pendaftaran/{registration}/dokumen/{dokumen}', [Admin\RegistrationController::class, 'unduhDokumen'])->name('registrations.dokumen');
    Route::put('/pendaftaran/{registration}', [Admin\RegistrationController::class, 'update'])->name('registrations.update');
    Route::post('/pendaftaran/{registration}/jadikan-siswa', [Admin\RegistrationController::class, 'terima'])->name('registrations.terima');
    Route::delete('/pendaftaran/{registration}', [Admin\RegistrationController::class, 'destroy'])->name('registrations.destroy');

    // Siswa
    Route::resource('students', Admin\StudentController::class)->except('show');

    // Guru
    Route::resource('teachers', Admin\TeacherController::class)->except('show');

    // Berita & pengumuman
    Route::resource('announcements', Admin\AnnouncementController::class)->except('show');

    // Galeri
    Route::get('/galeri', [Admin\GalleryController::class, 'index'])->name('galleries.index');
    Route::post('/galeri', [Admin\GalleryController::class, 'store'])->name('galleries.store');
    Route::delete('/galeri/{gallery}', [Admin\GalleryController::class, 'destroy'])->name('galleries.destroy');

    // Profil sekolah
    Route::get('/profil-sekolah', [Admin\SchoolProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil-sekolah', [Admin\SchoolProfileController::class, 'update'])->name('profile.update');

    // Manajemen pengguna (khusus admin)
    Route::resource('users', Admin\UserController::class)->except('show')->middleware('admin');
    Route::resource('products', Admin\ProductController::class)->except('show')->middleware('admin');
});
