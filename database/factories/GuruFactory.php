<?php

namespace Database\Factories;


use App\Models\Guru;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;


class GuruFactory extends Factory
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
            'nip' => $faker->numberBetween(1000000000, 9999999999),
            'gelar' => $faker->randomElement(['S.Pd', 'S.Kom', 'S.Pdi', 'S.Ag']),
            'tanggal_bergabung' => $faker->date('Y-m-d'),
        ];
    }
}
