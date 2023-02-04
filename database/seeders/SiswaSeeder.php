<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Siswa;
use Faker\Factory as Faker;



class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 1; $i <= 100; $i++) {
            $user =  User::factory()->create();
            $siswa = Siswa::factory()->create([
                'user_id' => $user->id,
            ]);

            DB::table('role_user')->insert([
                'role_id' => 3,
                'user_id' => $user->id,
            ]);
        }
    }
}
