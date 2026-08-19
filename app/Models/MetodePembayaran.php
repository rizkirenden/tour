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
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function transaksiPemasukans()
    {
        return $this->hasMany(TransaksiPemasukan::class, 'metode_pembayaran', 'nama_bank');
    }
}
