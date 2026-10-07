# Product Requirements Document — Portal Sekolah

## 1. Ringkasan Produk

Codebase ini berisi aplikasi web Laravel dengan dua kelompok kemampuan yang saat ini berjalan berdampingan:

1. **Portal sekolah**, terutama alur Penerimaan Siswa Baru (PSB), pengelolaan pendaftar dan dokumen, data siswa dan guru, konten, galeri, serta profil sekolah.
2. **Storefront Abdishope**, dengan katalog produk, keranjang, checkout, dan pencatatan pesanan.

Nama aplikasi pada konfigurasi awal adalah `Portal Sekolah`, sedangkan storefront dan sebagian besar panel admin menggunakan branding `Abdishope`. Karena itu dokumen ini menjelaskan perilaku codebase sebagaimana adanya dan tidak menganggap kedua area tersebut sudah terintegrasi sebagai satu pengalaman produk yang konsisten.

## 2. Tujuan

- Memudahkan calon siswa mengirim pendaftaran, mengunggah persyaratan, dan memantau status seleksi.
- Membantu petugas mengelola pendaftar, dokumen, serta memindahkan pendaftar yang diterima ke data siswa.
- Menyediakan pengelolaan data siswa, guru, pengguna, berita/pemberitahuan, galeri, dan profil sekolah melalui rute administrasi.
- Menyediakan alur katalog dan pemesanan produk Abdishope untuk pengunjung dan pelanggan.
- Melindungi data sensitif pendaftar melalui validasi, pembatasan permintaan, dan akses dokumen terverifikasi.

## 3. Pengguna dan Peran

| Pengguna | Kebutuhan dan akses yang tersedia |
| --- | --- |
| Pengunjung | Membuka storefront, melihat katalog dan detail produk, memakai keranjang, serta mengisi formulir PSB dan cek status. |
| Pelanggan (`customer`) | Membuat akun, login, checkout, dan membuka halaman pesanan miliknya. |
| Staf (`staf`) | Login ke panel/rute administrasi yang dilindungi autentikasi; dapat memproses pendaftaran dan mengelola beberapa data sekolah. |
| Guru (`guru`) | Akun tersedia dalam model pengguna dan dapat login; akses administrasi umum mengikuti middleware autentikasi yang dipakai rute. |
| Admin (`admin`) | Mengakses rute administrasi umum serta, secara khusus, manajemen produk dan pengguna. |
| Panitia PSB | Menggunakan rute administrasi pendaftaran untuk meninjau berkas, memperbarui status, dan menerima pendaftar sebagai siswa. |

Catatan akses: middleware `admin` diterapkan pada rute manajemen produk dan pengguna. Rute panel lainnya mensyaratkan login dan akun aktif, tetapi belum membatasi berdasarkan role tertentu. Perilaku ini penting dipahami sebelum aplikasi dibuka untuk penggunaan produksi.

## 4. Ruang Lingkup Fungsional Saat Ini

### 4.1 Penerimaan Siswa Baru

- Formulir publik menangkap identitas calon siswa, NISN opsional, jenis kelamin, tanggal lahir, asal sekolah, jurusan pilihan, kontak, alamat, dan data wali.
- Pendaftaran diberi nomor berurutan dengan format `PSB-{tahun}-{nomor empat digit}` dan status awal `menunggu`.
- Pengiriman formulir dibatasi hingga 5 permintaan per jam per alamat IP.
- Pendaftar dapat membuka pengelolaan dokumen setelah mengirim formulir atau berhasil mencocokkan nomor pendaftaran dan email pada halaman cek status.
- Dokumen yang didukung: PDF, JPG/JPEG, dan PNG; ukuran maksimum 2 MB per berkas; maksimum 12 dokumen per pendaftar.
- Dokumen disimpan pada disk `local` privat dan hanya diunduh melalui rute yang memeriksa kepemilikan atau autentikasi petugas.
- Pendaftar dapat melihat progres/status dan catatan panitia setelah verifikasi nomor pendaftaran dan email.
- Status pendaftaran yang tersedia: `menunggu`, `diverifikasi`, `diterima`, dan `ditolak`.
- Petugas dapat mencari dan memfilter pendaftar, melihat/mengunduh dokumen, memperbarui status/catatan, menghapus pendaftaran, serta mengubah pendaftar berstatus `diterima` menjadi siswa aktif.
- Penomoran NIS baru dilakukan ketika pendaftar diterima menjadi siswa.

### 4.2 Administrasi Sekolah

- CRUD data siswa, termasuk NIS, NISN, kelas, jurusan, tanggal lahir, wali, dan status.
- CRUD data guru, termasuk NIP, mata pelajaran, jabatan, kontak, tanggal bergabung, dan status.
- CRUD berita dan pemberitahuan, termasuk kategori, ringkasan, isi, gambar sampul, dan status publikasi.
- Pengelolaan galeri foto dengan kategori, deskripsi, unggah gambar, dan hapus.
- Pengelolaan profil sekolah yang mencakup nama, NPSN, jenjang, akreditasi, kepala sekolah, kontak, alamat, visi, misi, dan sejarah.
- Manajemen akun oleh admin, mencakup role, status aktif, serta penggantian kata sandi.
- Pencarian/filter dan paginasi tersedia pada beberapa daftar administrasi.

### 4.3 Storefront dan Pemesanan Abdishope

- Beranda menampilkan produk aktif terbaru dan tautan ke katalog.
- Katalog dan detail produk hanya menampilkan produk aktif; katalog dapat difilter melalui kategori `home`, `wear`, dan `ritual`.
- Keranjang disimpan pada session browser dan mendukung penambahan produk dengan jumlah yang dibatasi stok saat ditambahkan.
- Checkout memerlukan autentikasi dan mengumpulkan nama penerima, telepon, alamat, serta pilihan metode pembayaran.
- Metode pembayaran yang ditampilkan: transfer bank, e-wallet, dan COD.
- Pesanan dan baris item dicatat ke database dalam transaksi; stok dikurangi dan isi keranjang dibersihkan setelah pesanan berhasil dibuat.
- Halaman pesanan hanya dapat diakses pemilik pesanan atau admin.
- Dashboard toko menampilkan jumlah produk, stok, pelanggan, pesanan, total pembayaran berstatus lunas, dan pesanan terbaru.
- Admin dapat mengelola data produk: nama, kategori, tipe, deskripsi, harga, harga lama, URL gambar, tag, stok, dan status aktif.

### 4.4 Autentikasi dan Keamanan

- Registrasi storefront membuat akun ber-role `customer`; kata sandi minimal 8 karakter dan disimpan sebagai hash.
- Login, logout, regenerasi session, dan opsi “ingat saya” tersedia.
- Akun yang tidak aktif ditolak saat login dan sesi aktifnya ditutup ketika mengakses rute administrasi.
- Percobaan login, pengiriman formulir PSB, cek status, dan unggah dokumen dibatasi lajunya.
- Pengelolaan akun mencegah admin menurunkan role, menonaktifkan, atau menghapus akunnya sendiri melalui halaman manajemen pengguna.
- Nomor pendaftaran saja tidak cukup untuk membuka atau mengelola dokumen; bukti kepemilikan disimpan di session setelah formulir dikirim atau cek status sukses.

## 5. Data Utama

| Entitas | Kegunaan |
| --- | --- |
| `users` | Akun, role, status aktif, kontak, dan waktu login terakhir. |
| `school_profiles` | Informasi profil dan identitas sekolah. |
| `registrations` | Formulir, nomor, status seleksi, dan catatan pendaftar. |
| `registration_documents` | Metadata dan lokasi privat berkas persyaratan. |
| `students` | Data siswa yang dikelola atau dibuat dari pendaftar yang diterima. |
| `teachers` | Data tenaga pendidik. |
| `announcements` | Berita dan pemberitahuan. |
| `galleries` | Metadata foto galeri. |
| `products` | Katalog produk dan inventaris Abdishope. |
| `orders` | Penerima, metode/status pembayaran, status pesanan, dan jumlah transaksi. |
| `order_items` | Snapshot nama, harga, jumlah, dan subtotal produk saat dipesan. |

Skema database dibuat melalui Laravel migrations. File `.env.example` menggunakan MySQL sebagai konfigurasi bawaan; konfigurasi pengujian memakai SQLite in-memory.

## 6. Aturan Bisnis Penting

- Pendaftaran baru selalu dimulai dengan status `menunggu`.
- Pendaftar hanya dapat dipromosikan menjadi siswa bila statusnya `diterima`.
- NIS siswa harus unik; pembuatan siswa dari pendaftaran menyalin informasi pendaftar yang tersedia.
- Dokumen pendaftaran tidak boleh disajikan sebagai URL publik.
- Keranjang adalah data session, bukan keranjang persisten per akun.
- Checkout menetapkan biaya pengiriman tetap Rp20.000.
- Pesanan menyimpan `payment_status` awal `pending`; tidak ada gateway pembayaran yang dikonfigurasi dalam codebase.
- Produk yang tidak aktif tidak muncul pada katalog dan tidak dimasukkan ke item keranjang saat daftar item dibangun.

## 7. Kebutuhan Nonfungsional

- Antarmuka berbahasa Indonesia dan mendukung layar desktop maupun mobile melalui Blade dan Tailwind CSS.
- Validasi input dilakukan di sisi server.
- Kata sandi tidak disimpan dalam bentuk teks biasa.
- Endpoint publik sensitif harus mempertahankan rate limit yang telah dikonfigurasi.
- Hak akses dokumen pendaftar harus tetap diperiksa pada setiap operasi lihat, unggah, unduh, dan hapus.
- Test otomatis harus melindungi aturan akses, rate limit, dan penomoran.

## 8. Teknologi

- PHP `^8.3` dan Laravel `^13.8`.
- Blade untuk rendering halaman.
- Tailwind CSS 4, Alpine.js, dan Vite untuk antarmuka/aset.
- Database relasional; konfigurasi default lokal pada `.env.example` adalah MySQL.
- PHPUnit 12 melalui `php artisan test`.

## 9. Kriteria Penerimaan

### PSB dan administrasi sekolah

- Pengunjung dapat mengirim formulir PSB dan menerima nomor pendaftaran.
- Pendaftar dapat memeriksa status hanya dengan pasangan nomor pendaftaran dan email yang cocok.
- Pendaftar yang terverifikasi dapat mengunggah, mengunduh, dan menghapus dokumen; pengunjung lain tidak dapat mengakses berkas tersebut.
- Petugas dapat mencari pendaftaran, memperbarui status/catatan, dan mengunduh dokumen.
- Pendaftar berstatus `diterima` dapat dibuat menjadi siswa dengan NIS unik.
- Admin dapat mengelola pengguna dan produk; pengguna terautentikasi dapat mengakses rute administrasi lain sesuai konfigurasi middleware saat ini.

### Storefront

- Pengunjung dapat melihat beranda, produk aktif, katalog, dan detail produk.
- Pengunjung dapat menambahkan produk ke keranjang, dan pelanggan terautentikasi dapat membuat pesanan.
- Pesanan menyimpan rincian produk dan memperbarui stok.
- Pemilik pesanan dapat membuka ringkasan pesanan; pengguna lain ditolak.

### Kualitas

- `php artisan test` lulus.
- `npm run build` membangun aset Vite.

## 10. Batasan dan Kesenjangan yang Terlihat

- Identitas dan alur produk masih bercampur: konfigurasi menyebut `Portal Sekolah`, sedangkan storefront dan dashboard memakai `Abdishope`.
- Beranda publik yang terdaftar pada `/` adalah storefront. Belum terlihat halaman depan sekolah, halaman publik berita, halaman publik galeri, atau halaman profil sekolah.
- CRUD galeri, profil sekolah, siswa, guru, dan pendaftaran tersedia melalui route, tetapi tidak seluruhnya ditampilkan pada navigasi sidebar admin.
- Role `staf` dan `guru` ada, tetapi aturan otorisasi rute sekolah belum membedakan hak akses role tersebut dari semua pengguna yang sudah login dan aktif.
- Tidak ada alur administrasi pesanan untuk memperbarui status pembayaran/pemenuhan atau memproses refund.
- Metode pembayaran hanya dicatat; transfer/e-wallet tidak diverifikasi otomatis dan tidak terhubung ke payment gateway.
- Instruksi pembayaran pada halaman hasil pesanan adalah instruksi statis/demo.
- Ongkir bersifat tetap; belum ada integrasi kurir.
- Belum terlihat halaman riwayat seluruh pesanan pelanggan.
- Tidak ada notifikasi email/WhatsApp yang terintegrasi.
- Gambar produk menggunakan URL, sedangkan gambar berita/galeri menggunakan storage publik.
- Data contoh pada seeder mencakup profil sekolah, akun demo, siswa, guru, konten, galeri, pendaftar, dan produk.

## 11. Prioritas Pengembangan Lanjutan

1. Tegaskan apakah aplikasi akan menjadi portal sekolah, toko Abdishope, atau dua produk dalam satu aplikasi; samakan nama, navigasi, dan halaman awal dengan keputusan tersebut.
2. Definisikan matriks role-permission dan terapkan pembatasan role pada setiap route administrasi.
3. Jika fokusnya portal sekolah, tambahkan halaman publik profil sekolah, berita, galeri, dan tautan PSB pada navigasi publik.
4. Jika toko tetap dipakai, tambahkan manajemen pesanan, riwayat pesanan pelanggan, dan status pemenuhan.
5. Integrasikan payment gateway dan kurir hanya setelah kebijakan transaksi, pembatalan, dan pengembalian disepakati.
6. Tambahkan validasi alur end-to-end untuk checkout, stok saat checkout bersamaan, serta hak akses tiap role.
