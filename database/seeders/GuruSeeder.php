<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use App\Models\User;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $user =  User::factory()->create();
            $guru = Guru::factory()->create([
                'user_id' => $user->id,
            ]);

            DB::table('role_user')->insert([
                'role_id' => 2,
                'user_id' => $user->id,
            ]);
        }
    }
}
