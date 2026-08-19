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
        'keterangan'
    ];

    public function transaksiPemasukans()
    {
        return $this->hasMany(TransaksiPemasukan::class, 'jenis_transaksi', 'nama');
    }
}
