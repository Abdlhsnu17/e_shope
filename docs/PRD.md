# Product Requirements Document: Abdishope

## 1. Ringkasan

Abdishope adalah aplikasi e-commerce untuk katalog produk, transaksi pesanan, serta pengelolaan toko melalui panel admin. Produk menyediakan pengalaman belanja yang responsif bagi pelanggan dan workspace operasional untuk administrator.

## 2. Tujuan Produk

- Menyediakan storefront yang cepat dan mudah dipakai untuk menjelajahi serta membeli produk.
- Membuat proses tambah keranjang dan checkout sederhana serta tercatat di database.
- Memungkinkan admin mengelola katalog, stok, pengguna, berita, dan pemberitahuan tanpa mengubah kode.
- Menjadi fondasi integrasi pembayaran online dan pengiriman pada tahap berikutnya.

## 3. Pengguna

| Pengguna | Kebutuhan utama |
| --- | --- |
| Pengunjung | Melihat beranda, katalog, kategori, dan detail produk. |
| Pelanggan | Mendaftar, login, menyimpan pesanan, checkout, dan melihat konfirmasi pesanan. |
| Admin | Mengelola produk, stok, pengguna, berita/pemberitahuan, serta memantau pesanan dan pendapatan. |

## 4. Ruang Lingkup Fitur

### 4.1 Storefront

- Beranda dengan hero, koleksi unggulan, kategori, dan konten editorial.
- Katalog seluruh produk dengan filter kategori `home`, `wear`, dan `ritual`.
- Halaman detail produk berisi gambar, harga, deskripsi, stok, dan form tambah keranjang.
- Branding Abdishope pada header, footer, halaman autentikasi, dan dashboard admin.
- Tampilan responsif untuk mobile dan desktop.

### 4.2 Akun dan Autentikasi

- Registrasi pelanggan dengan nama, email, nomor WhatsApp, dan kata sandi.
- Login, logout, dan opsi ingat saya.
- Redirect pelanggan ke storefront dan admin ke dashboard admin setelah login.
- Akun tidak aktif tidak dapat login.

### 4.3 Keranjang dan Checkout

- Keranjang berbasis session.
- Validasi jumlah pembelian terhadap stok produk.
- Checkout hanya tersedia untuk pelanggan yang telah login.
- Pengumpulan nama penerima, nomor telepon, dan alamat pengiriman.
- Perhitungan subtotal, biaya kirim tetap, dan total pesanan.
- Pencatatan order number dan detail item pesanan.

### 4.4 Metode Pembayaran

- Transfer bank.
- E-wallet: GoPay, OVO, atau DANA.
- Cash on Delivery (COD).
- Status awal pembayaran adalah `pending`.
- Instruksi metode pembayaran ditampilkan setelah pesanan dibuat.

### 4.5 Panel Admin

- Dashboard berisi jumlah produk, stok, pelanggan, pesanan, pendapatan pembayaran lunas, dan daftar pesanan terbaru.
- CRUD produk: nama, kategori, tipe, deskripsi, harga, harga diskon, URL gambar, label, stok, dan status tampil.
- CRUD berita & pemberitahuan: judul, kategori, ringkasan, isi, sampul, status draf/terbit, pencarian, dan filter.
- CRUD pengguna dengan pembatasan agar admin tidak dapat mencabut akses admin dirinya sendiri.

## 5. Hak Akses

| Role | Hak akses |
| --- | --- |
| Guest | Storefront, katalog, detail produk, dan keranjang. |
| Customer | Seluruh akses guest serta checkout dan konfirmasi pesanan miliknya. |
| Admin | Dashboard dan seluruh CRUD toko. |

## 6. Data Utama

| Tabel | Fungsi |
| --- | --- |
| `users` | Data pelanggan dan admin. |
| `products` | Katalog, harga, stok, gambar, serta status publikasi produk. |
| `orders` | Data penerima, pembayaran, status proses, subtotal, ongkir, dan total. |
| `order_items` | Snapshot produk, harga, dan kuantitas per pesanan. |
| `announcements` | Berita dan pemberitahuan toko. |

Database pengembangan lokal bernama `abdishope`.

## 7. Kebutuhan UI/UX

- Identitas visual Abdishope harus konsisten di seluruh halaman.
- Harga memakai format Rupiah.
- CTA keranjang dan checkout harus terlihat jelas.
- Form memberikan validasi server-side yang mudah dipahami.
- Status pembayaran dan pesanan selalu ditampilkan dengan teks, bukan warna saja.
- Dashboard admin harus menonjolkan pekerjaan operasional tanpa konten akademik/sekolah.

## 8. Kebutuhan Teknis

- Laravel 13 untuk backend dan routing.
- Blade, Tailwind CSS, Alpine.js, dan Vite untuk antarmuka.
- MySQL/MariaDB dengan migration Laravel.
- Aset Vite dibangun ke `public/build/manifest.json`.
- Seeder `StoreSeeder` menyediakan admin dan produk demo.
- File gambar berita dikelola oleh Laravel Storage.

## 9. Acceptance Criteria

- Pengunjung dapat melihat katalog dan detail produk tanpa login.
- Pengunjung dapat menambahkan produk ke keranjang.
- Pelanggan dapat membuat akun, login, dan menyelesaikan checkout.
- Sistem membuat `orders` dan `order_items` setelah checkout berhasil.
- Stok berkurang sesuai jumlah produk yang dipesan.
- Admin dapat login ke `/admin` dan melihat metrik toko.
- Admin dapat membuat, mengubah, dan menghapus produk.
- Admin dapat membuat, mengubah, dan menghapus berita/pemberitahuan.
- Admin dapat mengelola akun pengguna.
- `npm run build` menghasilkan manifest Vite.
- Blade template dapat dikompilasi tanpa error.

## 10. Batasan Saat Ini

- Pembayaran belum terhubung ke gateway pihak ketiga; metode pembayaran masih alur demo.
- Belum ada verifikasi otomatis pembayaran atau webhook.
- Belum ada integrasi kurir dan kalkulasi ongkir dinamis.
- Keranjang belum tersimpan permanen ke akun pengguna.
- Belum tersedia manajemen pesanan lengkap untuk admin (ubah status, resi, dan refund).
- Belum ada notifikasi email/WhatsApp.

## 11. Prioritas Berikutnya

1. Integrasi Midtrans/Xendit, VA, QRIS, dan webhook pembayaran.
2. Manajemen pesanan admin, status pengiriman, dan nomor resi.
3. Integrasi kurir serta ongkir otomatis.
4. Riwayat pesanan pelanggan.
5. Notifikasi email/WhatsApp.
6. Promo, voucher, dan pengelolaan kategori dinamis.
