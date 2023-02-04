<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListPerilakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Menjadi Imam/Muadzin', 'type' => 'ibadah', 'is_terpuji' => 1],
            ['name' => 'Infaq Terbaik Jumat', 'type' => 'ibadah', 'is_terpuji' => 1],
            ['name' => 'Berbagi makanan dan perlengkapan', 'type' => 'akhlak', 'is_terpuji' => 1],
            ['name' => 'Mengutip dan membuang Sampah pada tempatnya', 'type' => 'akhlak', 'is_terpuji' => 1],
            ['name' => 'Merapikan Sandal dan sepatu', 'type' => 'akhlak', 'is_terpuji' => 1],
            ['name' => 'Membawa Jimat (Benda yang dianggap bisa menolong)', 'type' => 'akidah', 'is_terpuji' => 0],
            ['name' => 'Membawa Buku-buku Zodiak', 'type' => 'akidah', 'is_terpuji' => 0],
            ['name' => "Telat sholat berjama'ah", 'type' => 'ibadah', 'is_terpuji' => 0],
            ['name' => "Tidak menjaga adab dalam masjid (doa dan Dzikir)", 'type' => 'ibadah', 'is_terpuji' => 0],
            ['name' => "Berkelahi", 'type' => 'akhlak', 'is_terpuji' => 0],
            ['name' => "Mencuri", 'type' => 'akhlak', 'is_terpuji' => 0],
            ['name' => "berdusta", 'type' => 'akhlak', 'is_terpuji' => 0],
            ['name' => "Melakukan perbuatan asusila", 'type' => 'akhlak', 'is_terpuji' => 0],
            ['name' => "Merokok dan Narkoba", 'type' => 'akhlak', 'is_terpuji' => 0],
        ];

        DB::table('list_perilakus')->insert($data);
    }
}
