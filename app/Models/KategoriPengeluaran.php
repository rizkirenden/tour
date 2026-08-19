<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengeluarans';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];

    public function transaksiPengeluarans()
    {
        return $this->hasMany(TransaksiPengeluaran::class, 'kategori_pengeluaran', 'nama_kategori');
    }
}
