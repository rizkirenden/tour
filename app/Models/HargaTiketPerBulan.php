<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaTiketPerBulan extends Model
{
    use HasFactory;

    protected $table = 'harga_tiket_per_bulans';

    protected $fillable = [
        'kota_asal',
        'pulau',
        'bandara',
        'bulan',
        'tahun',
        'tipe_tiket',
        'kode_maskapai',
        'nama_maskapai',
        'kelas',
        'harga',
        'catatan'
    ];

    protected $casts = [
        'harga' => 'integer'
    ];

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'kode_maskapai', 'kode_maskapai');
    }
}
