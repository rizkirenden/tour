<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketTour extends Model
{
    use HasFactory;

    protected $table = 'paket_tours';
    protected $primaryKey = 'id_paket_tour';

    protected $fillable = [
        'kota_tujuan',
        'negara',
        'durasi_hari',
        'deskripsi',
        'harga_per_orang',
    ];

    protected $casts = [
        'harga_per_orang' => 'integer',
        'durasi_hari' => 'integer',
    ];

    // Relasi ke ProdukPaket (inverse)
    public function produkPakets()
    {
        return $this->hasMany(ProdukPaket::class, 'paket_tour_id', 'id_paket_tour');
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_paket_tour', 'id_paket_tour', 'id_hotel')
                    ->withPivot('durasi_menginap', 'urutan', 'catatan')
                    ->withTimestamps()
                    ->orderBy('hotel_paket_tour.urutan');
    }

    // Accessor untuk format harga
    public function getHargaPerOrangFormattedAttribute()
    {
        return $this->harga_per_orang ? 'Rp ' . number_format($this->harga_per_orang, 0, ',', '.') : '-';
    }

    // Accessor untuk total harga hotel
    public function getTotalHargaHotelAttribute()
    {
        // Harga hotel diambil dari tabel hotels (harga_per_malam) * durasi_menginap
        $total = 0;
        foreach ($this->hotels as $hotel) {
            $total += ($hotel->harga_per_malam ?? 0) * ($hotel->pivot->durasi_menginap ?? 1);
        }
        return $total;
    }

    public function getTotalHargaHotelFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_hotel, 0, ',', '.');
    }
}
