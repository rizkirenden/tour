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
        'agent_name',
        'fee_agent',
        'pendampingan_nama',
        'pendampingan_fee',
        'pendampingan_fee_petugas',
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
        'pendampingan_fee' => 'integer',
        'pendampingan_fee_petugas' => 'integer',
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

    public function departures()
    {
        return $this->belongsToMany(Departure::class, 'departure_jamaahs', 'id_jamaah', 'id_departure')
                    ->withPivot('status_keberangkatan', 'catatan')
                    ->withTimestamps();
    }

    public function departureJamaahs()
    {
        return $this->hasMany(DepartureJamaah::class, 'id_jamaah', 'id_jamaah');
    }

    // ==========================================
    // ACCESSORS - FILE URL
    // ==========================================

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

    // ==========================================
    // ACCESSORS - FILE TYPE
    // ==========================================

    public function getFileKtpKkTypeAttribute()
    {
        if (!$this->file_ktp_kk) return null;
        $ext = strtolower(pathinfo($this->file_ktp_kk, PATHINFO_EXTENSION));
        return $ext === 'pdf' ? 'pdf' : 'image';
    }

    public function getFileVaksinTypeAttribute()
    {
        if (!$this->file_vaksin) return null;
        $ext = strtolower(pathinfo($this->file_vaksin, PATHINFO_EXTENSION));
        return $ext === 'pdf' ? 'pdf' : 'image';
    }

    public function getFileVisaTypeAttribute()
    {
        if (!$this->file_visa) return null;
        $ext = strtolower(pathinfo($this->file_visa, PATHINFO_EXTENSION));
        return $ext === 'pdf' ? 'pdf' : 'image';
    }

    public function getFilePasporTypeAttribute()
    {
        if (!$this->file_paspor) return null;
        $ext = strtolower(pathinfo($this->file_paspor, PATHINFO_EXTENSION));
        return $ext === 'pdf' ? 'pdf' : 'image';
    }

    // ==========================================
    // ACCESSORS - AGENT & PENDAMPINGAN
    // ==========================================

    public function getAgentNameFormattedAttribute()
    {
        return $this->agent_name ?? '-';
    }

    public function getFeeAgentFormattedAttribute()
    {
        return 'Rp ' . number_format($this->fee_agent, 0, ',', '.');
    }

    public function getPendampinganNamaFormattedAttribute()
    {
        return $this->pendampingan_nama ?? '-';
    }

    public function getPendampinganFeeFormattedAttribute()
    {
        return 'Rp ' . number_format($this->pendampingan_fee, 0, ',', '.');
    }

    public function getPendampinganFeePetugasFormattedAttribute()
    {
        return 'Rp ' . number_format($this->pendampingan_fee_petugas, 0, ',', '.');
    }

    public function getTotalPendampinganFeeAttribute()
    {
        return ($this->pendampingan_fee ?? 0) + ($this->pendampingan_fee_petugas ?? 0);
    }

    public function getTotalPendampinganFeeFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendampingan_fee, 0, ',', '.');
    }

    public function getTotalFeeAgentPendampinganAttribute()
    {
        return ($this->fee_agent ?? 0) + ($this->pendampingan_fee ?? 0) + ($this->pendampingan_fee_petugas ?? 0);
    }

    public function getTotalFeeAgentPendampinganFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_fee_agent_pendampingan, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - STATUS KELENGKAPAN
    // ==========================================

    public function getStatusPassportAttribute()
    {
        $fields = [
            'nomor_paspor' => 'Nomor Paspor',
            'paspor_expired' => 'Tanggal Berakhir',
            'paspor_terbit' => 'Tanggal Terbit',
            'paspor_diterbitkan_di' => 'Diterbitkan Di'
        ];

        $missing = [];
        foreach ($fields as $field => $label) {
            if (empty($this->$field)) {
                $missing[] = $label;
            }
        }

        if (count($missing) == 0) {
            return [
                'status' => 'complete',
                'label' => 'Lengkap',
                'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Lengkap</span>',
                'missing' => []
            ];
        } else {
            return [
                'status' => 'incomplete',
                'label' => 'Belum Lengkap',
                'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Belum Lengkap</span>',
                'missing' => $missing
            ];
        }
    }

    public function getStatusDokumenAttribute()
    {
        $fields = [
            'file_ktp_kk' => 'KTP/KK',
            'file_vaksin' => 'Vaksin',
            'file_visa' => 'Visa',
            'file_paspor' => 'Paspor'
        ];

        $uploaded = [];
        $missing = [];
        foreach ($fields as $field => $label) {
            if (!empty($this->$field)) {
                $uploaded[] = $label;
            } else {
                $missing[] = $label;
            }
        }

        if (count($missing) == 0) {
            return [
                'status' => 'complete',
                'label' => 'Lengkap',
                'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Lengkap</span>',
                'count' => count($uploaded),
                'total' => count($fields),
                'missing' => []
            ];
        } else {
            return [
                'status' => 'incomplete',
                'label' => count($uploaded) . '/' . count($fields) . ' Upload',
                'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">' . count($uploaded) . '/' . count($fields) . '</span>',
                'count' => count($uploaded),
                'total' => count($fields),
                'missing' => $missing
            ];
        }
    }

    public function getStatusKelengkapanAttribute()
    {
        $passport = $this->status_passport;
        $dokumen = $this->status_dokumen;

        if ($passport['status'] == 'complete' && $dokumen['status'] == 'complete') {
            return [
                'status' => 'complete',
                'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">✓ Lengkap</span>',
                'icon' => 'fas fa-check-circle text-green-500'
            ];
        } else {
            return [
                'status' => 'incomplete',
                'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">⚠ Belum Lengkap</span>',
                'icon' => 'fas fa-exclamation-triangle text-yellow-500'
            ];
        }
    }

    // ==========================================
    // ACCESSORS - SUMBER DATA
    // ==========================================

    public function getSumberDataAttribute()
    {
        if ($this->id_keluarga) {
            $keluarga = $this->keluarga;
            if ($keluarga) {
                return [
                    'source' => 'keluarga',
                    'label' => 'Dari Keluarga',
                    'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    <i class="fas fa-users mr-1"></i> Keluarga
                                </span>',
                    'detail' => $keluarga->nama_keluarga . ' (' . $keluarga->kode_keluarga . ')',
                    'kode_keluarga' => $keluarga->kode_keluarga,
                    'nama_keluarga' => $keluarga->nama_keluarga,
                    'link' => route('transaksional.keluarga.show', $keluarga->id_keluarga)
                ];
            }
        }

        return [
            'source' => 'jamaah',
            'label' => 'Input Mandiri',
            'badge' => '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            <i class="fas fa-user mr-1"></i> Mandiri
                        </span>',
            'detail' => 'Diinput langsung sebagai jamaah',
            'kode_keluarga' => null,
            'nama_keluarga' => null,
            'link' => null
        ];
    }

    // ==========================================
    // ACCESSORS - LAINNYA
    // ==========================================

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

    public function getDiskonNamaAttribute()
    {
        return $this->diskon ? $this->diskon->nama_diskon : '-';
    }

    public function getDiskonValueAttribute()
    {
        return $this->diskon ? $this->diskon->nilai_diskon : 0;
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('nomor_paspor', 'like', '%' . $search . '%')
                ->orWhere('id_keberangkatan', 'like', '%' . $search . '%')
                ->orWhere('produk_paket', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%')
                ->orWhere('agent_name', 'like', '%' . $search . '%')
                ->orWhere('pendampingan_nama', 'like', '%' . $search . '%');
        });

        $query->when($filters['status_pembayaran'] ?? null, function ($query, $status) {
            $query->where('status_pembayaran', $status);
        });

        $query->when($filters['jenis_kelamin'] ?? null, function ($query, $gender) {
            $query->where('jenis_kelamin', $gender);
        });

        $query->when($filters['sumber_data'] ?? null, function ($query, $source) {
            if ($source == 'keluarga') {
                $query->whereNotNull('id_keluarga');
            } elseif ($source == 'jamaah') {
                $query->whereNull('id_keluarga');
            }
        });
    }
}
