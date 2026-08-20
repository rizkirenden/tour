<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelPaketTour extends Model
{
    protected $table = 'hotel_paket_tour';

    protected $fillable = [
        'id_paket_tour',
        'id_hotel',
        'durasi_menginap',
        'harga_hotel',
        'urutan',
        'catatan'
    ];

    protected $casts = [
        'durasi_menginap' => 'integer',
        'harga_hotel' => 'integer',
        'urutan' => 'integer',
    ];

    // Relasi ke PaketTour
    public function paketTour()
    {
        return $this->belongsTo(PaketTour::class, 'id_paket_tour', 'id_paket_tour');
    }

    // Relasi ke Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');  // <-- Perhatikan parameter kedua dan ketiga
    }
}
