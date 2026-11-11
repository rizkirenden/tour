<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamaahUpgradeHotel extends Model
{
    use HasFactory;

    protected $table = 'jamaah_upgrade_hotels';

    protected $fillable = [
        'id_jamaah',
        'id_keluarga',
        'id_hotel',
        'id_kamar',
        'tipe_kamar',
        'harga_per_pax',
        'jumlah_pax',
        'total_harga',
        'jumlah_kamar',
        'durasi_menginap',
        'harga_per_malam',
        'catatan',
    ];

    protected $casts = [
        'harga_per_pax' => 'integer',
        'jumlah_pax' => 'integer',
        'total_harga' => 'integer',
        'jumlah_kamar' => 'integer',
        'durasi_menginap' => 'integer',
        'harga_per_malam' => 'integer',
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah', 'id_jamaah');
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_keluarga', 'id_keluarga');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->total_harga = (int) ($model->harga_per_pax ?? 0) * (int) ($model->jumlah_pax ?? 1);
        });
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format((int) $this->total_harga, 0, ',', '.');
    }

    public function getHargaPerPaxFormattedAttribute()
    {
        return 'Rp ' . number_format((int) $this->harga_per_pax, 0, ',', '.');
    }

    public function getIsKeluargaUpgradeAttribute()
    {
        return !is_null($this->id_keluarga) && is_null($this->id_jamaah);
    }

    public function getTotalHargaSafeAttribute(): int
    {
        return (int) ($this->total_harga ?? 0);
    }
}
