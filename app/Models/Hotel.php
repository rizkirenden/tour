<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    protected $primaryKey = 'id_hotel';

    protected $fillable = [
        'nama_hotel',
        'lokasi',
        'tipe_hotel',
        'bintang',
        'negara',
        'kota',
        'fasilitas',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'id_hotel');
    }

    public function paketHotels()
    {
        return $this->hasMany(PaketHotel::class, 'id_hotel');
    }

    public function paketTours()
    {
        return $this->belongsToMany(PaketTour::class, 'hotel_paket_tour', 'id_hotel', 'id_paket_tour')
            ->withPivot('durasi_menginap', 'harga_hotel', 'urutan', 'catatan')
            ->withTimestamps();
    }

    public function getNamaHotelAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getBintangLabelAttribute()
    {
        return str_repeat('★', $this->bintang) . str_repeat('☆', 5 - $this->bintang);
    }

    public function getBintangTextAttribute()
    {
        if (empty($this->bintang) || $this->bintang == 0) {
            return '-';
        }
        return str_repeat('⭐', (int)$this->bintang);
    }

    public function scopeByLokasi($query, $lokasi)
    {
        return $query->where('lokasi', $lokasi);
    }

    public function scopeByBintang($query, $bintang)
    {
        return $query->where('bintang', $bintang);
    }
    public function getHargaPerMalamFormattedAttribute()
    {
        return $this->harga_per_malam ? 'Rp ' . number_format($this->harga_per_malam, 0, ',', '.') : 'Rp 0';
    }
}
