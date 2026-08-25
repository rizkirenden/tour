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
        'is_active',
    ];

    protected $casts = [
        'harga_per_orang' => 'integer',
        'durasi_hari' => 'integer',
        'is_active' => 'boolean',
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

    // Accessor untuk harga tour formatted
    public function getHargaPerOrangFormattedAttribute()
    {
        if ($this->harga_per_orang) {
            return 'Rp ' . number_format($this->harga_per_orang, 0, ',', '.');
        }
        return '-';
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

    // Accessor status
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Aktif</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Tidak Aktif</span>';
    }
}