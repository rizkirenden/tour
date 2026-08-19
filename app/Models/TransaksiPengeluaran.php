<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pengeluarans';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'kategori_pengeluaran',
        'departure',
        'tanggal_pengeluaran',
        'deskripsi',
        'jumlah',
        'metode',
        'bukti_pembayaran',
        'status_approval'
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
        'jumlah' => 'integer'
    ];

    public function kategoriPengeluaranRelation()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_pengeluaran', 'nama_kategori');
    }

    public function departureRelation()
    {
        return $this->belongsTo(Departure::class, 'departure', 'nama_keberangkatan');
    }

    public function getJumlahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusApprovalLabelAttribute()
    {
        $statuses = [
            'Pending' => 'Menunggu',
            'Approved' => 'Disetujui',
            'Rejected' => 'Ditolak'
        ];
        return $statuses[$this->status_approval] ?? $this->status_approval;
    }

    public function getStatusApprovalBadgeAttribute()
    {
        $badges = [
            'Pending' => 'warning',
            'Approved' => 'success',
            'Rejected' => 'danger'
        ];
        return $badges[$this->status_approval] ?? 'secondary';
    }

    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'Approved');
    }

    public function scopePending($query)
    {
        return $query->where('status_approval', 'Pending');
    }

    public function scopeByDeparture($query, $departure)
    {
        return $query->where('departure', $departure);
    }
}
