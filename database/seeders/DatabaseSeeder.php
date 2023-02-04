<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('migrate:fresh');

        $this->call([
            RoleSeeder::class,
            KelasSeeder::class,
            AdminSeeder::class,
            SiswaSeeder::class,
            GuruSeeder::class,
            RoleUserSeeder::class,
            MataPelajaranSeeder::class,
            ListPerilakuSeeder::class,




        ]);
    }
}
