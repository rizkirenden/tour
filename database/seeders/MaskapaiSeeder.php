<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaskapaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('maskapais')->insert([
            ['nama_maskapai' => 'Garuda Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['nama_maskapai' => 'Saudi Arabian Airlines', 'created_at' => now(), 'updated_at' => now()],
            ['nama_maskapai' => 'Emirates', 'created_at' => now(), 'updated_at' => now()],
            ['nama_maskapai' => 'Lion Air', 'created_at' => now(), 'updated_at' => now()],
            ['nama_maskapai' => 'Citilink', 'created_at' => now(), 'updated_at' => now()],
            ['nama_maskapai' => 'Batik Air', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
