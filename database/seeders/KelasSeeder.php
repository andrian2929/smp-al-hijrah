<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['jenjang' => 'VII', 'section' => 'A'],
            ['jenjang' => 'VII', 'section' => 'B'],
            ['jenjang' => 'VII', 'section' => 'C'],
            ['jenjang' => 'VIII', 'section' => 'A'],
            ['jenjang' => 'VIII', 'section' => 'B'],
            ['jenjang' => 'VIII', 'section' => 'C'],
            ['jenjang' => 'IX', 'section' => 'A'],
            ['jenjang' => 'IX', 'section' => 'B'],
            ['jenjang' => 'IX', 'section' => 'C'],
        ];
        DB::table('kelas')->insert($data);
    }
}
