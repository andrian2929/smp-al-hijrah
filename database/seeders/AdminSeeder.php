<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::factory()->create([
            'username' => 'admin',
            'name' => 'Andrian Ramadan',
            'no_induk' => '2124329',
            'image' => null,
            'no_telp' => '081261447311',
            'alamat' => 'Medan',
            'kelurahan' => 'Tanah Enam Ratus',
            'kecamatan' => 'Medan Marelan',
            'kota' => 'Medan',
            'provinsi' => 'Sumatera Utara',
            'gol_darah' => 'A',
            'tinggi' => '170',
            'berat' => '60',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Medan',
            'tanggal_lahir' => '2000-12-12',
            'is_active' => 1,
            'email' => 'ramadanandrian89@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $how = DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => $admin->id,
        ]);

        // DB::table('role_user')->insert([
        //     'role_id' => 2,
        //     'user_id' => $admin->id,
        // ]);
    }
}
