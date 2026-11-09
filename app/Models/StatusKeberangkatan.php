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
        'keterangan'
    ];

    public function departures()
    {
        return $this->hasMany(Departure::class, 'id_status', 'id_status');
    }

    public function getBadgeAttribute()
    {
        return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">' . $this->nama_status . '</span>';
    }
}
