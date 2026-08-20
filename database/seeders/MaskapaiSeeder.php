<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaskapaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('maskapais')->insert([
            [
                'id_maskapai' => 1,
                'kode_maskapai' => 'GA',
                'nama_maskapai' => 'Garuda Indonesia',
                'tipe_penerbangan' => 'Internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_maskapai' => 2,
                'kode_maskapai' => 'SV',
                'nama_maskapai' => 'Saudi Arabian Airlines',
                'tipe_penerbangan' => 'Internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}