<?php
// app/Models/DokumenPerusahaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_perusahaans';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'jenis',
        'path',
        'nama_penandatangan',
        'jabatan'
    ];

    const JENIS_LOGO = 'logo';
    const JENIS_TTD = 'ttd';
    const JENIS_CAP = 'cap';

    public function getUrlAttribute()
    {
        if ($this->path) {
            return Storage::url($this->path);
        }
        return null;
    }

    public function getBase64Attribute()
    {
        if ($this->path) {
            $fullPath = storage_path('app/public/' . $this->path);
            if (file_exists($fullPath)) {
                $imageData = file_get_contents($fullPath);
                return 'data:image/png;base64,' . base64_encode($imageData);
            }
        }
        return null;
    }

    public static function getByJenis($jenis)
    {
        return self::where('jenis', $jenis)->orderBy('created_at', 'desc')->first();
    }

    public static function getLogo()
    {
        return self::getByJenis(self::JENIS_LOGO);
    }

    public static function getTtd()
    {
        return self::getByJenis(self::JENIS_TTD);
    }

    public static function getCap()
    {
        return self::getByJenis(self::JENIS_CAP);
    }
}
