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
                'nama_produk' => 'Umroh Executive 12 Hari',
                'deskripsi' => 'Paket umroh executive dengan hotel bintang 5 di Mekkah dan Madinah. Termasuk transportasi dan tour guide.',
                'include_tur' => 1,
                'paket_tour_id' => 1,
                'harga_dasar' => 25000000,
                'durasi_perjalanan' => 12,
                'durasi_mekkah' => 5,
                'durasi_madinah' => 5,
                'durasi_hari' => 12,
                'flyer' => null,
                'kategori' => 'Executive',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Umroh Premium 9 Hari',
                'deskripsi' => 'Paket umroh premium dengan hotel bintang 4 dan fasilitas lengkap.',
                'include_tur' => 0,
                'paket_tour_id' => null,
                'harga_dasar' => 18000000,
                'durasi_perjalanan' => 9,
                'durasi_mekkah' => 4,
                'durasi_madinah' => 4,
                'durasi_hari' => 9,
                'flyer' => null,
                'kategori' => 'Premium',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Umroh Ekonomi 7 Hari',
                'deskripsi' => 'Paket umroh ekonomis dengan hotel bintang 3 dan fasilitas standar.',
                'include_tur' => 0,
                'paket_tour_id' => null,
                'harga_dasar' => 12000000,
                'durasi_perjalanan' => 7,
                'durasi_mekkah' => 3,
                'durasi_madinah' => 3,
                'durasi_hari' => 7,
                'flyer' => null,
                'kategori' => 'Ekonomi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Umroh VIP 14 Hari',
                'deskripsi' => 'Paket umroh VIP dengan layanan eksklusif, hotel bintang 5, dan pendampingan khusus.',
                'include_tur' => 1,
                'paket_tour_id' => 1,
                'harga_dasar' => 35000000,
                'durasi_perjalanan' => 14,
                'durasi_mekkah' => 6,
                'durasi_madinah' => 6,
                'durasi_hari' => 14,
                'flyer' => null,
                'kategori' => 'VIP',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Umroh Reguler 10 Hari',
                'deskripsi' => 'Paket umroh reguler dengan hotel bintang 4 dan fasilitas standar.',
                'include_tur' => 0,
                'paket_tour_id' => null,
                'harga_dasar' => 15000000,
                'durasi_perjalanan' => 10,
                'durasi_mekkah' => 4,
                'durasi_madinah' => 4,
                'durasi_hari' => 10,
                'flyer' => null,
                'kategori' => 'Reguler',
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
