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
        'id_departure',
        'id_hotel',
        'lokasi',
        'nomor_kamar',
        'tipe_kamar',
        'kapasitas',
        'harga_per_malam',
        'total_malam',
        'total_biaya',
        'keterangan'
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }

    public function kamarJamaahs()
    {
        return $this->hasMany(KamarJamaah::class, 'id_kamar');
    }
}
