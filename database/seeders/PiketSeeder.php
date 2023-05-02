<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class PiketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'username' => 'piketpiket',
            'name' => 'Piket Piket',
            'no_induk' => '01891211212',
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
            'email' => 'dfdfadfsd@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('piketpiket'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_user')->insert([
            'role_id' => 4,
            'user_id' => $admin->id,
        ]);
    }
}
