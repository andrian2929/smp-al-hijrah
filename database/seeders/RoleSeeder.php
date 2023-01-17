<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Administrator', 'display_name' => 'admin'],
            ['name' => 'Guru', 'display_name' => 'guru'],
            ['name' => 'Siswa', 'display_name' => 'siswa']
        ];
        DB::table('roles')->insert($data);
    }
}
