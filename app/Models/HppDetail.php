<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HppDetail extends Model
{
    use HasFactory;

    protected $table = 'hpp_details';

    protected $fillable = [
        'departure',
        'jenis_biaya',
        'nama_item',
        'jumlah',
        'harga_satuan',
        'total_biaya',
        'keterangan'
    ];

    protected $casts = [
        'total_biaya' => 'integer'
    ];

    public function departureRelation()
    {
        return $this->belongsTo(Departure::class, 'departure', 'nama_keberangkatan');
    }
}
