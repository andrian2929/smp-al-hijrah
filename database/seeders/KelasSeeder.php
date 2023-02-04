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
            ['id' => 1, 'jenjang' => 'VII', 'section' => 'A'],
            ['id' => 2, 'jenjang' => 'VII', 'section' => 'B'],
            ['id' => 3, 'jenjang' => 'VII', 'section' => 'C'],
            ['id' => 4, 'jenjang' => 'VIII', 'section' => 'A'],
            ['id' => 5, 'jenjang' => 'VIII', 'section' => 'B'],
            ['id' => 6, 'jenjang' => 'VIII', 'section' => 'C'],
            ['id' => 7, 'jenjang' => 'IX', 'section' => 'A'],
            ['id' => 8, 'jenjang' => 'IX', 'section' => 'B'],
            ['id' => 9, 'jenjang' => 'IX', 'section' => 'C'],
        ];
        DB::table('kelas')->insert($data);
    }
}
