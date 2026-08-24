<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaskapaiTipePenerbangan extends Model
{
    use HasFactory;

    protected $table = 'maskapai_tipe_penerbangans';

    protected $fillable = [
        'id_maskapai',
        'tipe_penerbangan',
    ];

    // Relasi ke Maskapai
    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'id_maskapai', 'id_maskapai');
    }
}
