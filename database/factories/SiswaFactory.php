<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create('id_ID');
        return [
            'kelas_id' => $faker->numberBetween(1, 9),
            'nisn' => $faker->numberBetween(1000000000, 9999999999),
            'is_beasiswa' => $faker->boolean(),
            'asal_sekolah' => $faker->randomElement(['SMA Negeri 1 Medan', 'SMA Negeri 2 Medan', 'SMA Negeri 3 Medan', 'SMA Negeri 4 Medan', 'SMA Negeri 5 Medan']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
