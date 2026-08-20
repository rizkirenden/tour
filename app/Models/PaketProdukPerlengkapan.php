<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketProdukPerlengkapan extends Model
{
    protected $table = 'paket_produk_perlengkapan';

    protected $fillable = [
        'id_produk',
        'id_perlengkapan',
        'kuantitas',
        'catatan'
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke ProdukPaket
    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'id_produk', 'id_produk');
    }

    // Relasi ke Perlengkapan
    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan', 'id_perlengkapan');
    }

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        return $this->kuantitas * ($this->perlengkapan->harga_satuan ?? 0);
    }

    // Accessor untuk subtotal formatted
    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Scope untuk filter berdasarkan produk
    public function scopeByProduk($query, $produkId)
    {
        return $query->where('id_produk', $produkId);
    }

    // Scope untuk filter berdasarkan perlengkapan
    public function scopeByPerlengkapan($query, $perlengkapanId)
    {
        return $query->where('id_perlengkapan', $perlengkapanId);
    }
}
