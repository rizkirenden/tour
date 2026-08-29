<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_transaksis')->insert([
            // Pembayaran
            [
                'nama' => 'DP',
                'keterangan' => 'Transaksi pembayaran uang muka (DP)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Angsuran 1',
                'keterangan' => 'Transaksi pembayaran angsuran ke-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pelunasan',
                'keterangan' => 'Transaksi pembayaran pelunasan sisa tagihan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lunas',
                'keterangan' => 'Status transaksi telah lunas',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Tiket
            [
                'nama' => 'VISA',
                'keterangan' => 'Biaya Visa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tiket Domestik Pergi',
                'keterangan' => 'Tiket domestik pergi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tiket Domestik Pulang',
                'keterangan' => 'Tiket domestik pulang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tiket Internasional',
                'keterangan' => 'Tiket internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Manasik
            [
                'nama' => 'Manasik Fikih (Zamrud)',
                'keterangan' => 'Manasik Fikih Zamrud',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Manasik Qolbu',
                'keterangan' => 'Manasik Qolbu',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Hotel
            [
                'nama' => 'Hotel Jakarta',
                'keterangan' => 'Hotel transit Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Hotel Mekkah',
                'keterangan' => 'Hotel Mekkah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Hotel Madinah',
                'keterangan' => 'Hotel Madinah',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Handling
            [
                'nama' => 'Handling Jakarta (Pulang Pergi)',
                'keterangan' => 'Handling Jakarta pulang pergi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Handling Internasional (Pulang Pergi)',
                'keterangan' => 'Handling internasional pulang pergi',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama' => 'Kereta Cepat',
                'keterangan' => 'Kereta cepat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tour Leader',
                'keterangan' => 'Tour leader',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Muthowwif 10 Days',
                'keterangan' => 'Muthowwif 10 hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Operasional
            [
                'nama' => 'Biaya Operasional',
                'keterangan' => 'Biaya operasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Air Zam Zam',
                'keterangan' => 'Air zam zam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sertifikat Umroh Arrum',
                'keterangan' => 'Sertifikat umroh arrum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
