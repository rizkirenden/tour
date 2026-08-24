<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    protected $table = 'jamaahs';
    protected $primaryKey = 'id_jamaah';

    protected $fillable = [
        'id_keberangkatan',
        'id_keluarga',
        'hubungan_keluarga',
        'is_kepala_keluarga',
        'produk_paket',
        'id_diskon',
        'nama_lengkap',
        'nik',
        'nama_ayah',
        'pekerjaan',
        'telepon',
        'wa',
        'alamat',
        'nomor_paspor',
        'paspor_expired',
        'paspor_terbit',
        'paspor_diterbitkan_di',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'kota_asal',
        'pulau',
        'bandara_keberangkatan',
        'bulan_keberangkatan',
        'tahun_keberangkatan',
        'file_ktp_kk',
        'file_vaksin',
        'file_visa',
        'file_paspor',
        'encryption_key',
        'jenis_pendampingan',
        'agent',
        'fee_agent',
        'harga_tiket_pergi_domestik',
        'harga_tiket_pulang_domestik',
        'total_tiket_domestik',
        'harga_tiket_pergi_international',
        'harga_tiket_pulang_international',
        'total_tiket_international',
        'hotel_mekkah',
        'hotel_madinah',
        'hotel_transit',
        'total_tagihan_sebelum_diskon',
        'nilai_diskon',
        'total_diskon',
        'total_tagihan_setelah_diskon',
        'total_dibayar',
        'sisa_tagihan',
        'status_pembayaran',
        'keterangan_diskon',
        'catatan_tambahan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'paspor_expired' => 'date',
        'paspor_terbit' => 'date',
        'fee_agent' => 'integer',
        'harga_tiket_pergi_domestik' => 'integer',
        'harga_tiket_pulang_domestik' => 'integer',
        'total_tiket_domestik' => 'integer',
        'harga_tiket_pergi_international' => 'integer',
        'harga_tiket_pulang_international' => 'integer',
        'total_tiket_international' => 'integer',
        'total_tagihan_sebelum_diskon' => 'integer',
        'nilai_diskon' => 'integer',
        'total_diskon' => 'integer',
        'total_tagihan_setelah_diskon' => 'integer',
        'total_dibayar' => 'integer',
        'sisa_tagihan' => 'integer',
        'bulan_keberangkatan' => 'integer',
        'tahun_keberangkatan' => 'integer',
        'is_kepala_keluarga' => 'boolean',
    ];

    // === RELATIONSHIPS ===
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_keluarga', 'id_keluarga');
    }

    public function transaksis()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_jamaah', 'id_jamaah');
    }

    public function produkPaketData()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket', 'nama_produk');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'id_diskon', 'id_diskon');
    }

    // === ACCESSORS ===
    public function getKepalaKeluargaLabelAttribute()
    {
        return $this->is_kepala_keluarga ? 'Ya' : 'Tidak';
    }

    public function getStatusPembayaranBadgeAttribute()
    {
        $colors = [
            'Belum Bayar' => 'bg-red-100 text-red-700',
            'DP' => 'bg-yellow-100 text-yellow-700',
            'Setoran' => 'bg-blue-100 text-blue-700',
            'Lunas' => 'bg-green-100 text-green-700'
        ];
        $color = $colors[$this->status_pembayaran] ?? 'bg-gray-100 text-gray-700';
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $color . '">' . $this->status_pembayaran . '</span>';
    }

    public function getJenisKelaminLabelAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : ($this->jenis_kelamin == 'P' ? 'Perempuan' : '-');
    }

    public function getTanggalLahirFormattedAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d/m/Y') : '-';
    }

    public function getPasporExpiredFormattedAttribute()
    {
        return $this->paspor_expired ? $this->paspor_expired->format('d/m/Y') : '-';
    }

    public function getPasporTerbitFormattedAttribute()
    {
        return $this->paspor_terbit ? $this->paspor_terbit->format('d/m/Y') : '-';
    }

    // === FORMATTED ATTRIBUTES ===
    public function getFeeAgentFormattedAttribute()
    {
        return 'Rp ' . number_format($this->fee_agent, 0, ',', '.');
    }

    public function getTotalTiketDomestikFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tiket_domestik, 0, ',', '.');
    }

    public function getTotalTiketInternationalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tiket_international, 0, ',', '.');
    }

    public function getTotalTagihanSebelumDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_sebelum_diskon, 0, ',', '.');
    }

    public function getTotalDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_diskon, 0, ',', '.');
    }

    public function getNilaiDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_diskon, 0, ',', '.');
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

    // === FILE URL ACCESSORS ===
    public function getFileKtpKkUrlAttribute()
    {
        return $this->file_ktp_kk ? asset('storage/' . $this->file_ktp_kk) : null;
    }

    public function getFileVaksinUrlAttribute()
    {
        return $this->file_vaksin ? asset('storage/' . $this->file_vaksin) : null;
    }

    public function getFileVisaUrlAttribute()
    {
        return $this->file_visa ? asset('storage/' . $this->file_visa) : null;
    }

    public function getFilePasporUrlAttribute()
    {
        return $this->file_paspor ? asset('storage/' . $this->file_paspor) : null;
    }

    // === FILE EXTENSION & TYPE ===
    public function getFileKtpKkTypeAttribute()
    {
        if (!$this->file_ktp_kk) return null;
        $ext = pathinfo($this->file_ktp_kk, PATHINFO_EXTENSION);
        return in_array(strtolower($ext), ['pdf']) ? 'pdf' : 'image';
    }

    public function getFileVaksinTypeAttribute()
    {
        if (!$this->file_vaksin) return null;
        $ext = pathinfo($this->file_vaksin, PATHINFO_EXTENSION);
        return in_array(strtolower($ext), ['pdf']) ? 'pdf' : 'image';
    }

    public function getFileVisaTypeAttribute()
    {
        if (!$this->file_visa) return null;
        $ext = pathinfo($this->file_visa, PATHINFO_EXTENSION);
        return in_array(strtolower($ext), ['pdf']) ? 'pdf' : 'image';
    }

    public function getFilePasporTypeAttribute()
    {
        if (!$this->file_paspor) return null;
        $ext = pathinfo($this->file_paspor, PATHINFO_EXTENSION);
        return in_array(strtolower($ext), ['pdf']) ? 'pdf' : 'image';
    }

    public function getFileKtpKkIconAttribute()
    {
        $type = $this->file_ktp_kk_type;
        if ($type === 'pdf') return 'fa-file-pdf text-red-500';
        if ($type === 'image') return 'fa-file-image text-green-500';
        return 'fa-file text-gray-500';
    }

    public function getFileVaksinIconAttribute()
    {
        $type = $this->file_vaksin_type;
        if ($type === 'pdf') return 'fa-file-pdf text-red-500';
        if ($type === 'image') return 'fa-file-image text-green-500';
        return 'fa-file text-gray-500';
    }

    public function getFileVisaIconAttribute()
    {
        $type = $this->file_visa_type;
        if ($type === 'pdf') return 'fa-file-pdf text-red-500';
        if ($type === 'image') return 'fa-file-image text-green-500';
        return 'fa-file text-gray-500';
    }

    public function getFilePasporIconAttribute()
    {
        $type = $this->file_paspor_type;
        if ($type === 'pdf') return 'fa-file-pdf text-red-500';
        if ($type === 'image') return 'fa-file-image text-green-500';
        return 'fa-file text-gray-500';
    }

    public function getDiskonNamaAttribute()
    {
        return $this->diskon ? $this->diskon->nama_diskon : '-';
    }

    public function getDiskonValueAttribute()
    {
        return $this->diskon ? $this->diskon->nilai_diskon : 0;
    }

    // === SCOPE ===
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('nomor_paspor', 'like', '%' . $search . '%')
                ->orWhere('id_keberangkatan', 'like', '%' . $search . '%')
                ->orWhere('produk_paket', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%');
        });

        $query->when($filters['status_pembayaran'] ?? null, function ($query, $status) {
            $query->where('status_pembayaran', $status);
        });

        $query->when($filters['jenis_kelamin'] ?? null, function ($query, $gender) {
            $query->where('jenis_kelamin', $gender);
        });
    }
}
