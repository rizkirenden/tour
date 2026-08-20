<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StatusKeberangkatanSeeder::class,
            HotelSeeder::class,
            JenisTransaksiSeeder::class,
            KategoriPengeluaranSeeder::class,
            KotaAsalSeeder::class,
            MaskapaiSeeder::class,
            MetodePembayaranSeeder::class,
            PerlengkapanSeeder::class,
            DiskonSeeder::class,
             PaketTourSeeder::class,
            ProdukPaketSeeder::class,
            PaketHotelSeeder::class,
        ]);
    }
}