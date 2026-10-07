# Product Requirements Document — Abdishope

## 1. Ringkasan Produk

Abdishope adalah storefront e-commerce untuk menjelajahi dan memesan produk kebutuhan rumah, busana, dan ritual harian. Aplikasi menyediakan katalog publik, keranjang belanja, checkout untuk pelanggan terdaftar, pencatatan pesanan, dan panel admin untuk mengelola produk serta memantau ringkasan toko.

Dokumen ini menetapkan Abdishope sebagai identitas dan ruang lingkup produk. Beberapa modul administrasi sekolah dan pendaftaran masih tersisa di codebase, tetapi merupakan legacy/out-of-scope dan bukan bagian dari pengalaman produk Abdishope yang dituju.

## 2. Tujuan Produk

- Menyediakan pengalaman belanja yang responsif dan mudah dipahami.
- Membantu pengunjung menemukan produk melalui beranda, katalog, kategori, dan halaman detail.
- Mendukung alur keranjang dan checkout dengan pesanan yang tercatat secara konsisten.
- Memberikan alat kepada admin untuk memelihara katalog dan memantau indikator toko.
- Menjaga akun, pesanan, dan operasi administrasi di balik autentikasi serta validasi server.

## 3. Pengguna

| Pengguna | Kebutuhan |
| --- | --- |
| Pengunjung | Menjelajahi katalog dan detail produk, lalu menambahkan produk ke keranjang. |
| Pelanggan (`customer`) | Membuat akun, masuk, menyelesaikan checkout, dan melihat pesanan miliknya. |
| Admin | Mengelola katalog produk dan pengguna serta melihat ringkasan toko/pesanan terbaru. |

## 4. Ruang Lingkup Fitur Saat Ini

### 4.1 Storefront dan katalog

- Beranda Abdishope menampilkan produk aktif terbaru dan koleksi pilihan.
- Katalog hanya menampilkan produk aktif.
- Kategori katalog: `home`, `wear`, dan `ritual`.
- Halaman detail produk menampilkan informasi produk dan opsi untuk menambahkan ke keranjang.
- Harga ditampilkan dalam format Rupiah.
- Antarmuka dirancang responsif untuk perangkat desktop dan mobile.

### 4.2 Akun dan autentikasi

- Registrasi pelanggan menggunakan nama, email, nomor telepon opsional, dan kata sandi terkonfirmasi dengan panjang minimal 8 karakter.
- Login, logout, regenerasi session, dan opsi “ingat saya” tersedia.
- Pelanggan diarahkan kembali ke storefront setelah login; admin diarahkan ke dashboard.
- Akun nonaktif tidak dapat login; sesi akun yang dinonaktifkan akan ditutup saat mengakses rute yang dilindungi middleware akun aktif.
- Admin dapat mengelola akun pengguna dan role melalui panel admin.

### 4.3 Keranjang dan checkout

- Keranjang disimpan di session browser, bukan secara persisten pada akun pelanggan.
- Pengunjung dapat menambahkan produk ke keranjang tanpa login.
- Jumlah saat penambahan divalidasi agar minimal 1 dan tidak melebihi stok yang tersedia pada saat itu.
- Checkout memerlukan login.
- Checkout mengumpulkan nama penerima, nomor telepon, alamat, dan metode pembayaran.
- Metode pembayaran yang tersedia pada antarmuka: transfer bank, e-wallet, dan COD.
- Ongkos kirim saat ini berupa biaya tetap Rp20.000.
- Sistem menyimpan pesanan beserta snapshot nama, harga, jumlah, dan subtotal setiap item.
- Pembuatan pesanan dilakukan dalam transaksi database; stok dikurangi dan keranjang dibersihkan setelah pesanan berhasil dibuat.
- Pemilik pesanan dan admin dapat membuka halaman pesanan; pengguna lain ditolak.

### 4.4 Panel admin

- Dashboard menampilkan jumlah produk, total stok, jumlah pelanggan, jumlah pesanan, pendapatan dari pesanan berstatus pembayaran lunas, dan pesanan terbaru.
- CRUD produk mencakup nama, kategori, tipe, deskripsi, harga, harga lama, URL gambar, tag, stok, dan status aktif.
- Manajemen pengguna mencakup nama, email, role, telepon, status aktif, dan kata sandi.
- Admin tidak dapat menghapus dirinya sendiri atau mencabut akses adminnya sendiri melalui formulir manajemen pengguna.

## 5. Peran dan Hak Akses

| Peran | Akses produk |
| --- | --- |
| Guest | Beranda, katalog, detail produk, dan keranjang. |
| Customer | Akses guest, checkout, dan halaman pesanan miliknya. |
| Admin | Dashboard, pengelolaan produk/pengguna, serta akses admin ke halaman pesanan. |

Role `staf` dan `guru` juga ada pada model pengguna karena modul legacy. Role tersebut bukan bagian dari model peran toko yang dituju. Sebelum deployment, hak akses semua route perlu ditinjau dan dibatasi sesuai matriks peran Abdishope.

## 6. Model Data Toko

| Entitas | Fungsi |
| --- | --- |
| `users` | Akun pelanggan/admin, role, status aktif, dan metadata login. |
| `products` | Katalog, harga, stok, gambar, kategori, dan status aktif. |
| `orders` | Pemilik, penerima, metode/status pembayaran, status pesanan, subtotal, ongkir, dan total. |
| `order_items` | Snapshot produk dan rincian harga/jumlah saat pesanan dibuat. |

Codebase juga memiliki tabel dan model sekolah legacy, antara lain `school_profiles`, `registrations`, `registration_documents`, `students`, `teachers`, `announcements`, dan `galleries`. Entitas tersebut tidak termasuk model domain Abdishope dan sebaiknya dipisahkan atau dihapus melalui migrasi terencana setelah dependensi serta data yang perlu dipertahankan ditinjau.

## 7. Aturan Bisnis

- Hanya produk aktif yang ditampilkan di katalog dan detail produk.
- Stok produk tidak boleh negatif melalui validasi form admin dan validasi kuantitas saat produk ditambahkan ke keranjang.
- Checkout memerlukan keranjang yang memiliki item.
- Total pesanan dihitung dari subtotal item ditambah ongkos kirim tetap Rp20.000.
- Status pembayaran awal pesanan adalah `pending`; status pesanan awal adalah `new`.
- `order_items` menyimpan snapshot agar detail nama dan harga pada pesanan tetap tersedia jika produk berubah atau dihapus.
- Pesanan hanya dapat dilihat oleh pemilik pesanan atau admin.

## 8. Kebutuhan Nonfungsional

- Antarmuka utama berbahasa Indonesia, dengan identitas visual Abdishope yang konsisten.
- Formulir memvalidasi input di server dan menampilkan pesan kesalahan yang dapat dipahami.
- Kata sandi harus disimpan dalam bentuk hash.
- Rute yang memerlukan akun harus menolak guest; akun nonaktif tidak boleh terus menggunakan sesi yang dilindungi.
- Perubahan katalog dan pesanan harus disimpan melalui database serta migration Laravel.
- Build frontend dilakukan oleh Vite.

## 9. Kriteria Penerimaan

- Pengunjung dapat membuka beranda, katalog, dan detail produk aktif.
- Katalog dapat difilter berdasarkan kategori toko.
- Pengunjung dapat memasukkan produk ke keranjang; checkout tanpa login ditolak.
- Pelanggan yang login dapat membuat pesanan dengan data penerima dan salah satu metode pembayaran yang tersedia.
- Pesanan dan item pesanan tercatat, stok dikurangi, dan keranjang dikosongkan setelah checkout berhasil.
- Pemilik dapat membuka pesanannya dan pengguna lain tidak dapat membukanya.
- Admin dapat menambah, mengubah, dan menghapus produk serta mengelola akun.
- Dashboard menampilkan indikator toko dan pesanan terbaru.
- `npm run build` membangun aset frontend dan `php artisan test` menjalankan test suite.

## 10. Batasan Saat Ini

- Payment gateway belum terintegrasi; pilihan transfer dan e-wallet belum diverifikasi otomatis, webhook pembayaran tidak tersedia, dan instruksi transfer di halaman pesanan bersifat statis/demo.
- Belum ada manajemen pesanan admin untuk memperbarui status pembayaran/pemenuhan, mengelola pengiriman, atau memproses refund.
- Belum ada riwayat pesanan pelanggan khusus.
- Belum ada integrasi kurir atau perhitungan ongkir dinamis.
- Validasi jumlah stok dilakukan saat penambahan ke keranjang; implementasi checkout perlu diperkuat dengan validasi ulang stok di dalam transaksi untuk mencegah overselling saat permintaan bersamaan.
- Keranjang tidak tersimpan lintas perangkat atau lintas session.
- Gambar produk disimpan sebagai URL eksternal, bukan dikelola melalui unggahan gambar produk.
- Fitur newsletter, journal, dan tautan bantuan yang tampil pada storefront belum memiliki alur backend yang terimplementasi.
- Modul administrasi dan PSB sekolah masih tertinggal dalam route, view, model, dan migration. Modul tersebut tidak boleh dianggap sebagai bagian dari produk Abdishope.

## 11. Prioritas Pengembangan

1. Pisahkan domain Abdishope dari modul sekolah legacy; tinjau rute, menu, migration, seeder, dan data sebelum menghapus atau memindahkan apa pun.
2. Audit seluruh route admin dan terapkan middleware role admin secara konsisten.
3. Validasi ulang stok di dalam transaksi checkout dan tentukan perilaku jika stok berubah setelah produk masuk keranjang.
4. Tambahkan alur administrasi pesanan, pembaruan status, riwayat pelanggan, dan pengelolaan pengiriman.
5. Integrasikan payment gateway setelah alur status pembayaran, pembatalan, dan refund ditentukan.
6. Tambahkan ongkir dinamis serta notifikasi pesanan setelah kebutuhan operasional ditetapkan.
