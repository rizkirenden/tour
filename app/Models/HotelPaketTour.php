<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPaketTour extends Model
{
    use HasFactory;

    protected $table = 'hotel_paket_tour';

    protected $fillable = [
        'id_paket_tour',
        'id_hotel',
        'durasi_menginap',
        'urutan',
        'catatan',
    ];

    protected $casts = [
        'durasi_menginap' => 'integer',
        'urutan' => 'integer',
    ];

    public function paketTour()
    {
        return $this->belongsTo(PaketTour::class, 'id_paket_tour');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }
}
