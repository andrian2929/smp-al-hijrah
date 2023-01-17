<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username'=>$this->faker->userName(), 
            'name'=>$this->faker->name(), 
            'no_induk'=>$this->faker->numerify("############"),  
            'no_telp'=>$this->faker->phoneNumber(), 
            'alamat'=>$this->faker->address(),
            'tanggal_lahir'=>$this->faker->date(),
            'gol_darah'=>$this->faker->randomElement(['A','B','O','AB']), 
            'tinggi'=>$this->faker->numberBetween($min = 100, $max = 200),
            'berat'=>$this->faker->numberBetween($min = 30, $max = 100),
            'email_verified_at'=>now(), 
            'password'=>$this->faker->password(), 
            'remember_token'=>Str::random(10),             
            // 'name' => $this->faker->name(),
            // 'email' => $this->faker->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // 'remember_token' => Str::random(10),
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
