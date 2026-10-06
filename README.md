# Abdishope

Abdishope adalah aplikasi e-commerce berbasis Laravel untuk menjual produk, menerima pesanan, dan mengelola operasional toko dari dashboard admin. Antarmuka publik memakai identitas logo Abdishope, katalog produk, keranjang, checkout, serta metode pembayaran demo.

## Fitur

- Storefront responsif: beranda, katalog, kategori, dan detail produk.
- Akun pelanggan: daftar, masuk, keluar, dan checkout berbasis sesi.
- Keranjang belanja dan validasi stok.
- Checkout dan pencatatan pesanan dengan metode transfer bank, e-wallet, atau COD.
- Panel admin: dashboard toko, CRUD produk, CRUD berita & pemberitahuan, dan manajemen pengguna.
- Data tersimpan di MySQL melalui migration Laravel.

## Teknologi

- PHP 8.3+
- Laravel 13
- MySQL / MariaDB
- Blade, Tailwind CSS 4, Alpine.js, dan Vite

## Instalasi Lokal

1. Pasang dependensi.

```bash
composer install
npm install
```

2. Buat konfigurasi environment.

```bash
cp .env.example .env
php artisan key:generate
```

3. Buat database `abdishope` melalui phpMyAdmin atau MySQL, lalu isi `.env`.

```env
APP_NAME="Abdishope"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=abdishope
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

4. Buat tabel dan data demo toko.

```bash
php artisan migrate
php artisan db:seed --class=StoreSeeder
```

5. Build aset dan mulai aplikasi.

```bash
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Untuk mode pengembangan aset, jalankan `npm run dev` pada terminal kedua.

## Akun Demo

| Jenis | Email | Password |
| --- | --- | --- |
| Admin | `admin@abdishope.test` | `password` |

Pelanggan dapat membuat akun baru dari `/register`.

## Rute Utama

| Area | Rute | Keterangan |
| --- | --- | --- |
| Beranda | `/` | Storefront Abdishope |
| Katalog | `/shop` | Daftar dan filter produk |
| Detail produk | `/products/{slug}` | Detail dan tambah ke keranjang |
| Keranjang | `/cart` | Isi keranjang berbasis sesi |
| Checkout | `/checkout` | Membuat pesanan; wajib login |
| Login | `/login` | Login pelanggan dan admin |
| Register | `/register` | Pendaftaran pelanggan |
| Admin | `/admin` | Dashboard toko; wajib akun admin |
| Produk admin | `/admin/products` | CRUD produk |
| Berita & pemberitahuan | `/admin/announcements` | CRUD konten dan gambar sampul |
| Pengguna | `/admin/users` | Manajemen akun oleh admin |

## Pembayaran

Checkout saat ini menyimpan pilihan metode pembayaran ke tabel `orders`:

- Transfer bank
- E-wallet (GoPay / OVO / DANA)
- Cash on Delivery (COD)

Ini adalah alur demo; belum terhubung ke payment gateway seperti Midtrans, Xendit, atau QRIS.

## Tabel E-commerce

- `users` — admin dan pelanggan.
- `products` — katalog, stok, harga, gambar, dan status tampil.
- `orders` — informasi pembeli, alamat, metode/status pembayaran, dan total.
- `order_items` — produk dan jumlah pada setiap pesanan.
- `announcements` — berita dan pemberitahuan toko.

## Catatan Vite

Jika muncul error berikut:

```text
Vite manifest not found at: public/build/manifest.json
```

jalankan:

```bash
npm install
npm run build
```

## Testing

```bash
php artisan test
```

Lihat detail kebutuhan produk di [docs/PRD.md](docs/PRD.md).
