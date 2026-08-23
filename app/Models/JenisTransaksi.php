<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    use HasFactory;

    protected $table = 'jenis_transaksis';
    protected $primaryKey = 'id_jenis';

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transaksis()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function detailHargaProduk()
    {
        return $this->hasMany(DetailHargaProduk::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function getKodeNamaAttribute()
    {
        return $this->kode . ' - ' . $this->nama;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getKodeNamaKeteranganAttribute()
    {
        return $this->kode . ' - ' . $this->nama . ' (' . $this->keterangan . ')';
    }
}
