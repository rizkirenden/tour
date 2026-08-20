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

    // Accessor untuk format harga
    public function getHargaPerOrangFormattedAttribute()
    {
        return $this->harga_per_orang ? 'Rp ' . number_format($this->harga_per_orang, 0, ',', '.') : '-';
    }
}