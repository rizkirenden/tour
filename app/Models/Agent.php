<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
    ];

    // Jamaah menyimpan agen sebagai string (agent_name), jadi dihubungkan lewat nama
    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'agent_name', 'name');
    }
}
