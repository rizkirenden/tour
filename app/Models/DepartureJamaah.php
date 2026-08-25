<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartureJamaah extends Model
{
    use HasFactory;

    protected $table = 'departure_jamaahs';

    protected $fillable = [
        'id_departure',
        'id_jamaah',
        'status_keberangkatan',
        'catatan',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure', 'id_departure');
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah', 'id_jamaah');
    }
}