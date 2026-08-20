<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPaket extends Model
{
    use HasFactory;

    protected $table = 'produk_pakets';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'deskripsi',
        'harga_dasar',
        'hotel_mekkah_default',
        'hotel_madinah_default',
        'hotel_transit_default',
        'include_tur',
        'paket_tour_id',
        'status_keberangkatan_id',
        'durasi_mekkah',
        'durasi_madinah',
        'durasi_transit',
        'durasi_hari',
        'harga_visa',
        'harga_handling',
        'harga_muthowwif',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'include_tur' => 'boolean',
        'is_active' => 'boolean',
        'harga_dasar' => 'integer',
        'harga_visa' => 'integer',
        'harga_handling' => 'integer',
        'harga_muthowwif' => 'integer'
    ];

    // Relasi ke Hotel
    public function hotelMekkah()
    {
        return $this->belongsTo(Hotel::class, 'hotel_mekkah_default', 'id_hotel');
    }

    public function hotelMadinah()
    {
        return $this->belongsTo(Hotel::class, 'hotel_madinah_default', 'id_hotel');
    }

    public function hotelTransit()
    {
        return $this->belongsTo(Hotel::class, 'hotel_transit_default', 'id_hotel');
    }

    // Relasi ke Paket Tour
    public function paketTour()
    {
        return $this->belongsTo(PaketTour::class, 'paket_tour_id', 'id_paket_tour');
    }

    // Relasi ke Status Keberangkatan
    public function statusKeberangkatan()
    {
        return $this->belongsTo(StatusKeberangkatan::class, 'status_keberangkatan_id', 'id_status');
    }

    public function paketPerlengkapans()
    {
        return $this->hasMany(PaketPerlengkapan::class, 'id_produk');
    }

    public function paketHotels()
    {
        return $this->hasMany(PaketHotel::class, 'id_produk');
    }

    public function departures()
    {
        return $this->hasMany(Departure::class, 'produk_paket', 'nama_produk');
    }

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'produk_paket', 'nama_produk');
    }

    public function getHargaDasarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_dasar, 0, ',', '.');
    }

    public function getHargaVisaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_visa, 0, ',', '.');
    }

    public function getTotalHargaPerOrangAttribute()
    {
        return $this->harga_dasar + $this->harga_visa + $this->harga_handling + $this->harga_muthowwif;
    }

    // Accessor untuk menampilkan nama hotel
    public function getHotelMekkahNamaAttribute()
    {
        return $this->hotelMekkah ? $this->hotelMekkah->nama_hotel : '-';
    }

    public function getHotelMadinahNamaAttribute()
    {
        return $this->hotelMadinah ? $this->hotelMadinah->nama_hotel : '-';
    }

    public function getHotelTransitNamaAttribute()
    {
        return $this->hotelTransit ? $this->hotelTransit->nama_hotel : '-';
    }
}