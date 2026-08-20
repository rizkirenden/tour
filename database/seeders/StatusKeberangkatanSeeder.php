<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusKeberangkatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_keberangkatans')->insert([
            [
                'id_status' => 1,
                'nama_status' => 'Siap Berangkat',
                'warna' => '#10B981', // Hijau
                'keterangan' => 'Jamaah sudah siap untuk keberangkatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_status' => 2,
                'nama_status' => 'Dalam Perjalanan',
                'warna' => '#F59E0B', // Kuning
                'keterangan' => 'Jamaah sedang dalam perjalanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}