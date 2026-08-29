<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaketTourSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah tabel paket_tours sudah ada datanya
        if (DB::table('paket_tours')->count() == 0) {

            DB::table('paket_tours')->insert([
                [
                    'kota_tujuan' => 'Mekkah & Madinah',
                    'negara' => 'Arab Saudi',
                    'durasi_hari' => 12,
                    'deskripsi' => 'Paket Umrah Premium 12 Hari dengan hotel bintang 5 di Mekkah dan Madinah',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'Mekkah & Madinah',
                    'negara' => 'Arab Saudi',
                    'durasi_hari' => 9,
                    'deskripsi' => 'Paket Umrah Ekonomis 9 Hari dengan hotel bintang 4 di Mekkah dan Madinah',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'Istanbul',
                    'negara' => 'Turki',
                    'durasi_hari' => 7,
                    'deskripsi' => 'Paket Wisata Turki 7 Hari mengunjungi Istanbul, Cappadocia, dan Pamukkale',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'Dubai',
                    'negara' => 'UEA',
                    'durasi_hari' => 5,
                    'deskripsi' => 'Paket Wisata Dubai 5 Hari mengunjungi Burj Khalifa, Dubai Mall, dan Palm Jumeirah',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'Tokyo',
                    'negara' => 'Jepang',
                    'durasi_hari' => 7,
                    'deskripsi' => 'Paket Wisata Jepang 7 Hari mengunjungi Tokyo, Kyoto, dan Osaka',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'London',
                    'negara' => 'Inggris',
                    'durasi_hari' => 5,
                    'deskripsi' => 'Paket Wisata Inggris 5 Hari mengunjungi London, Big Ben, dan Tower Bridge',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'Madrid',
                    'negara' => 'Spanyol',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Paket Wisata Spanyol 3 Hari mengunjungi Madrid dan Barcelona',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'kota_tujuan' => 'Kairo',
                    'negara' => 'Mesir',
                    'durasi_hari' => 6,
                    'deskripsi' => 'Paket Wisata Mesir 6 Hari mengunjungi Piramida Giza dan Sungai Nil',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

            $this->command->info('Paket Tour berhasil di-seed!');
            $this->command->info('Total data: ' . DB::table('paket_tours')->count() . ' paket');
        } else {
            $this->command->info('Data paket tour sudah ada, skip seeding.');
            $this->command->info('Total data: ' . DB::table('paket_tours')->count() . ' paket');
        }
    }
}
