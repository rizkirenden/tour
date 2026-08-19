<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Maskapai extends Model
{
    use HasFactory;

    protected $table = 'maskapais';
    protected $primaryKey = 'id_maskapai';

    protected $fillable = [
        'kode_maskapai',
        'nama_maskapai',
        'tipe_penerbangan'
    ];

    public function hargaTiketPerBulans()
    {
        return $this->hasMany(HargaTiketPerBulan::class, 'kode_maskapai', 'kode_maskapai');
    }
}
