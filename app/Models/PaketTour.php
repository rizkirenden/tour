<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketTour extends Model
{
    use HasFactory;

    protected $table = 'paket_tours';

    protected $fillable = [
        'id_produk',
        'kota_tujuan',
        'negara',
        'durasi_hari',
        'deskripsi',
        'harga_include',
        'harga_tambahan',
        'harga_per_orang'
    ];

    protected $casts = [
        'harga_include' => 'boolean'
    ];

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'id_produk');
    }
}
