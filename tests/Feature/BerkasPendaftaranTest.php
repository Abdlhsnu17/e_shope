<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Berkas pendaftaran memuat kartu keluarga, akta kelahiran, dan rapor. Test di
 * sini mengunci syaratnya: nomor pendaftaran yang berurutan tidak boleh cukup
 * untuk membuka, mengubah, atau menghapus berkas milik orang lain.
 */
class BerkasPendaftaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_orang_lain_tidak_bisa_membuka_halaman_berkas(): void
    {
        $pendaftaran = Registration::factory()->create();

        $this->get(route('pendaftaran.dokumen', $pendaftaran->nomor_pendaftaran))
            ->assertRedirect(route('pendaftaran.status'));
    }

    public function test_orang_lain_tidak_bisa_mengunggah_ke_pendaftaran_milik_orang(): void
    {
        $pendaftaran = Registration::factory()->create();

        $this->post(route('pendaftaran.dokumen.unggah', $pendaftaran->nomor_pendaftaran), [
            'jenis' => 'Kartu Keluarga',
            'berkas' => UploadedFile::fake()->create('kk.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('pendaftaran.status'));

        $this->assertSame(0, $pendaftaran->documents()->count());
    }

    public function test_orang_lain_tidak_bisa_menghapus_dokumen(): void
    {
        $pendaftaran = Registration::factory()->create();
        $doc = $pendaftaran->documents()->create([
            'jenis' => 'Rapor', 'nama_file' => 'rapor.pdf', 'path' => 'dokumen/1/rapor.pdf', 'ukuran' => 1024,
        ]);

        $this->delete(route('pendaftaran.dokumen.hapus', [$pendaftaran->nomor_pendaftaran, $doc->id]))
            ->assertRedirect(route('pendaftaran.status'));

        $this->assertModelExists($doc);
    }

    public function test_orang_lain_tidak_bisa_mengunduh_dokumen(): void
    {
        $pendaftaran = Registration::factory()->create();
        $doc = $pendaftaran->documents()->create([
            'jenis' => 'Akta Kelahiran', 'nama_file' => 'akta.pdf', 'path' => 'dokumen/1/akta.pdf', 'ukuran' => 1024,
        ]);

        $this->get(route('pendaftaran.dokumen.unduh', [$pendaftaran->nomor_pendaftaran, $doc->id]))
            ->assertRedirect(route('pendaftaran.status'));
    }

    public function test_pengisi_formulir_langsung_boleh_mengurus_berkasnya(): void
    {
        $response = $this->post(route('pendaftaran.store'), $this->dataFormulir());

        $pendaftaran = Registration::firstOrFail();
        $response->assertRedirect(route('pendaftaran.dokumen', $pendaftaran->nomor_pendaftaran));

        $this->get(route('pendaftaran.dokumen', $pendaftaran->nomor_pendaftaran))->assertOk();

        $this->post(route('pendaftaran.dokumen.unggah', $pendaftaran->nomor_pendaftaran), [
            'jenis' => 'Kartu Keluarga',
            'berkas' => UploadedFile::fake()->create('kk.pdf', 100, 'application/pdf'),
        ])->assertSessionHasNoErrors();

        $doc = $pendaftaran->documents()->firstOrFail();

        // Berkas harus mendarat di disk privat, bukan di jalur yang tersaji publik.
        Storage::disk('local')->assertExists($doc->path);
        Storage::disk('public')->assertMissing($doc->path);

        // Halaman berkas menautkan rute unduh yang dijaga, bukan URL /storage.
        $this->get(route('pendaftaran.dokumen', $pendaftaran->nomor_pendaftaran))
            ->assertSee(route('pendaftaran.dokumen.unduh', [$pendaftaran->nomor_pendaftaran, $doc->id]), false)
            ->assertDontSee('/storage/'.$doc->path);
    }

    public function test_cek_status_dengan_nomor_dan_email_yang_cocok_membuka_akses_berkas(): void
    {
        $pendaftaran = Registration::factory()->create(['email' => 'wali@contoh.test']);

        $this->post(route('pendaftaran.status'), [
            'nomor' => $pendaftaran->nomor_pendaftaran,
            'email' => 'wali@contoh.test',
        ])->assertOk();

        $this->get(route('pendaftaran.dokumen', $pendaftaran->nomor_pendaftaran))->assertOk();
    }

    public function test_cek_status_dengan_email_yang_salah_tidak_membuka_akses(): void
    {
        $pendaftaran = Registration::factory()->create(['email' => 'wali@contoh.test']);

        $this->post(route('pendaftaran.status'), [
            'nomor' => $pendaftaran->nomor_pendaftaran,
            'email' => 'penebak@contoh.test',
        ])->assertSessionHasErrors('nomor');

        $this->get(route('pendaftaran.dokumen', $pendaftaran->nomor_pendaftaran))
            ->assertRedirect(route('pendaftaran.status'));
    }

    public function test_unggahan_dibatasi_agar_penyimpanan_tidak_dibanjiri(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataFormulir());
        $pendaftaran = Registration::firstOrFail();

        for ($i = 0; $i < Registration::MAKS_DOKUMEN; $i++) {
            $pendaftaran->documents()->create([
                'jenis' => 'Lainnya', 'nama_file' => "berkas-{$i}.pdf",
                'path' => "dokumen/{$pendaftaran->id}/berkas-{$i}.pdf", 'ukuran' => 1024,
            ]);
        }

        $this->post(route('pendaftaran.dokumen.unggah', $pendaftaran->nomor_pendaftaran), [
            'jenis' => 'Lainnya',
            'berkas' => UploadedFile::fake()->create('lagi.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('berkas');

        $this->assertSame(Registration::MAKS_DOKUMEN, $pendaftaran->documents()->count());
    }

    public function test_petugas_bisa_mengunduh_dokumen_pendaftar(): void
    {
        $pendaftaran = Registration::factory()->create();
        Storage::disk('local')->put("dokumen/{$pendaftaran->id}/kk.pdf", 'isi berkas');
        $doc = $pendaftaran->documents()->create([
            'jenis' => 'Kartu Keluarga', 'nama_file' => 'kk.pdf',
            'path' => "dokumen/{$pendaftaran->id}/kk.pdf", 'ukuran' => 10,
        ]);

        $petugas = User::factory()->create(['role' => 'staf']);

        $this->actingAs($petugas)
            ->get(route('admin.registrations.show', $pendaftaran))
            ->assertOk()
            ->assertSee(route('admin.registrations.dokumen', [$pendaftaran, $doc->id]), false);

        $this->actingAs($petugas)
            ->get(route('admin.registrations.dokumen', [$pendaftaran, $doc->id]))
            ->assertOk();
    }

    public function test_tamu_tidak_bisa_mengunduh_dokumen_lewat_rute_admin(): void
    {
        $pendaftaran = Registration::factory()->create();
        $doc = $pendaftaran->documents()->create([
            'jenis' => 'Rapor', 'nama_file' => 'rapor.pdf',
            'path' => "dokumen/{$pendaftaran->id}/rapor.pdf", 'ukuran' => 10,
        ]);

        $this->get(route('admin.registrations.dokumen', [$pendaftaran, $doc->id]))
            ->assertRedirect(route('login'));
    }

    /** @return array<string, string> */
    protected function dataFormulir(): array
    {
        return [
            'nama_lengkap' => 'Ananda Putri',
            'nisn' => '1234567890',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-17',
            'asal_sekolah' => 'SMP Negeri 1',
            'jurusan_pilihan' => 'IPA',
            'email' => 'ananda@contoh.test',
            'telepon' => '081234567890',
            'alamat' => 'Jalan Merdeka 10',
            'nama_wali' => 'Bapak Putra',
            'telepon_wali' => '081234567891',
        ];
    }
}
