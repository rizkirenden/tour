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
        'kode_hotel',
        'nama_hotel',
        'lokasi',
        'tipe_hotel',
        'bintang',
        'tipe_kamar',
        'harga_per_malam',
        'kapasitas',
        'negara',
        'kota',
        'fasilitas',
    ];

    protected $casts = [
        'harga_per_malam' => 'integer',
        'is_active' => 'boolean'
    ];

    public function paketHotels()
    {
        return $this->hasMany(PaketHotel::class, 'id_hotel');
    }

    public function hargaHotelPerBulans()
    {
        return $this->hasMany(HargaHotelPerBulan::class, 'hotel', 'nama_hotel');
    }

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'id_hotel');
    }

    public function getNamaHotelAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getHargaPerMalamFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_malam, 0, ',', '.');
    }

    public function getBintangLabelAttribute()
    {
        return str_repeat('★', $this->bintang) . str_repeat('☆', 5 - $this->bintang);
    }

    public function getIsActiveLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeByLokasi($query, $lokasi)
    {
        return $query->where('lokasi', $lokasi);
    }

    public function scopeByBintang($query, $bintang)
    {
        return $query->where('bintang', $bintang);
    }
     public function getBintangTextAttribute()
    {
        if (empty($this->bintang) || $this->bintang == 0) {
            return '-';
        }
        return str_repeat('⭐', (int)$this->bintang);
    }
}
