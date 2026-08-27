<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayarans';
    protected $primaryKey = 'id_metode';

    const JENIS_BANK_TRANSFER = 'bank_transfer';
    const JENIS_CASH = 'cash';
    const JENIS_E_WALLET = 'e_wallet';

    protected $fillable = [
        'jenis_pembayaran',
        'kode_bank',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'e_wallet_type',
        'nomor_telepon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transaksis()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_metode_pembayaran', 'id_metode');
    }

    public function getNamaLengkapAttribute()
    {
        if ($this->jenis_pembayaran === self::JENIS_CASH) {
            return 'Cash / Tunai';
        }
        if ($this->jenis_pembayaran === self::JENIS_E_WALLET) {
            return $this->e_wallet_type . ' - ' . $this->nomor_telepon;
        }
        return $this->kode_bank . ' - ' . $this->nama_bank;
    }

    public function getDisplayNameAttribute()
    {
        switch ($this->jenis_pembayaran) {
            case self::JENIS_CASH:
                return '💰 Tunai';
            case self::JENIS_E_WALLET:
                return '📱 ' . ($this->e_wallet_type ?? 'E-Wallet');
            default:
                return '🏦 ' . $this->nama_bank;
        }
    }

    public function getIconAttribute()
    {
        switch ($this->jenis_pembayaran) {
            case self::JENIS_CASH:
                return 'fas fa-money-bill-wave';
            case self::JENIS_E_WALLET:
                return 'fas fa-mobile-alt';
            default:
                return 'fas fa-university';
        }
    }

    public function getColorAttribute()
    {
        switch ($this->jenis_pembayaran) {
            case self::JENIS_CASH:
                return 'green';
            case self::JENIS_E_WALLET:
                return 'purple';
            default:
                return 'blue';
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('jenis_pembayaran', self::JENIS_BANK_TRANSFER);
    }

    public function scopeCash($query)
    {
        return $query->where('jenis_pembayaran', self::JENIS_CASH);
    }

    public function scopeEWallet($query)
    {
        return $query->where('jenis_pembayaran', self::JENIS_E_WALLET);
    }
}
