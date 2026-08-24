<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perlengkapan extends Model
{
    use HasFactory;

    protected $table = 'perlengkapans';
    protected $primaryKey = 'id_perlengkapan';

    protected $fillable = [
        'nama_perlengkapan',
        'deskripsi',
        'harga_satuan',
        'satuan',
        'kategori'
    ];

    protected $casts = [
        'harga_satuan' => 'integer',
    ];

    // Relasi many-to-many dengan ProdukPaket
    public function produkPakets()
    {
        return $this->belongsToMany(ProdukPaket::class, 'paket_produk_perlengkapan', 'id_perlengkapan', 'id_produk')
                    ->withPivot('kuantitas', 'catatan')
                    ->withTimestamps();
    }

    // Relasi langsung ke pivot
    public function paketProdukPerlengkapans()
    {
        return $this->hasMany(PaketProdukPerlengkapan::class, 'id_perlengkapan', 'id_perlengkapan');
    }

    // Accessor untuk format harga
    public function getHargaSatuanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    // Accessor untuk badge kategori
    public function getKategoriBadgeAttribute()
    {
        $colors = [
            'Koper' => 'bg-blue-100 text-blue-700',
            'Pakaian' => 'bg-green-100 text-green-700',
            'Aksesoris' => 'bg-purple-100 text-purple-700',
            'Dokumen' => 'bg-yellow-100 text-yellow-700',
            'Lainnya' => 'bg-gray-100 text-gray-700',
        ];

        return $colors[$this->kategori] ?? 'bg-gray-100 text-gray-700';
    }

    // Accessor untuk icon kategori
    public function getKategoriIconAttribute()
    {
        $icons = [
            'Koper' => 'fa-suitcase',
            'Pakaian' => 'fa-tshirt',
            'Aksesoris' => 'fa-gem',
            'Dokumen' => 'fa-file-alt',
            'Lainnya' => 'fa-box',
        ];

        return $icons[$this->kategori] ?? 'fa-box';
    }

    // Scope untuk filter
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nama_perlengkapan', 'like', "%{$search}%");
    }
}
