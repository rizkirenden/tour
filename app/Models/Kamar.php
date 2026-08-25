<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'id_hotel',
        'tipe_kamar',
        'kapasitas',
        'fasilitas_kamar',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    // Accessor untuk badge kapasitas
    public function getKapasitasBadgeAttribute()
    {
        return '<span class="inline-flex px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">' . $this->kapasitas . ' orang</span>';
    }
}