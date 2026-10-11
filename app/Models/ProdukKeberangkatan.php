<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKeberangkatan extends Model
{
    use HasFactory;

    protected $table = 'produk_keberangkatans';

    protected $fillable = [
        'produk_paket_id',
        'bulan',
        'tahun',
        'is_active',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'is_active' => 'boolean',
    ];

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket_id', 'id_produk');
    }

    public function jamaahs()
    {
        return $this->belongsToMany(
            Jamaah::class,
            'jamaah_keberangkatans',
            'id_produk_keberangkatan',
            'id_jamaah'
        )->withPivot([
            'bulan_keberangkatan',
            'tahun_keberangkatan',
            'bulan_harga',
            'tahun_harga',
            'harga',
            'id_produk_harga_bulanan'
        ])->withTimestamps();
    }

    public function getBulanFormattedAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return ($bulanNama[$this->bulan] ?? '-') . ' ' . $this->tahun;
    }

    public function getKeberangkatanFormattedAttribute()
    {
        return $this->bulan_formatted;
    }

    public function getKeberangkatanBadgeAttribute()
    {
        $color = $this->is_active
            ? 'bg-purple-100 text-purple-700'
            : 'bg-gray-100 text-gray-500';
        return '<span class="px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">'
            . $this->keberangkatan_formatted . '</span>';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
