<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketPerlengkapan extends Model
{
    use HasFactory;

    protected $table = 'paket_perlengkapans';

    protected $fillable = [
        'id_produk',
        'id_perlengkapan',
        'jumlah',
        'wajib'
    ];

    protected $casts = [
        'wajib' => 'boolean'
    ];

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'id_produk');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan');
    }
}
