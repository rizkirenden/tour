<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaAsal extends Model
{
    use HasFactory;

    protected $table = 'kota_asals';
    protected $primaryKey = 'id_kota';

    protected $fillable = [
        'nama_kota',
        'provinsi',
        'pulau',
        'bandara_terdekat'
    ];

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'kota_asal', 'nama_kota');
    }
}
