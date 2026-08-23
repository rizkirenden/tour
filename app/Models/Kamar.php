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
        'jumlah_kamar',
        'harga_per_malam',
        'fasilitas_kamar',
    ];

    protected $casts = [
        'harga_per_malam' => 'decimal:2',
    ];

    // Relasi ke Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }

    // Accessor untuk tipe kamar
    public function getTipeKamarAttribute($value)
    {
        return ucwords(strtolower($value));
    }
}
