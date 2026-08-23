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
        // Nonaktifkan foreign key checks sementara
        Schema::disableForeignKeyConstraints();

        // Hapus data dengan urutan yang benar (dari child ke parent)
        DB::table('kamar_jamaahs')->truncate(); // Hapus data di tabel child terlebih dahulu
        DB::table('kamars')->truncate();
        DB::table('hotels')->truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Hotel 1 dengan multiple kamar
        $hotel1 = Hotel::create([
            'kode_hotel' => 'HOT-001',
            'nama_hotel' => 'Hotel Al-Haram Mekkah',
            'lokasi' => 'Dekat Masjidil Haram',
            'tipe_hotel' => 'Luxury',
            'bintang' => 5,
            'negara' => 'Arab Saudi',
            'kota' => 'Mekkah',
            'fasilitas' => 'AC, TV, WiFi, Kolam Renang, Restoran, Spa',
        ]);

        // Multiple kamar untuk Hotel 1
        Kamar::create([
            'id_hotel' => $hotel1->id_hotel,
            'tipe_kamar' => 'Deluxe Suite',
            'kapasitas' => 2,
            'jumlah_kamar' => 10,
            'harga_per_malam' => 2500000,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar',
        ]);

        Kamar::create([
            'id_hotel' => $hotel1->id_hotel,
            'tipe_kamar' => 'Executive Suite',
            'kapasitas' => 4,
            'jumlah_kamar' => 5,
            'harga_per_malam' => 3500000,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Minibar, Living Room',
        ]);

        Kamar::create([
            'id_hotel' => $hotel1->id_hotel,
            'tipe_kamar' => 'Standard Room',
            'kapasitas' => 2,
            'jumlah_kamar' => 20,
            'harga_per_malam' => 1500000,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        // Hotel 2 dengan multiple kamar
        $hotel2 = Hotel::create([
            'kode_hotel' => 'HOT-002',
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
            'jumlah_kamar' => 15,
            'harga_per_malam' => 1800000,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub',
        ]);

        Kamar::create([
            'id_hotel' => $hotel2->id_hotel,
            'tipe_kamar' => 'Family Suite',
            'kapasitas' => 4,
            'jumlah_kamar' => 8,
            'harga_per_malam' => 2800000,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub, Kitchenette',
        ]);

        // Hotel 3
        $hotel3 = Hotel::create([
            'kode_hotel' => 'HOT-003',
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
            'jumlah_kamar' => 30,
            'harga_per_malam' => 500000,
            'fasilitas_kamar' => 'AC, TV, WiFi',
        ]);

        Kamar::create([
            'id_hotel' => $hotel3->id_hotel,
            'tipe_kamar' => 'Deluxe Room',
            'kapasitas' => 2,
            'jumlah_kamar' => 10,
            'harga_per_malam' => 750000,
            'fasilitas_kamar' => 'AC, TV, WiFi, Bathub',
        ]);
    }
}
