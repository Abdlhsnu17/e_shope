<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Registration;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProductSeeder::class);
        $this->profilSekolah();
        $this->pengguna();
        $this->guru();
        $this->siswa();
        $this->konten();
        $this->galeri();
        $this->pendaftaran();
    }

    protected function profilSekolah(): void
    {
        SchoolProfile::current()->update([
            'nama' => 'SMA Nusantara Jaya',
            'npsn' => '20101234',
            'jenjang' => 'SMA',
            'akreditasi' => 'A',
            'kepala_sekolah' => 'Dr. Hendra Wijaya, M.Pd.',
            'email' => 'info@smanusantarajaya.sch.id',
            'telepon' => '(021) 8765-4321',
            'alamat' => 'Jl. Pendidikan Raya No. 45, Kelurahan Sukamaju, Jakarta Timur 13450',
            'visi' => 'Menjadi sekolah unggul yang membentuk generasi berkarakter, berprestasi, dan berwawasan global dengan tetap berpijak pada nilai-nilai luhur bangsa.',
            'misi' => implode("\n", [
                'Menyelenggarakan pembelajaran aktif, kreatif, dan menyenangkan berbasis teknologi.',
                'Menanamkan nilai religius, integritas, dan kepedulian sosial pada seluruh warga sekolah.',
                'Mengembangkan potensi akademik dan non-akademik peserta didik secara seimbang.',
                'Membangun budaya literasi dan berpikir kritis di lingkungan sekolah.',
                'Menjalin kemitraan dengan orang tua, dunia usaha, dan perguruan tinggi.',
            ]),
            'sejarah' => "SMA Nusantara Jaya berdiri pada tahun 1985 sebagai jawaban atas kebutuhan pendidikan menengah di kawasan Jakarta Timur. Bermula dari tiga ruang kelas sederhana dengan 96 peserta didik, sekolah ini tumbuh berkat dukungan masyarakat sekitar.\n\nMemasuki tahun 2000-an, sekolah melakukan modernisasi besar-besaran: laboratorium komputer, perpustakaan digital, serta laboratorium sains dibangun untuk menunjang kegiatan belajar. Akreditasi A pertama kali diraih pada tahun 2008 dan berhasil dipertahankan hingga kini.\n\nSaat ini SMA Nusantara Jaya menaungi lebih dari seribu peserta didik dengan dua peminatan utama, yakni MIPA dan IPS, serta puluhan kegiatan ekstrakurikuler yang aktif berprestasi di tingkat kota hingga nasional.",
        ]);
    }

    protected function pengguna(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sekolah.test'],
            [
                'name' => 'Administrator Sekolah',
                'password' => 'password',
                'role' => 'admin',
                'telepon' => '081234567890',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staf@sekolah.test'],
            [
                'name' => 'Rina Kurniawati',
                'password' => 'password',
                'role' => 'staf',
                'telepon' => '081298765432',
                'is_active' => true,
            ]
        );
    }

    protected function guru(): void
    {
        $data = [
            ['Dr. Hendra Wijaya, M.Pd.', 'L', 'Bahasa Indonesia', 'Kepala Sekolah'],
            ['Siti Nurhaliza, S.Pd.', 'P', 'Matematika', 'Wakil Kepala Kurikulum'],
            ['Bambang Sutrisno, S.Si.', 'L', 'Fisika', 'Wali Kelas XII MIPA 1'],
            ['Dewi Anggraini, S.Pd.', 'P', 'Biologi', 'Wali Kelas XI MIPA 2'],
            ['Ahmad Fauzi, S.Pd.', 'L', 'Bahasa Inggris', 'Pembina OSIS'],
            ['Maya Puspita, S.Pd.', 'P', 'Kimia', 'Wali Kelas XII MIPA 2'],
            ['Rudi Hartono, S.Kom.', 'L', 'Informatika', 'Koordinator Laboratorium'],
            ['Lestari Handayani, S.Pd.', 'P', 'Sejarah', 'Wali Kelas X-3'],
            ['Joko Prasetyo, S.Pd.', 'L', 'Penjaskes', 'Pembina Ekstrakurikuler'],
            ['Ratna Sari, S.E.', 'P', 'Ekonomi', 'Wali Kelas XI IPS 1'],
        ];

        foreach ($data as $i => [$nama, $jk, $mapel, $jabatan]) {
            Teacher::updateOrCreate(
                ['nip' => '19850'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT).'201001'],
                [
                    'nama' => $nama,
                    'email' => Str::slug(explode(',', $nama)[0], '.').'@smanusantarajaya.sch.id',
                    'telepon' => '0812'.rand(10000000, 99999999),
                    'jenis_kelamin' => $jk,
                    'mata_pelajaran' => $mapel,
                    'jabatan' => $jabatan,
                    'tanggal_bergabung' => now()->subYears(rand(2, 20))->format('Y-m-d'),
                    'alamat' => 'Jakarta Timur',
                    'status' => 'aktif',
                ]
            );
        }
    }

    protected function siswa(): void
    {
        $depan = ['Andi', 'Budi', 'Citra', 'Dian', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indah', 'Joko',
            'Kartika', 'Lukman', 'Mira', 'Nanda', 'Oki', 'Putri', 'Rangga', 'Sinta', 'Tono', 'Umi'];
        $belakang = ['Pratama', 'Santoso', 'Wijaya', 'Lestari', 'Nugroho', 'Maharani', 'Saputra',
            'Anggraini', 'Kusuma', 'Ramadhan'];
        $kelas = ['X-1', 'X-2', 'X-3', 'XI MIPA 1', 'XI MIPA 2', 'XI IPS 1', 'XII MIPA 1', 'XII MIPA 2', 'XII IPS 1'];

        for ($i = 1; $i <= 60; $i++) {
            $nama = $depan[($i - 1) % count($depan)].' '.$belakang[($i * 3) % count($belakang)];
            $kelasTerpilih = $kelas[($i - 1) % count($kelas)];

            Student::updateOrCreate(
                ['nis' => 'S24'.str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'nisn' => '00'.rand(10000000, 99999999),
                    'nama' => $nama,
                    'email' => Str::slug($nama, '.').$i.'@siswa.sch.id',
                    'jenis_kelamin' => $i % 2 === 0 ? 'P' : 'L',
                    'kelas' => $kelasTerpilih,
                    'jurusan' => str_contains($kelasTerpilih, 'IPS') ? 'IPS' : (str_contains($kelasTerpilih, 'MIPA') ? 'MIPA' : 'Umum'),
                    'tanggal_lahir' => now()->subYears(rand(15, 18))->subDays(rand(0, 364))->format('Y-m-d'),
                    'alamat' => 'Jl. Melati No. '.rand(1, 120).', Jakarta Timur',
                    'nama_wali' => 'Bapak/Ibu '.$belakang[($i * 3) % count($belakang)],
                    'telepon_wali' => '0813'.rand(10000000, 99999999),
                    'status' => 'aktif',
                ]
            );
        }
    }

    protected function konten(): void
    {
        $konten = [
            [
                'judul' => 'Penerimaan Peserta Didik Baru Tahun Ajaran '.date('Y').'/'.(date('Y') + 1).' Resmi Dibuka',
                'kategori' => 'pengumuman',
                'ringkasan' => 'Pendaftaran dilakukan sepenuhnya secara daring melalui portal sekolah, mulai hari ini hingga kuota terpenuhi.',
                'konten' => "SMA Nusantara Jaya secara resmi membuka pendaftaran peserta didik baru untuk tahun ajaran mendatang. Seluruh proses pendaftaran dilakukan secara daring melalui portal resmi sekolah sehingga calon peserta didik tidak perlu datang ke sekolah pada tahap awal.\n\nCalon pendaftar cukup mengisi formulir pendaftaran, kemudian mengunggah dokumen persyaratan berupa kartu keluarga, akta kelahiran, ijazah atau surat keterangan lulus, rapor semester terakhir, dan pas foto terbaru.\n\nSetelah formulir dikirim, sistem akan menerbitkan nomor pendaftaran yang dapat digunakan untuk memantau status seleksi kapan saja melalui menu Cek Status. Panitia akan memverifikasi berkas dalam waktu maksimal tiga hari kerja.",
            ],
            [
                'judul' => 'Tim Olimpiade Sains Raih Medali Emas Tingkat Provinsi',
                'kategori' => 'berita',
                'ringkasan' => 'Dua peserta didik kelas XI MIPA berhasil membawa pulang medali emas dan perak pada OSN tingkat provinsi.',
                'konten' => "Prestasi membanggakan kembali ditorehkan peserta didik SMA Nusantara Jaya. Pada ajang Olimpiade Sains Nasional tingkat provinsi yang digelar pekan lalu, tim sekolah berhasil meraih satu medali emas bidang Fisika dan satu medali perak bidang Biologi.\n\nKeberhasilan ini merupakan buah dari pembinaan intensif yang dilakukan sejak awal tahun ajaran, meliputi pendalaman materi, pembahasan soal-soal olimpiade, serta simulasi ujian secara berkala.\n\nKepala sekolah menyampaikan apresiasi kepada seluruh peserta didik dan guru pembina, serta berharap capaian ini memacu semangat berprestasi warga sekolah lainnya.",
            ],
            [
                'judul' => 'Agenda Ujian Tengah Semester Ganjil',
                'kategori' => 'agenda',
                'ringkasan' => 'Ujian tengah semester berlangsung selama satu pekan dengan sistem pelaksanaan berbasis komputer.',
                'konten' => "Ujian Tengah Semester Ganjil akan dilaksanakan selama satu pekan penuh untuk seluruh tingkatan kelas. Pelaksanaan ujian menggunakan sistem berbasis komputer di laboratorium sekolah dengan pembagian sesi pagi dan siang.\n\nPeserta didik diimbau hadir paling lambat lima belas menit sebelum sesi dimulai dengan membawa kartu ujian. Jadwal lengkap per kelas dapat dilihat pada papan pengumuman sekolah maupun melalui wali kelas masing-masing.",
            ],
            [
                'judul' => 'Sekolah Resmikan Laboratorium Komputer Baru',
                'kategori' => 'berita',
                'ringkasan' => 'Laboratorium berkapasitas 40 unit komputer ini menunjang pembelajaran Informatika dan pelaksanaan ujian daring.',
                'konten' => "Sebagai bagian dari peningkatan mutu sarana pembelajaran, SMA Nusantara Jaya meresmikan laboratorium komputer baru berkapasitas empat puluh unit. Fasilitas ini dilengkapi jaringan internet berkecepatan tinggi serta perangkat lunak pembelajaran terkini.\n\nSelain menunjang mata pelajaran Informatika, laboratorium ini juga akan dimanfaatkan untuk pelaksanaan ujian berbasis komputer serta kegiatan ekstrakurikuler robotika dan desain grafis.",
            ],
            [
                'judul' => 'Pekan Literasi dan Bazar Buku Sekolah',
                'kategori' => 'agenda',
                'ringkasan' => 'Rangkaian kegiatan meliputi lomba resensi, bedah buku, serta bazar buku dengan harga khusus pelajar.',
                'konten' => "Dalam rangka memperkuat budaya literasi, sekolah menggelar Pekan Literasi yang diisi beragam kegiatan menarik. Rangkaian acara mencakup lomba resensi buku antarkelas, bedah buku bersama penulis tamu, serta pojok baca terbuka di area taman sekolah.\n\nBersamaan dengan itu, digelar pula bazar buku yang menawarkan koleksi fiksi maupun nonfiksi dengan harga khusus pelajar. Seluruh warga sekolah dan orang tua peserta didik diundang untuk hadir dan berpartisipasi.",
            ],
            [
                'judul' => 'Jadwal Pembagian Rapor Semester',
                'kategori' => 'pengumuman',
                'ringkasan' => 'Pembagian rapor dilakukan secara tatap muka dan wajib dihadiri oleh orang tua atau wali peserta didik.',
                'konten' => "Pembagian rapor semester akan dilaksanakan secara tatap muka di ruang kelas masing-masing. Orang tua atau wali peserta didik diharapkan hadir tepat waktu sesuai jadwal yang telah dibagikan oleh wali kelas.\n\nPada kesempatan tersebut, wali kelas juga akan menyampaikan perkembangan akademik dan sikap peserta didik selama satu semester, sekaligus membuka sesi konsultasi bagi orang tua yang memerlukan.",
            ],
        ];

        // Warna sampul per kategori agar kartu berita tampil konsisten.
        $warnaKategori = [
            'pengumuman' => [42, 77, 159],
            'berita' => [29, 50, 102],
            'agenda' => [13, 148, 136],
        ];

        foreach ($konten as $i => $item) {
            $path = 'konten/contoh-'.($i + 1).'.png';

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put(
                    $path,
                    $this->gambarPlaceholder($item['judul'], $warnaKategori[$item['kategori']])
                );
            }

            Announcement::updateOrCreate(
                ['slug' => Str::slug($item['judul'])],
                $item + [
                    'gambar' => $path,
                    'is_published' => true,
                    'published_at' => now()->subDays($i * 4 + 1),
                ]
            );
        }
    }

    protected function galeri(): void
    {
        $foto = [
            ['Upacara Bendera Senin', 'kegiatan', [79, 70, 229]],
            ['Kegiatan Belajar di Kelas', 'kegiatan', [37, 99, 235]],
            ['Laboratorium Komputer', 'fasilitas', [13, 148, 136]],
            ['Perpustakaan Sekolah', 'fasilitas', [5, 150, 105]],
            ['Lapangan Olahraga', 'fasilitas', [217, 119, 6]],
            ['Juara Olimpiade Sains Provinsi', 'prestasi', [219, 39, 119]],
            ['Juara Umum Lomba Debat', 'prestasi', [190, 24, 93]],
            ['Ekstrakurikuler Paskibra', 'ekstrakurikuler', [124, 58, 237]],
            ['Ekstrakurikuler Robotika', 'ekstrakurikuler', [8, 145, 178]],
            ['Pentas Seni Akhir Tahun', 'kegiatan', [225, 29, 72]],
        ];

        foreach ($foto as $i => [$judul, $kategori, $warna]) {
            $path = 'galeri/contoh-'.($i + 1).'.png';

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $this->gambarPlaceholder($judul, $warna));
            }

            Gallery::updateOrCreate(
                ['judul' => $judul],
                [
                    'kategori' => $kategori,
                    'deskripsi' => 'Dokumentasi '.strtolower($judul).' di lingkungan sekolah.',
                    'path' => $path,
                ]
            );
        }
    }

    /** Membuat gambar contoh sederhana agar galeri tidak kosong saat demo. */
    protected function gambarPlaceholder(string $teks, array $warna): string
    {
        $lebar = 800;
        $tinggi = 600;
        $img = imagecreatetruecolor($lebar, $tinggi);

        [$r, $g, $b] = $warna;
        for ($y = 0; $y < $tinggi; $y++) {
            $rasio = $y / $tinggi;
            $garis = imagecolorallocate(
                $img,
                (int) ($r + (255 - $r) * $rasio * 0.45),
                (int) ($g + (255 - $g) * $rasio * 0.45),
                (int) ($b + (255 - $b) * $rasio * 0.45),
            );
            imageline($img, 0, $y, $lebar, $y, $garis);
        }

        $putih = imagecolorallocate($img, 255, 255, 255);
        foreach (str_split($teks, 24) as $i => $potongan) {
            imagestring($img, 5, 40, (int) ($tinggi / 2 - 20 + $i * 22), $potongan, $putih);
        }

        ob_start();
        imagepng($img);
        imagedestroy($img);

        return (string) ob_get_clean();
    }

    protected function pendaftaran(): void
    {
        $pendaftar = [
            ['Alya Ramadhani', 'P', 'SMP Negeri 12 Jakarta', 'MIPA', 'diterima'],
            ['Bagas Setiawan', 'L', 'SMP Negeri 5 Jakarta', 'MIPA', 'diverifikasi'],
            ['Cindy Permata', 'P', 'SMP Islam Al-Azhar', 'IPS', 'menunggu'],
            ['Doni Firmansyah', 'L', 'SMP Negeri 20 Jakarta', 'MIPA', 'menunggu'],
            ['Elsa Maharani', 'P', 'SMP Tunas Bangsa', 'IPS', 'diterima'],
            ['Farhan Aditya', 'L', 'SMP Negeri 8 Jakarta', 'MIPA', 'ditolak'],
            ['Gina Salsabila', 'P', 'SMP Harapan Jaya', 'IPS', 'menunggu'],
            ['Hafiz Nurrahman', 'L', 'SMP Negeri 15 Jakarta', 'MIPA', 'diverifikasi'],
        ];

        foreach ($pendaftar as $i => [$nama, $jk, $asal, $jurusan, $status]) {
            Registration::updateOrCreate(
                ['nomor_pendaftaran' => 'PSB-'.date('Y').'-'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'nama_lengkap' => $nama,
                    'nisn' => '00'.rand(10000000, 99999999),
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => now()->subYears(15)->subDays(rand(0, 364))->format('Y-m-d'),
                    'asal_sekolah' => $asal,
                    'jurusan_pilihan' => $jurusan,
                    'email' => Str::slug($nama, '.').'@email.test',
                    'telepon' => '0857'.rand(10000000, 99999999),
                    'alamat' => 'Jl. Kenanga No. '.rand(1, 90).', Jakarta Timur',
                    'nama_wali' => 'Orang Tua '.explode(' ', $nama)[0],
                    'telepon_wali' => '0821'.rand(10000000, 99999999),
                    'status' => $status,
                    'catatan' => match ($status) {
                        'diterima' => 'Selamat! Berkas Anda lengkap dan Anda dinyatakan diterima. Silakan menunggu informasi daftar ulang.',
                        'ditolak' => 'Mohon maaf, kuota jurusan pilihan Anda telah terpenuhi.',
                        'diverifikasi' => 'Berkas Anda sudah kami terima dan sedang dalam proses seleksi.',
                        default => null,
                    },
                ]
            );
        }
    }
}
