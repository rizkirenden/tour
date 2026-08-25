<?php
// app/Models/JenisTransaksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    use HasFactory;

    protected $table = 'jenis_transaksis';
    protected $primaryKey = 'id_jenis';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function transaksis()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function detailHargaProduk()
    {
        return $this->hasMany(DetailHargaProduk::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function departureJenisTransaksis()
    {
        return $this->hasMany(DepartureJenisTransaksi::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function getNamaKeteranganAttribute()
    {
        return $this->nama . ($this->keterangan ? ' (' . $this->keterangan . ')' : '');
    }
}
