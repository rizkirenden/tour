<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeparturePerlengkapan extends Model
{
    use HasFactory;

    protected $table = 'departure_perlengkapan';

    protected $fillable = [
        'id_departure',
        'id_perlengkapan',
        'jumlah_per_jamaah',
        'harga_satuan',
        'total_harga',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'jumlah_per_jamaah' => 'integer',
        'harga_satuan' => 'integer',
        'total_harga' => 'integer',
        'is_active' => 'boolean',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure', 'id_departure');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan', 'id_perlengkapan');
    }

    public function perlengkapanJamaahs()
    {
        return $this->hasMany(PerlengkapanJamaah::class, 'id_departure_perlengkapan', 'id');
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getHargaSatuanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Aktif</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Nonaktif</span>';
    }
}