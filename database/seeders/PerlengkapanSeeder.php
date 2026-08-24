<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerlengkapanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perlengkapans')->insert([
            [
                'nama_perlengkapan' => 'Koper Ukuran Besar',
                'deskripsi' => 'Koper 28 inch dengan roda 360 derajat, material abs hardcase',
                'harga_satuan' => 750000,
                'satuan' => 'Buah',
                'kategori' => 'Koper',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perlengkapan' => 'Koper Ukuran Sedang',
                'deskripsi' => 'Koper 24 inch dengan roda 360 derajat',
                'harga_satuan' => 550000,
                'satuan' => 'Buah',
                'kategori' => 'Koper',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perlengkapan' => 'Baju Ihram Pria',
                'deskripsi' => 'Baju ihram putih 2 helai untuk pria, bahan katun',
                'harga_satuan' => 250000,
                'satuan' => 'Set',
                'kategori' => 'Pakaian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perlengkapan' => 'Baju Ihram Wanita',
                'deskripsi' => 'Baju ihram untuk wanita, bahan katun nyaman',
                'harga_satuan' => 200000,
                'satuan' => 'Set',
                'kategori' => 'Pakaian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perlengkapan' => 'Tas Selempang',
                'deskripsi' => 'Tas selempang untuk menyimpan dokumen dan barang berharga',
                'harga_satuan' => 150000,
                'satuan' => 'Buah',
                'kategori' => 'Aksesoris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perlengkapan' => 'Passport Holder',
                'deskripsi' => 'Tempat menyimpan passport dan tiket pesawat',
                'harga_satuan' => 50000,
                'satuan' => 'Buah',
                'kategori' => 'Dokumen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perlengkapan' => 'Sandal Jepit',
                'deskripsi' => 'Sandal jepit untuk kegiatan di dalam hotel',
                'harga_satuan' => 75000,
                'satuan' => 'Pasang',
                'kategori' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
