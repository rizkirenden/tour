<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaskapaiTipeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('maskapai_tipe_penerbangans')->insert([
            // Garuda Indonesia (id: 1) - Internasional & Domestik
            ['id_maskapai' => 1, 'tipe_penerbangan' => 'Internasional', 'created_at' => now(), 'updated_at' => now()],
            ['id_maskapai' => 1, 'tipe_penerbangan' => 'Domestik', 'created_at' => now(), 'updated_at' => now()],

            // Saudi Arabian Airlines (id: 2) - Internasional
            ['id_maskapai' => 2, 'tipe_penerbangan' => 'Internasional', 'created_at' => now(), 'updated_at' => now()],

            // Emirates (id: 3) - Internasional
            ['id_maskapai' => 3, 'tipe_penerbangan' => 'Internasional', 'created_at' => now(), 'updated_at' => now()],

            // Lion Air (id: 4) - Domestik
            ['id_maskapai' => 4, 'tipe_penerbangan' => 'Domestik', 'created_at' => now(), 'updated_at' => now()],

            // Citilink (id: 5) - Domestik
            ['id_maskapai' => 5, 'tipe_penerbangan' => 'Domestik', 'created_at' => now(), 'updated_at' => now()],

            // Batik Air (id: 6) - Domestik
            ['id_maskapai' => 6, 'tipe_penerbangan' => 'Domestik', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
