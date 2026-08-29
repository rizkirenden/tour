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

        // ============ HOTEL 4 - MADRID, SPAIN ============
        $hotel4 = Hotel::create([
            'nama_hotel' => 'Hotel Real Madrid',
            'lokasi' => 'Pusat Kota Madrid, dekat Santiago Bernabéu Stadium',
            'tipe_hotel' => 'Luxury',
            'bintang' => 5,
            'negara' => 'Spanyol',
            'kota' => 'Madrid',
            'fasilitas' => 'AC, TV, WiFi, Kolam Renang, Restoran, Spa, Gym, Bar, Ruang Pertemuan',
        ]);

        Kamar::create([
            'id_hotel' => $hotel4->id_hotel,
            'tipe_kamar' => 'Presidential Suite',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room, Dining Room, Balkon',
        ]);

        Kamar::create([
            'id_hotel' => $hotel4->id_hotel,
            'tipe_kamar' => 'Executive Suite',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room',
        ]);

        Kamar::create([
            'id_hotel' => $hotel4->id_hotel,
            'tipe_kamar' => 'Deluxe Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar',
        ]);

        Kamar::create([
            'id_hotel' => $hotel4->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        // ============ HOTEL 5 - ISTANBUL, TURKEY ============
        $hotel5 = Hotel::create([
            'nama_hotel' => 'Hotel Sultanahmet Istanbul',
            'lokasi' => 'Dekat Masjid Biru dan Hagia Sophia',
            'tipe_hotel' => 'Premium',
            'bintang' => 4,
            'negara' => 'Turki',
            'kota' => 'Istanbul',
            'fasilitas' => 'AC, TV, WiFi, Restoran, Gym, Spa, Rooftop View, Kolam Renang',
        ]);

        Kamar::create([
            'id_hotel' => $hotel5->id_hotel,
            'tipe_kamar' => 'Suite with Bosphorus View',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room, Balkon dengan Pemandangan',
        ]);

        Kamar::create([
            'id_hotel' => $hotel5->id_hotel,
            'tipe_kamar' => 'Deluxe Suite',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Balkon',
        ]);

        Kamar::create([
            'id_hotel' => $hotel5->id_hotel,
            'tipe_kamar' => 'Superior Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub',
        ]);

        Kamar::create([
            'id_hotel' => $hotel5->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        // ============ HOTEL 6 - DUBAI, UAE (Tambahan) ============
        $hotel6 = Hotel::create([
            'nama_hotel' => 'Burj Al Arab Jumeirah',
            'lokasi' => 'Jumeirah Beach, Dubai',
            'tipe_hotel' => 'Luxury',
            'bintang' => 7,
            'negara' => 'Uni Emirat Arab',
            'kota' => 'Dubai',
            'fasilitas' => 'AC, TV, WiFi, Kolam Renang, Restoran, Spa, Gym, Private Beach, Helipad',
        ]);

        Kamar::create([
            'id_hotel' => $hotel6->id_hotel,
            'tipe_kamar' => 'Royal Suite',
            'kapasitas' => 6,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room, Dining Room, Private Elevator, Cinema',
        ]);

        Kamar::create([
            'id_hotel' => $hotel6->id_hotel,
            'tipe_kamar' => 'Panoramic Suite',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room, Balkon',
        ]);

        Kamar::create([
            'id_hotel' => $hotel6->id_hotel,
            'tipe_kamar' => 'Deluxe Suite',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar',
        ]);

        // ============ HOTEL 7 - TOKYO, JAPAN (Tambahan) ============
        $hotel7 = Hotel::create([
            'nama_hotel' => 'Hotel Imperial Tokyo',
            'lokasi' => 'Pusat Kota Tokyo, dekat Istana Kekaisaran',
            'tipe_hotel' => 'Luxury',
            'bintang' => 5,
            'negara' => 'Jepang',
            'kota' => 'Tokyo',
            'fasilitas' => 'AC, TV, WiFi, Kolam Renang, Restoran, Spa, Gym, Ruang Pertemuan',
        ]);

        Kamar::create([
            'id_hotel' => $hotel7->id_hotel,
            'tipe_kamar' => 'Imperial Suite',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room, View Kota',
        ]);

        Kamar::create([
            'id_hotel' => $hotel7->id_hotel,
            'tipe_kamar' => 'Executive Suite',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room',
        ]);

        Kamar::create([
            'id_hotel' => $hotel7->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        // ============ HOTEL 8 - LONDON, UK (Tambahan) ============
        $hotel8 = Hotel::create([
            'nama_hotel' => 'The Ritz London',
            'lokasi' => 'Piccadilly, London',
            'tipe_hotel' => 'Luxury',
            'bintang' => 5,
            'negara' => 'Inggris',
            'kota' => 'London',
            'fasilitas' => 'AC, TV, WiFi, Restoran, Spa, Gym, Ruang Pertemuan, Klub Malam',
        ]);

        Kamar::create([
            'id_hotel' => $hotel8->id_hotel,
            'tipe_kamar' => 'Royal Suite',
            'kapasitas' => 4,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room, Dining Room, Balkon',
        ]);

        Kamar::create([
            'id_hotel' => $hotel8->id_hotel,
            'tipe_kamar' => 'Deluxe Suite',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar',
        ]);

        Kamar::create([
            'id_hotel' => $hotel8->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        $this->command->info('Hotel dan Kamar berhasil di-seed!');
        $this->command->info('Total Hotel: ' . Hotel::count());
        $this->command->info('Total Kamar: ' . Kamar::count());
    }
}
