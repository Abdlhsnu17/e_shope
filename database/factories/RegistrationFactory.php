<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_pendaftaran' => 'PSB-'.date('Y').'-'.fake()->unique()->numerify('####'),
            'nama_lengkap' => fake()->name(),
            'nisn' => fake()->unique()->numerify('##########'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-18 years', '-14 years'),
            'asal_sekolah' => 'SMP '.fake()->city(),
            'jurusan_pilihan' => fake()->randomElement(['IPA', 'IPS']),
            'email' => fake()->unique()->safeEmail(),
            'telepon' => fake()->numerify('08##########'),
            'alamat' => fake()->address(),
            'nama_wali' => fake()->name(),
            'telepon_wali' => fake()->numerify('08##########'),
            'status' => 'menunggu',
        ];
    }
}
