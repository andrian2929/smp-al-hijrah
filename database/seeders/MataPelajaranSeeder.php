<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Pendidikan Agama Islam', 'jenjang' => 'VII'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'jenjang' => 'VII'],
            ['name' => 'Bahasa Indonesia', 'jenjang' => 'VII'],
            ['name' => 'Matematika', 'jenjang' => 'VII'],
            ['name' => 'Ilmu Pengetahuan Alam', 'jenjang' => 'VII'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'jenjang' => 'VII'],
            ['name' => 'Bahasa Inggris', 'jenjang' => 'VII'],
            ['name' => 'Seni Budaya', 'jenjang' => 'VII'],
            ['name' => 'Pendidikan Jasmani', 'jenjang' => 'VII']
        ];

        DB::table('mata_pelajarans')->insert($data);
    }
}
