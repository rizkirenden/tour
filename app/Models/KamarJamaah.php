<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamarJamaah extends Model
{
    use HasFactory;

    protected $table = 'kamar_jamaahs';

    protected $fillable = [
        'id_kamar',
        'id_jamaah',
        'posisi_tempat_tidur',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah');
    }
}
