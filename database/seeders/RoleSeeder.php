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
            ['id' => 1, 'name' => 'Administrator', 'display_name' => 'admin'],
            ['id' => 2, 'name' => 'Guru', 'display_name' => 'guru'],
            ['id' => 3, 'name' => 'Siswa', 'display_name' => 'siswa']
        ];
        DB::table('roles')->insert($data);
    }
}
