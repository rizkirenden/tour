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
        'kode_diskon',
        'nama_diskon',
        'persen_diskon',
        'berlaku_untuk_produk',
        'kuota',
        'sudah_digunakan'
    ];

    protected $casts = [
        'persen_diskon' => 'decimal:2',
        'kuota' => 'integer',
        'sudah_digunakan' => 'integer',
    ];

    // Accessor untuk format persen
    public function getPersenDiskonFormattedAttribute()
    {
        return $this->persen_diskon . '%';
    }

    // Accessor untuk sisa kuota
    public function getSisaKuotaAttribute()
    {
        if (!$this->kuota) return 'Unlimited';
        $sisa = $this->kuota - $this->sudah_digunakan;
        return $sisa > 0 ? $sisa : 0;
    }

    // Accessor untuk status kuota
    public function getStatusKuotaAttribute()
    {
        if (!$this->kuota) return 'Unlimited';
        if ($this->sudah_digunakan >= $this->kuota) {
            return 'Habis';
        }
        return $this->sisa_kuota . ' tersisa';
    }

    // Accessor untuk warna status kuota
    public function getStatusKuotaColorAttribute()
    {
        if (!$this->kuota) return 'bg-gray-100 text-gray-700';
        if ($this->sudah_digunakan >= $this->kuota) {
            return 'bg-red-100 text-red-700';
        }
        return 'bg-green-100 text-green-700';
    }
}
