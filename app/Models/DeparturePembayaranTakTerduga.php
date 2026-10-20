<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeparturePembayaranTakTerduga extends Model
{
    use HasFactory;

    protected $table = 'departure_pembayaran_tak_terduga';
    protected $primaryKey = 'id_pembayaran_tak_terduga';

    protected $fillable = [
        'id_departure',
        'nama_pembayaran',
        'total_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'total_pembayaran' => 'integer',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure', 'id_departure');
    }

    public function getTotalPembayaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pembayaran ?? 0, 0, ',', '.');
    }
}