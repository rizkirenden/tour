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
    ];

    protected $casts = [
        'durasi_hari' => 'integer',
    ];

    // Relasi ke Produk Paket
    public function produkPakets()
    {
        return $this->hasMany(ProdukPaket::class, 'paket_tour_id', 'id_paket_tour');
    }

    // Relasi ke Hotel melalui pivot 'hotel_paket_tour'
    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_paket_tour', 'id_paket_tour', 'id_hotel')
                    ->withPivot('urutan')
                    ->withTimestamps()
                    ->orderBy('urutan', 'asc');
    }

    // Accessor untuk total harga hotel
    public function getTotalHargaHotelAttribute()
    {
        $total = 0;
        foreach ($this->hotels as $hotel) {
            $total += $hotel->harga_per_malam ?? 0;
        }
        return $total;
    }

    public function getTotalHargaHotelFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_hotel, 0, ',', '.');
    }

    public function getNamaPaketAttribute()
    {
        return $this->kota_tujuan . ' (' . $this->durasi_hari . ' Hari)';
    }
}
