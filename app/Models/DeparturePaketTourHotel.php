<?php
// app/Models/DeparturePaketTourHotel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeparturePaketTourHotel extends Model
{
    use HasFactory;

    protected $table = 'departure_paket_tour_hotels';

    protected $fillable = [
        'id_departure',
        'id_paket_tour',
        'id_hotel',
        'urutan',
        'harga_per_malam',
        'durasi_menginap',
        'jumlah_kamar',
        'tipe_kamar',
        'catatan',
    ];

    protected $casts = [
        'harga_per_malam' => 'integer',
        'durasi_menginap' => 'integer',
        'jumlah_kamar' => 'integer',
        'urutan' => 'integer',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure', 'id_departure');
    }

    public function paketTour()
    {
        return $this->belongsTo(PaketTour::class, 'id_paket_tour', 'id_paket_tour');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
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
