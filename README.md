# Abdishope

Storefront e-commerce Abdishope untuk katalog produk kebutuhan rumah, busana, dan ritual harian. Aplikasi menyediakan katalog, keranjang berbasis session, checkout pelanggan, pencatatan pesanan, serta panel admin untuk mengelola produk dan pengguna.

Product Requirements Document: [docs/PRD.md](docs/PRD.md).

## Fitur

- Beranda, katalog kategori `home`, `wear`, dan `ritual`, serta detail produk.
- Keranjang belanja untuk pengunjung.
- Registrasi dan login pelanggan.
- Checkout dengan metode transfer bank, e-wallet, atau COD.
- Pencatatan pesanan dan item pesanan serta pengurangan stok.
- Dashboard toko dan CRUD produk/pengguna untuk admin.

> **Catatan codebase:** sejumlah route, view, model, dan migration untuk portal sekolah/PSB masih ada dari aplikasi sebelumnya. Area tersebut adalah legacy dan bukan bagian dari ruang lingkup produk Abdishope. Tinjau [PRD](docs/PRD.md) sebelum mengembangkan atau men-deploy fitur terkait.

## Persyaratan

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL/MariaDB (database bawaan pada `.env.example`) atau database lain yang didukung Laravel

## Menjalankan Secara Lokal

1. Pasang dependency PHP:

   ```bash
   composer install
   ```

2. Buat konfigurasi lokal dan application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Buat database lokal dan sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, serta `DB_PASSWORD` di `.env`. Nama database bawaan pada contoh adalah `portal_sekolah`; ganti sesuai database development Abdishope yang Anda siapkan.

4. Jalankan migration dan data contoh:

   ```bash
   php artisan migrate --seed
   ```

5. Buat symbolic link untuk file yang dikelola Laravel Storage:

   ```bash
   php artisan storage:link
   ```

6. Pasang dependency frontend dan build aset:

   ```bash
   npm install
   npm run build
   ```

7. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

   Buka URL yang ditampilkan, biasanya `http://127.0.0.1:8000`.

Untuk hot reload frontend, jalankan `npm run dev` di terminal terpisah. Script `composer run dev` juga tersedia untuk menjalankan server Laravel, Vite, queue listener, dan log viewer secara bersamaan.

## Akun Demo Lokal

Seeder utama membuat akun demo berikut dengan kata sandi `password`:

| Role | Email |
| --- | --- |
| Admin toko | `admin@sekolah.test` |
| Staf (akun legacy) | `staf@sekolah.test` |

Akun admin menggunakan alamat email demo lama yang belum diganti. Kredensial ini hanya untuk pengembangan lokal: jangan gunakan di server publik dan ganti atau hapus sebelum deployment.

## Rute Abdishope

| URL | Kegunaan |
| --- | --- |
| `/` | Beranda storefront |
| `/shop` | Katalog produk |
| `/products/{slug}` | Detail produk |
| `/cart` | Keranjang |
| `/checkout` | Checkout (perlu login) |
| `/orders/{order}` | Ringkasan pesanan (pemilik atau admin) |
| `/login` dan `/register` | Autentikasi |
| `/admin` | Dashboard admin |
| `/admin/products` | Pengelolaan produk |
| `/admin/users` | Pengelolaan pengguna |

## Test dan Build

```bash
php artisan test
npm run build
```

Test suite dikonfigurasi menggunakan SQLite in-memory pada `phpunit.xml`, sehingga pengujian tidak memerlukan database MySQL lokal.

## Teknologi

- PHP 8.3 dan Laravel 13
- Blade
- Tailwind CSS 4
- Alpine.js
- Vite
- PHPUnit 12

## Struktur Direktori

```text
app/                    Model, controller, dan middleware
database/migrations/    Skema database
database/seeders/       Data contoh dan akun demo
docs/PRD.md             Product Requirements Document Abdishope
resources/views/        Template Blade
routes/web.php          Rute aplikasi
tests/                  Unit dan feature tests
```

## Catatan Operasional

- Ongkos kirim checkout saat ini tetap Rp20.000.
- Metode pembayaran dicatat pada pesanan, tetapi belum terhubung ke payment gateway.
- Foto produk memakai URL eksternal; unggah dan pengelolaan gambar produk belum tersedia.
- Jangan menganggap rute dan fitur sekolah yang masih ada di repository sebagai bagian dari Abdishope.
