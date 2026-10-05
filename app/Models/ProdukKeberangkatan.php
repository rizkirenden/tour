<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getBulanFormattedAttribute()
    {
        if ($this->bulan) {
            return Carbon::create()->month($this->bulan)->translatedFormat('F');
        }
        return '-';
    }

    public function getKeberangkatanFormattedAttribute()
    {
        return $this->bulan_formatted . ' ' . $this->tahun;
    }

    public function getKeberangkatanBadgeAttribute()
    {
        $color = $this->is_active
            ? 'bg-purple-100 text-purple-700'
            : 'bg-gray-100 text-gray-500';
        return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $color . '">'
            . $this->keberangkatan_formatted . '</span>';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('tahun')->orderBy('bulan');
    }
}
