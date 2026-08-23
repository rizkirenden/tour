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
                'kode' => 'JTR-001',
                'nama' => 'DP',
                'keterangan' => 'Transaksi pembayaran uang muka (DP)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-002',
                'nama' => 'Angsuran 1',
                'keterangan' => 'Transaksi pembayaran angsuran ke-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-003',
                'nama' => 'Angsuran 2',
                'keterangan' => 'Transaksi pembayaran angsuran ke-2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-004',
                'nama' => 'Angsuran 3',
                'keterangan' => 'Transaksi pembayaran angsuran ke-3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-005',
                'nama' => 'Angsuran 4',
                'keterangan' => 'Transaksi pembayaran angsuran ke-4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-006',
                'nama' => 'Angsuran 5',
                'keterangan' => 'Transaksi pembayaran angsuran ke-5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-007',
                'nama' => 'Angsuran 6',
                'keterangan' => 'Transaksi pembayaran angsuran ke-6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-008',
                'nama' => 'Angsuran 7',
                'keterangan' => 'Transaksi pembayaran angsuran ke-7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-009',
                'nama' => 'Angsuran 8',
                'keterangan' => 'Transaksi pembayaran angsuran ke-8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-010',
                'nama' => 'Angsuran 9',
                'keterangan' => 'Transaksi pembayaran angsuran ke-9',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-011',
                'nama' => 'Angsuran 10',
                'keterangan' => 'Transaksi pembayaran angsuran ke-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-012',
                'nama' => 'Pelunasan',
                'keterangan' => 'Transaksi pembayaran pelunasan sisa tagihan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JTR-013',
                'nama' => 'Lunas',
                'keterangan' => 'Status transaksi telah lunas',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Tiket
            [
                'kode' => 'VISA',
                'nama' => 'VISA',
                'keterangan' => 'Biaya Visa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TKD-P',
                'nama' => 'Tiket Domestik Pergi',
                'keterangan' => 'Tiket domestik pergi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TKD-PL',
                'nama' => 'Tiket Domestik Pulang',
                'keterangan' => 'Tiket domestik pulang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TKI',
                'nama' => 'Tiket Internasional',
                'keterangan' => 'Tiket internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Manasik
            [
                'kode' => 'MAN-F',
                'nama' => 'Manasik Fikih (Zamrud)',
                'keterangan' => 'Manasik Fikih Zamrud',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'MAN-Q',
                'nama' => 'Manasik Qolbu',
                'keterangan' => 'Manasik Qolbu',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Hotel
            [
                'kode' => 'HOT-JKT',
                'nama' => 'Hotel Jakarta',
                'keterangan' => 'Hotel transit Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'HOT-MKK',
                'nama' => 'Hotel Mekkah',
                'keterangan' => 'Hotel Mekkah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'HOT-MDN',
                'nama' => 'Hotel Madinah',
                'keterangan' => 'Hotel Madinah',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Handling
            [
                'kode' => 'HDL-JKT',
                'nama' => 'Handling Jakarta (Pulang Pergi)',
                'keterangan' => 'Handling Jakarta pulang pergi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'HDL-INT',
                'nama' => 'Handling Internasional (Pulang Pergi)',
                'keterangan' => 'Handling internasional pulang pergi',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'KRT-CPT',
                'nama' => 'Kereta Cepat',
                'keterangan' => 'Kereta cepat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TR-LDR',
                'nama' => 'Tour Leader',
                'keterangan' => 'Tour leader',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'MUT-10',
                'nama' => 'Muthowwif 10 Days',
                'keterangan' => 'Muthowwif 10 hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Operasional
            [
                'kode' => 'BIAYA-OP',
                'nama' => 'Biaya Operasional',
                'keterangan' => 'Biaya operasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'AIR-ZAM',
                'nama' => 'Air Zam Zam',
                'keterangan' => 'Air zam zam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'SERTIF',
                'nama' => 'Sertifikat Umroh Arrum',
                'keterangan' => 'Sertifikat umroh arrum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
