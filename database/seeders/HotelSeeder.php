<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hotels')->insert([
            [
                'id_hotel' => 1,
                'kode_hotel' => 'HOT-001',
                'nama_hotel' => 'Hotel Al-Haram Mekkah',
                'lokasi' => 'Dekat Masjidil Haram',
                'tipe_hotel' => 'Bintang 5',
                'bintang' => 5,
                'tipe_kamar' => 'Deluxe',
                'harga_per_malam' => 2500000,
                'kapasitas' => 4,
                'negara' => 'Arab Saudi',
                'kota' => 'Mekkah',
                'fasilitas' => 'AC, TV, WiFi, Kolam Renang, Restoran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_hotel' => 2,
                'kode_hotel' => 'HOT-002',
                'nama_hotel' => 'Hotel Nabawi Madinah',
                'lokasi' => 'Dekat Masjid Nabawi',
                'tipe_hotel' => 'Bintang 4',
                'bintang' => 4,
                'tipe_kamar' => 'Superior',
                'harga_per_malam' => 1800000,
                'kapasitas' => 4,
                'negara' => 'Arab Saudi',
                'kota' => 'Madinah',
                'fasilitas' => 'AC, TV, WiFi, Restoran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}