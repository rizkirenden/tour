<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHargaProduk extends Model
{
    use HasFactory;

    protected $table = 'detail_harga_produk';

    protected $fillable = [
        'id_produk',
        'id_jenis_transaksi',
        'harga',
        'catatan',
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    public function produk()
    {
        return $this->belongsTo(ProdukPaket::class, 'id_produk', 'id_produk');
    }

    public function jenisTransaksi()
    {
        return $this->belongsTo(JenisTransaksi::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
