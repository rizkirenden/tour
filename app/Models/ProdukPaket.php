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

    // Relasi many-to-many dengan Perlengkapan melalui pivot
    public function perlengkapans()
    {
        return $this->belongsToMany(Perlengkapan::class, 'paket_produk_perlengkapan', 'id_produk', 'id_perlengkapan')
                    ->withPivot('kuantitas', 'catatan')
                    ->withTimestamps()
                    ->orderBy('paket_produk_perlengkapan.created_at');
    }

    // Relasi langsung ke pivot (untuk akses langsung)
    public function paketProdukPerlengkapans()
    {
        return $this->hasMany(PaketProdukPerlengkapan::class, 'id_produk', 'id_produk');
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

    // Accessor untuk harga
    public function getHargaDasarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_dasar, 0, ',', '.');
    }

    public function getHargaVisaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_visa ?? 0, 0, ',', '.');
    }

    public function getHargaHandlingFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_handling ?? 0, 0, ',', '.');
    }

    public function getHargaMuthowwifFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_muthowwif ?? 0, 0, ',', '.');
    }

    public function getTotalHargaPerOrangAttribute()
    {
        return $this->harga_dasar + ($this->harga_visa ?? 0) + ($this->harga_handling ?? 0) + ($this->harga_muthowwif ?? 0);
    }

    public function getTotalHargaPerOrangFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_per_orang, 0, ',', '.');
    }

    // Accessor untuk total harga perlengkapan
    public function getTotalHargaPerlengkapanAttribute()
    {
        $total = 0;
        foreach ($this->perlengkapans as $item) {
            $total += ($item->pivot->kuantitas ?? 1) * ($item->harga_satuan ?? 0);
        }
        return $total;
    }

    public function getTotalHargaPerlengkapanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_perlengkapan, 0, ',', '.');
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

    public function getTotalHargaPaketTourHotelAttribute()
    {
        if ($this->paketTour) {
            return $this->paketTour->total_harga_hotel;
        }
        return 0;
    }

    public function getTotalHargaPaketTourHotelFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_paket_tour_hotel, 0, ',', '.');
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

    public function getIncludeTurLabelAttribute()
    {
        return $this->include_tur ? 'Ya' : 'Tidak';
    }

    public function getIncludeTurBadgeAttribute()
    {
        return $this->include_tur
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Include Tur</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Tanpa Tur</span>';
    }
}
