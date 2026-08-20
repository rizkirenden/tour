<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukPaketSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produk_pakets')->insert([
            [
                'id_produk' => 1,
                'kode_produk' => 'PKT-001-2026',
                'nama_produk' => 'Umroh Executive 12 Hari',
                'deskripsi' => 'Paket umroh executive dengan hotel bintang 5',
                'harga_dasar' => 35000000,
                'hotel_mekkah_default' => 1,
                'hotel_madinah_default' => 2,
                'hotel_transit_default' => null,
                'include_tur' => 1,
                'paket_tour_id' => 1,
                'status_keberangkatan_id' => 1,
                'durasi_mekkah' => 5,
                'durasi_madinah' => 5,
                'durasi_transit' => 1,
                'durasi_hari' => 12,
                'harga_visa' => 1500000,
                'harga_handling' => 500000,
                'harga_muthowwif' => 1000000,
                'kategori' => 'Executive',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 2,
                'kode_produk' => 'PKT-002-2026',
                'nama_produk' => 'Umroh Premium 9 Hari',
                'deskripsi' => 'Paket umroh premium dengan hotel bintang 4',
                'harga_dasar' => 25000000,
                'hotel_mekkah_default' => 1,
                'hotel_madinah_default' => 2,
                'hotel_transit_default' => null,
                'include_tur' => 0,
                'paket_tour_id' => null,
                'status_keberangkatan_id' => 2,
                'durasi_mekkah' => 4,
                'durasi_madinah' => 4,
                'durasi_transit' => 1,
                'durasi_hari' => 9,
                'harga_visa' => 1500000,
                'harga_handling' => 500000,
                'harga_muthowwif' => 800000,
                'kategori' => 'Premium',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}