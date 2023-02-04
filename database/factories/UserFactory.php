<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
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
            'username' => $faker->userName,
            'name' => $faker->name,
            'no_induk' => $faker->randomNumber(7),
            'image' => 'default.png',
            'no_telp' => $faker->phoneNumber,
            'alamat' => $faker->address,
            'kelurahan' => $faker->city,
            'kecamatan' => $faker->city,
            'kota' => $faker->city,
            'provinsi' => $faker->state,
            'gol_darah' => $faker->randomElement(['A', 'B', 'AB', 'O']),
            'tinggi' => $faker->randomNumber(3),
            'berat' => $faker->randomNumber(2),
            'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d'),
            'is_active' => 1,
            'email' => $faker->email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
