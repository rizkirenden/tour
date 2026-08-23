<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayarans';
    protected $primaryKey = 'id_metode';

    protected $fillable = [
        'kode_bank',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transaksis()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_metode_pembayaran', 'id_metode');
    }

    public function getNamaLengkapAttribute()
    {
        return $this->kode_bank . ' - ' . $this->nama_bank;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
