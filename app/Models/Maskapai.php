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
        'nama_maskapai',
    ];

    public function tipePenerbangan()
    {
        return $this->hasMany(MaskapaiTipePenerbangan::class, 'id_maskapai', 'id_maskapai');
    }

    public function getTipePenerbanganStringAttribute()
    {
        return $this->tipePenerbangan->pluck('tipe_penerbangan')->implode(', ');
    }

    public function getTipePenerbanganBadgesAttribute()
    {
        $badges = [];
        foreach ($this->tipePenerbangan as $tipe) {
            if ($tipe->tipe_penerbangan == 'Internasional') {
                $badges[] = '<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Internasional</span>';
            } else {
                $badges[] = '<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Domestik</span>';
            }
        }
        return implode(' ', $badges);
    }

    public function getIsDomestikAttribute()
    {
        return $this->tipePenerbangan->contains('tipe_penerbangan', 'Domestik');
    }

    public function getIsInternasionalAttribute()
    {
        return $this->tipePenerbangan->contains('tipe_penerbangan', 'Internasional');
    }
}