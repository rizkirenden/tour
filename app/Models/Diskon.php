<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    protected $table = 'diskons';
    protected $primaryKey = 'id_diskon';

    protected $fillable = [
        'nama_diskon',
        'nilai_diskon',
        'berlaku_untuk_produk',
        'kuota',
        'sudah_digunakan',
    ];

    protected $casts = [
        'nilai_diskon' => 'integer',
        'kuota' => 'integer',
        'sudah_digunakan' => 'integer',
    ];

    // Accessor untuk nilai diskon formatted
    public function getNilaiDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_diskon, 0, ',', '.');
    }

    // Accessor untuk sisa kuota
    public function getSisaKuotaAttribute()
    {
        if ($this->kuota === null) {
            return '∞';
        }
        return $this->kuota - $this->sudah_digunakan;
    }

    // Accessor untuk status ketersediaan
    public function getIsAvailableAttribute()
    {
        if ($this->kuota === null) {
            return true;
        }
        return $this->sudah_digunakan < $this->kuota;
    }

    // Accessor untuk status kuota
    public function getStatusKuotaAttribute()
    {
        if ($this->kuota === null) {
            return 'Unlimited';
        }

        $sisa = $this->sisa_kuota;
        if ($sisa <= 0) {
            return 'Habis';
        } elseif ($sisa <= 5) {
            return 'Hampir Habis';
        } elseif ($sisa <= 10) {
            return 'Tersisa Sedikit';
        }
        return 'Tersedia';
    }

    // Accessor untuk warna status kuota
    public function getStatusKuotaColorAttribute()
    {
        if ($this->kuota === null) {
            return 'bg-green-100 text-green-700';
        }

        $sisa = $this->sisa_kuota;
        if ($sisa <= 0) {
            return 'bg-red-100 text-red-700';
        } elseif ($sisa <= 5) {
            return 'bg-yellow-100 text-yellow-700';
        } elseif ($sisa <= 10) {
            return 'bg-orange-100 text-orange-700';
        }
        return 'bg-green-100 text-green-700';
    }

    // Scope untuk diskon yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->whereNull('kuota')
              ->orWhereRaw('sudah_digunakan < kuota');
        });
    }

    // Relasi ke Keluarga
    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'id_diskon', 'id_diskon');
    }

    // Relasi ke Jamaah
    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'id_diskon', 'id_diskon');
    }
}
