<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamaahHotelRequest extends Model
{
    use HasFactory;

    protected $table = 'jamaah_hotel_requests';

    protected $fillable = [
        'jamaah',
        'departure',
        'hotel_default',
        'hotel_request',
        'lokasi',
        'harga_default_per_malam',
        'harga_request_per_malam',
        'selisih_per_malam',
        'durasi_menginap',
        'total_selisih',
        'alasan_request',
        'tanggal_request'
    ];

    protected $casts = [
        'tanggal_request' => 'date'
    ];

    public function jamaahRelation()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah', 'nama_lengkap');
    }

    public function departureRelation()
    {
        return $this->belongsTo(Departure::class, 'departure', 'nama_keberangkatan');
    }
}
