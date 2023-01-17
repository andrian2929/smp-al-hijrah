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
            ['name' => 'PAI', 'jenjang' => 'VII'],
            ['name' => 'PPKN', 'jenjang' => 'VII'],
            ['name' => 'BAHASA INDONESIA', 'jenjang' => 'VII'],
            ['name' => 'MATEMATIKA', 'jenjang' => 'VII'],
            ['name' => 'IPA', 'jenjang' => 'VII'],
            ['name' => 'IPS', 'jenjang' => 'VII'],
            ['name' => 'BAHASA INGGRIS', 'jenjang' => 'VII'],
            ['name' => 'SENI BUDAYA', 'jenjang' => 'VII'],
            ['name' => 'PENJAS', 'jenjang' => 'VII']
        ];

        DB::table('mata_pelajarans')->insert($data);
    }
}
