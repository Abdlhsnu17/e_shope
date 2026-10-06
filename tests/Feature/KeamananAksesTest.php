<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeamananAksesTest extends TestCase
{
    use RefreshDatabase;

    public function test_percobaan_login_dibatasi(): void
    {
        $user = User::factory()->create(['email' => 'admin@sekolah.test']);

        // Lima percobaan pertama masih dilayani (dan gagal karena sandi salah);
        // percobaan keenam harus ditolak pembatas laju.
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
                ->assertSessionHasErrors('email');
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertStatus(429);
    }

    public function test_pembatas_login_tidak_menghalangi_akun_lain(): void
    {
        User::factory()->create(['email' => 'korban@sekolah.test']);
        $lain = User::factory()->create(['email' => 'petugas@sekolah.test']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'korban@sekolah.test', 'password' => 'salah']);
        }

        $this->post('/login', ['email' => $lain->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_penyisiran_cek_status_dibatasi(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('pendaftaran.status'), [
                'nomor' => 'PSB-'.date('Y').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'email' => 'penebak@contoh.test',
            ])->assertRedirect();
        }

        $this->post(route('pendaftaran.status'), [
            'nomor' => 'PSB-'.date('Y').'-9999',
            'email' => 'penebak@contoh.test',
        ])->assertStatus(429);
    }

    public function test_pengiriman_formulir_pendaftaran_dibatasi(): void
    {
        $kirim = fn () => $this->post(route('pendaftaran.store'), [
            'nama_lengkap' => 'Pendaftar Massal',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
            'asal_sekolah' => 'SMP Negeri 2',
            'jurusan_pilihan' => 'IPA',
            'email' => 'massal@contoh.test',
            'telepon' => '081234567890',
            'alamat' => 'Jalan Mawar 1',
            'nama_wali' => 'Wali',
            'telepon_wali' => '081234567891',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $kirim()->assertRedirect();
        }

        $kirim()->assertStatus(429);
    }

    public function test_sesi_akun_yang_dinonaktifkan_langsung_ditutup(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk();

        $user->update(['is_active' => false]);

        $this->actingAs($user->fresh())->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_akun_nonaktif_tidak_bisa_login(): void
    {
        $user = User::factory()->nonaktif()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_hanya_admin_boleh_mengelola_pengguna(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'staf']))
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'guru']))
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    public function test_panel_admin_tertutup_untuk_tamu(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.registrations.index'))->assertRedirect(route('login'));
        $this->get(route('admin.students.index'))->assertRedirect(route('login'));
    }
}
