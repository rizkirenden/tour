<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKeberangkatan extends Model
{
    use HasFactory;

    protected $table = 'status_keberangkatans';
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'nama_status',
        'warna',
        'keterangan'
    ];

    public function departures()
    {
        return $this->hasMany(Departure::class, 'status', 'nama_status');
    }
}
