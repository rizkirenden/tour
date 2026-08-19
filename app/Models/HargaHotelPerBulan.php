<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaHotelPerBulan extends Model
{
    use HasFactory;

    protected $table = 'harga_hotel_per_bulans';

    protected $fillable = [
        'hotel',
        'lokasi',
        'bulan',
        'tahun',
        'harga_per_malam',
        'kapasitas',
        'tipe_kamar',
        'catatan'
    ];

    protected $casts = [
        'harga_per_malam' => 'integer'
    ];

    public function hotelRelation()
    {
        return $this->belongsTo(Hotel::class, 'hotel', 'nama_hotel');
    }
}
