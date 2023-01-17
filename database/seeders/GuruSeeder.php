<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 401,
                'user_id' => 100,
                'nip' => '20023213',
                'gelar' => 'S.Pd',
                'tanggal_bergabung' => '2022-02-03',

            ]
        ];
        DB::table('gurus')->insert($data);
    }
}
