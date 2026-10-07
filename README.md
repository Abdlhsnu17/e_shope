# Portal Sekolah / Abdishope

Aplikasi web Laravel yang saat ini menggabungkan alur Penerimaan Siswa Baru (PSB) dan administrasi sekolah dengan storefront Abdishope untuk katalog serta pemesanan produk. Nama dan navigasi kedua area belum sepenuhnya diseragamkan; rincian fitur dan batasannya ada di [docs/PRD.md](docs/PRD.md).

## Fitur

- Formulir PSB publik, nomor pendaftaran, cek status, dan pengelolaan dokumen pendaftar.
- Panel administrasi untuk pendaftaran, siswa, guru, berita/pemberitahuan, galeri, profil sekolah, akun, dan produk.
- Storefront: beranda, katalog, detail produk, keranjang, checkout, dan konfirmasi pesanan.
- Role pengguna: `admin`, `staf`, `guru`, dan `customer`.
- Perlindungan akses dokumen pendaftar, akun aktif, validasi server-side, serta pembatasan laju pada endpoint publik sensitif.

## Persyaratan

- PHP 8.3 atau lebih baru.
- Composer.
- Node.js dan npm.
- MySQL/MariaDB (konfigurasi bawaan) atau database lain yang didukung Laravel dan dikonfigurasi di `.env`.

## Menjalankan Secara Lokal

1. Pasang dependency PHP:

   ```bash
   composer install
   ```

2. Buat file `.env` dari contoh dan buat application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Buat database lokal, lalu sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env`. Nilai bawaan nama database adalah `portal_sekolah`.

4. Jalankan migration dan data contoh:

   ```bash
   php artisan migrate --seed
   ```

5. Buat symbolic link untuk file publik yang dikelola Laravel Storage:

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

   Buka URL yang ditampilkan oleh Artisan, biasanya `http://127.0.0.1:8000`.

Untuk pengembangan frontend dengan hot reload, jalankan `npm run dev` di terminal terpisah. Script `composer run dev` juga tersedia untuk menjalankan server Laravel, Vite, queue listener, dan log viewer bersamaan.

## Akun Demo

Seeder lokal membuat akun berikut dengan kata sandi `password`:

| Role | Email |
| --- | --- |
| Admin | `admin@sekolah.test` |
| Staf | `staf@sekolah.test` |

Kredensial tersebut hanya untuk pengembangan lokal. Jangan gunakan akun atau kata sandi demo di lingkungan yang dapat diakses publik. Ubah atau hapus akun demo sebelum deployment.

## Rute Utama

| URL | Fungsi |
| --- | --- |
| `/` | Beranda storefront Abdishope |
| `/shop` | Katalog produk |
| `/cart` | Keranjang belanja |
| `/login` dan `/register` | Masuk dan registrasi akun |
| `/pendaftaran` | Formulir Penerimaan Siswa Baru |
| `/pendaftaran/cek-status` | Cek status dengan nomor pendaftaran dan email |
| `/admin` | Dashboard administrasi |

Checkout memerlukan login. Berkas PSB hanya dapat dikelola setelah pendaftar membuktikan kepemilikan melalui session dari pengiriman formulir atau verifikasi nomor pendaftaran dan email.

## Pengujian dan Build

Jalankan test suite:

```bash
php artisan test
```

Build aset frontend:

```bash
npm run build
```

Test memakai SQLite in-memory sesuai konfigurasi `phpunit.xml`; pengujian tidak memerlukan database MySQL lokal.

## Teknologi

- PHP 8.3 dan Laravel 13
- Blade
- Tailwind CSS 4
- Alpine.js
- Vite
- PHPUnit 12

## Struktur Direktori

```text
app/                    Model, middleware, dan controller
database/migrations/    Skema database
database/seeders/       Data contoh dan akun demo
docs/PRD.md             Product Requirements Document
resources/views/        Template Blade
routes/web.php          Rute aplikasi
tests/                  Unit dan feature tests
```

## Catatan Pengembangan

- Konfigurasi contoh memakai MySQL, session/cache/queue berbasis database, dan `APP_LOCALE=id`.
- Seed data sekolah, akun demo, galeri, konten, pendaftar, dan produk merupakan data contoh.
- Dokumen pendaftaran disimpan pada disk privat (`local`), tidak pada disk publik.
- Metode pembayaran toko masih berupa pencatatan pilihan; integrasi gateway dan verifikasi pembayaran belum tersedia.
- Beberapa modul sekolah tersedia melalui route administrasi tetapi belum seluruhnya ditautkan dari sidebar. Periksa hak akses dan konfigurasi environment sebelum deployment.
