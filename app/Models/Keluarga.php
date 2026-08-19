<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluargas';
    protected $primaryKey = 'id_keluarga';

    protected $fillable = [
        'id_departure',
        'kode_keluarga',
        'nama_keluarga',
        'alamat',
        'telepon_rumah',
        'jumlah_anggota'
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure');
    }

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'id_keluarga');
    }
}
