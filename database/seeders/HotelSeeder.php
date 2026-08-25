<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Hotel;
use App\Models\Kamar;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('kamar_jamaahs')->truncate();
        DB::table('kamars')->truncate();
        DB::table('hotels')->truncate();

        Schema::enableForeignKeyConstraints();

        // Hotel 1 - Mekkah
        $hotel1 = Hotel::create([
            'nama_hotel' => 'Hotel Al-Haram Mekkah',
            'lokasi' => 'Dekat Masjidil Haram',
            'tipe_hotel' => 'Luxury',
            'bintang' => 5,
            'negara' => 'Arab Saudi',
            'kota' => 'Mekkah',
            'fasilitas' => 'AC, TV, WiFi, Kolam Renang, Restoran, Spa',
        ]);

        Kamar::create([
            'id_hotel' => $hotel1->id_hotel,
            'tipe_kamar' => 'Deluxe Suite',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar',
        ]);

        Kamar::create([
            'id_hotel' => $hotel1->id_hotel,
            'tipe_kamar' => 'Executive Suite',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room',
        ]);

        Kamar::create([
            'id_hotel' => $hotel1->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        // Hotel 2 - Madinah
        $hotel2 = Hotel::create([
            'nama_hotel' => 'Hotel Nabawi Madinah',
            'lokasi' => 'Dekat Masjid Nabawi',
            'tipe_hotel' => 'Premium',
            'bintang' => 4,
            'negara' => 'Arab Saudi',
            'kota' => 'Madinah',
            'fasilitas' => 'AC, TV, WiFi, Restoran, Gym',
        ]);

        Kamar::create([
            'id_hotel' => $hotel2->id_hotel,
            'tipe_kamar' => 'Superior Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub',
        ]);

        Kamar::create([
            'id_hotel' => $hotel2->id_hotel,
            'tipe_kamar' => 'Family Suite',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Kitchenette',
        ]);

        // Hotel 3 - Transit Jakarta
        $hotel3 = Hotel::create([
            'nama_hotel' => 'Grand Transit Hotel',
            'lokasi' => 'Dekat Bandara',
            'tipe_hotel' => 'Business',
            'bintang' => 3,
            'negara' => 'Indonesia',
            'kota' => 'Jakarta',
            'fasilitas' => 'AC, TV, WiFi, Restoran',
        ]);

        Kamar::create([
            'id_hotel' => $hotel3->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        Kamar::create([
            'id_hotel' => $hotel3->id_hotel,
            'tipe_kamar' => 'Deluxe Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub',
        ]);
    }
}