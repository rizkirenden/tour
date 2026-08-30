<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukHargaBulanan extends Model
{
    use HasFactory;

    protected $table = 'produk_harga_bulanan';

    protected $fillable = [
        'produk_paket_id',
        'bulan',
        'tahun',
        'harga',
        'flyer',
        'is_active',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'harga' => 'integer',
        'is_active' => 'boolean',
    ];

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket_id', 'id_produk');
    }

    public function getBulanFormattedAttribute()
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $namaBulan[$this->bulan] ?? $this->bulan;
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getFlyerUrlAttribute()
    {
        if ($this->flyer) {
            return asset('storage/' . $this->flyer);
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
