<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'id_invoice';

    protected $fillable = [
        'nomor_invoice',
        'jamaah',
        'departure',
        'tanggal_terbit',
        'tanggal_jatuh_tempo',
        'total_tagihan_sebelum_diskon',
        'persen_diskon',
        'total_diskon',
        'total_tagihan_setelah_diskon',
        'total_dibayar',
        'sisa_tagihan',
        'status_invoice'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'total_tagihan_sebelum_diskon' => 'integer',
        'persen_diskon' => 'decimal:2',
        'total_diskon' => 'integer',
        'total_tagihan_setelah_diskon' => 'integer',
        'total_dibayar' => 'integer',
        'sisa_tagihan' => 'integer'
    ];

    public function jamaahRelation()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah', 'nama_lengkap');
    }

    public function departureRelation()
    {
        return $this->belongsTo(Departure::class, 'departure', 'nama_keberangkatan');
    }

    public function getNomorInvoiceAttribute($value)
    {
        return strtoupper($value);
    }

    public function getTotalTagihanSebelumDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_sebelum_diskon, 0, ',', '.');
    }

    public function getTotalDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_diskon, 0, ',', '.');
    }

    public function getTotalTagihanSetelahDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_setelah_diskon, 0, ',', '.');
    }

    public function getTotalDibayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_dibayar, 0, ',', '.');
    }

    public function getSisaTagihanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    public function getStatusInvoiceLabelAttribute()
    {
        $statuses = [
            'Draft' => 'Draft',
            'Terbit' => 'Terbit',
            'Lunas' => 'Lunas',
            'Jatuh Tempo' => 'Jatuh Tempo'
        ];
        return $statuses[$this->status_invoice] ?? $this->status_invoice;
    }

    public function getStatusInvoiceBadgeAttribute()
    {
        $badges = [
            'Draft' => 'secondary',
            'Terbit' => 'info',
            'Lunas' => 'success',
            'Jatuh Tempo' => 'danger'
        ];
        return $badges[$this->status_invoice] ?? 'secondary';
    }

    public function getIsLunasAttribute()
    {
        return $this->status_invoice === 'Lunas';
    }

    public function getIsJatuhTempoAttribute()
    {
        return $this->status_invoice === 'Jatuh Tempo' ||
               ($this->status_invoice === 'Terbit' && now()->greaterThan($this->tanggal_jatuh_tempo));
    }

    public function scopeLunas($query)
    {
        return $query->where('status_invoice', 'Lunas');
    }

    public function scopeTerbit($query)
    {
        return $query->where('status_invoice', 'Terbit');
    }

    public function scopeJatuhTempo($query)
    {
        return $query->where('status_invoice', 'Jatuh Tempo');
    }
}
