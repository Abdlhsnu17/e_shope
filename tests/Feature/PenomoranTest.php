<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenomoranTest extends TestCase
{
    use RefreshDatabase;

    public function test_nomor_pendaftaran_berurut_dan_tidak_berulang(): void
    {
        $nomor = collect(range(1, 5))->map(fn () => Registration::daftarkan([
            'nama_lengkap' => 'Pendaftar',
            'jenis_kelamin' => 'L',
            'email' => uniqid().'@contoh.test',
        ])->nomor_pendaftaran);

        $this->assertSame($nomor->unique()->count(), $nomor->count());
        $this->assertSame('PSB-'.date('Y').'-0001', $nomor->first());
        $this->assertSame('PSB-'.date('Y').'-0005', $nomor->last());
    }

    /**
     * Penomoran lama memakai jumlah baris, sehingga begitu ada satu siswa dihapus
     * nomor berikutnya menabrak NIS yang masih dipakai siswa lain — dan pembuatan
     * siswa gagal karena unique index. Sekarang nomor diturunkan dari NIS
     * tertinggi, jadi berapa pun baris yang terhapus, nomor baru tetap bebas.
     */
    public function test_nis_baru_tidak_menabrak_nis_yang_masih_terpakai(): void
    {
        $siswa = collect(['A', 'B', 'C'])->map(fn ($nama) => Student::create([
            'nis' => Student::generateNis(),
            'nama' => 'Siswa '.$nama,
        ]));

        $siswa[1]->delete();

        $baru = Student::generateNis();

        $this->assertSame('S'.date('y').'0004', $baru);
        $this->assertFalse(Student::where('nis', $baru)->exists());
    }

    public function test_pendaftar_yang_diterima_menjadi_siswa_dengan_nis_baru(): void
    {
        $pendaftaran = Registration::factory()->create(['status' => 'diterima']);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.registrations.terima', $pendaftaran))
            ->assertRedirect(route('admin.students.index'));

        $siswa = Student::firstOrFail();
        $this->assertSame($pendaftaran->nama_lengkap, $siswa->nama);
        $this->assertSame('S'.date('y').'0001', $siswa->nis);
    }

    public function test_pendaftar_yang_belum_diterima_tidak_bisa_dipromosikan(): void
    {
        $pendaftaran = Registration::factory()->create(['status' => 'menunggu']);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.registrations.terima', $pendaftaran))
            ->assertSessionHasErrors('status');

        $this->assertSame(0, Student::count());
    }
}
