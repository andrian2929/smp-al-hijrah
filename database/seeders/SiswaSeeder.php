<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('siswas')->truncate();

        $siswa_data = [
            [
                'kelas_id' => 1,
                'user_id' => 300,
                'nisn' => '2124320',
                'is_beasiswa' => 0,
                'asal_sekolah' => 'SMA Negeri 1 Medan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelas_id' => 2,
                'user_id' => 400,
                'nisn' => '2124329',
                'is_beasiswa' => 0,
                'asal_sekolah' => 'SMA Negeri 2 Medan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('siswas')->insert($siswa_data);
    }
}
