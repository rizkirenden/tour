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
        'produk_paket',
        'nama_lengkap',
        'telepon',
        'alamat',
        'nomor_paspor',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'kota_asal',
        'pulau',
        'bandara_keberangkatan',
        'bulan_keberangkatan',
        'tahun_keberangkatan',
        'foto_ktp',
        'foto_vaksin',
        'foto_visa',
        'encryption_key',
        'jenis_pendampingan',
        'agent',
        'fee_agent',
        'harga_tiket_pergi',
        'harga_tiket_pulang',
        'total_tiket_domestik',
        'hotel_mekkah',
        'hotel_madinah',
        'hotel_transit',
        'tipe_kamar',
        'selisih_hotel_mekkah',
        'selisih_hotel_madinah',
        'total_selisih_hotel',
        'total_tagihan_sebelum_diskon',
        'persen_diskon',
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
        'fee_agent' => 'integer',
        'harga_tiket_pergi' => 'integer',
        'harga_tiket_pulang' => 'integer',
        'total_tiket_domestik' => 'integer',
        'selisih_hotel_mekkah' => 'integer',
        'selisih_hotel_madinah' => 'integer',
        'total_selisih_hotel' => 'integer',
        'total_tagihan_sebelum_diskon' => 'integer',
        'persen_diskon' => 'decimal:2',
        'total_diskon' => 'integer',
        'total_tagihan_setelah_diskon' => 'integer',
        'total_dibayar' => 'integer',
        'sisa_tagihan' => 'integer'
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_keberangkatan', 'nama_keberangkatan');
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'id_keluarga');
    }

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket', 'nama_produk');
    }

    public function hotelRequests()
    {
        return $this->hasMany(JamaahHotelRequest::class, 'jamaah', 'nama_lengkap');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'jamaah', 'nama_lengkap');
    }

    public function transaksiPemasukan()
    {
        return $this->hasMany(TransaksiPemasukan::class, 'jamaah', 'nama_lengkap');
    }

    public function perlengkapanJamaahs()
    {
        return $this->hasMany(PerlengkapanJamaah::class, 'id_jamaah');
    }

    public function kamarJamaahs()
    {
        return $this->hasMany(KamarJamaah::class, 'id_jamaah');
    }

    public function getNamaLengkapAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getStatusPembayaranLabelAttribute()
    {
        $statuses = [
            'Belum Bayar' => 'Belum Bayar',
            'DP' => 'DP',
            'Setoran' => 'Setoran',
            'Lunas' => 'Lunas'
        ];
        return $statuses[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    public function getJenisKelaminLabelAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getTotalTagihanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_setelah_diskon, 0, ',', '.');
    }

    public function getSisaTagihanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    public function setNamaLengkapAttribute($value)
    {
        $this->attributes['nama_lengkap'] = ucwords(strtolower($value));
    }
}
