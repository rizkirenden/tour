<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketHotel extends Model
{
    use HasFactory;

    protected $table = 'paket_hotels';

    protected $fillable = [
        'id_produk',
        'id_hotel',
        'urutan',
        'adalah_default',
        'tipe_penginapan',
        'harga_per_orang'
    ];

    protected $casts = [
        'adalah_default' => 'boolean'
    ];

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'id_produk');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }
}
