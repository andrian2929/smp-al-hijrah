<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('users')->insert([
            'id' => 100,
            'username' => 'andrianramadan',
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
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 200,
            'username' => 'admin',
            'name' => 'Adminto',
            'no_induk' => '23232332',
            'image' => null,
            'no_telp' => '08537622323',
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
            'email' => 'adminto@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 300,
            'username' => 'siswa',
            'name' => 'Siswanto',
            'no_induk' => '300435',
            'image' => null,
            'no_telp' => '08537622323',
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
            'email' => 'siswanto@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        DB::table('users')->insert([
            'id' => 400,
            'username' => 'siswa2',
            'name' => 'Siswanto',
            'no_induk' => '3004212135',
            'image' => null,
            'no_telp' => '08537622323',
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
            'email' => 'siswanto2@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
