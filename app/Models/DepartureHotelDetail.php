<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartureHotelDetail extends Model
{
    use HasFactory;

    protected $table = 'departure_hotel_details';

    protected $fillable = [
        'id_departure',
        'id_hotel',
        'id_kamar',
        'tipe_kamar',
        'jumlah_kamar',
        'harga_per_malam',
        'durasi_menginap',
        'catatan',
    ];

    protected $casts = [
        'jumlah_kamar' => 'integer',
        'harga_per_malam' => 'integer',
        'durasi_menginap' => 'integer',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure', 'id_departure');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    public function getTotalHargaAttribute()
    {
        return $this->harga_per_malam * $this->durasi_menginap * $this->jumlah_kamar;
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getHargaPerMalamFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_malam, 0, ',', '.');
    }
}