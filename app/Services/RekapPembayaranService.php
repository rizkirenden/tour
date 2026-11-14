<?php
// app/Services/RekapPembayaranService.php

namespace App\Services;

use App\Models\Jamaah;
use App\Models\Keluarga;
use App\Models\TransaksiPembayaran;
use App\Models\MetodePembayaran;
use App\Models\JenisPembayaran;
use App\Models\ProdukPaket;
use Illuminate\Support\Facades\DB;

class RekapPembayaranService
{
    /**
     * Summary global (jamaah & keluarga).
     */
    public function getSummaryGlobal(): array
    {
        $jamaahStats = DB::table('jamaahs')
            ->whereNull('id_keluarga')
            ->selectRaw('
                COUNT(*) as total,
                SUM(total_tagihan_setelah_diskon) as total_tagihan,
                SUM(total_dibayar) as total_dibayar,
                SUM(total_refund) as total_refund,
                SUM(sisa_tagihan) as sisa_tagihan,
                SUM(sisa_kelebihan_bayar) as sisa_kelebihan
            ')
            ->first();

        $keluargaStats = DB::table('keluargas')
            ->selectRaw('
                COUNT(*) as total,
                SUM(total_tagihan_setelah_diskon) as total_tagihan,
                SUM(total_dibayar) as total_dibayar,
                SUM(total_refund) as total_refund,
                SUM(sisa_tagihan) as sisa_tagihan,
                SUM(sisa_kelebihan_bayar) as sisa_kelebihan
            ')
            ->first();

        return [
            'jamaah' => [
                'total' => (int) ($jamaahStats->total ?? 0),
                'total_tagihan' => (int) ($jamaahStats->total_tagihan ?? 0),
                'total_dibayar' => (int) ($jamaahStats->total_dibayar ?? 0),
                'total_refund' => (int) ($jamaahStats->total_refund ?? 0),
                'sisa_tagihan' => (int) ($jamaahStats->sisa_tagihan ?? 0),
                'sisa_kelebihan' => (int) ($jamaahStats->sisa_kelebihan ?? 0),
            ],
            'keluarga' => [
                'total' => (int) ($keluargaStats->total ?? 0),
                'total_tagihan' => (int) ($keluargaStats->total_tagihan ?? 0),
                'total_dibayar' => (int) ($keluargaStats->total_dibayar ?? 0),
                'total_refund' => (int) ($keluargaStats->total_refund ?? 0),
                'sisa_tagihan' => (int) ($keluargaStats->sisa_tagihan ?? 0),
                'sisa_kelebihan' => (int) ($keluargaStats->sisa_kelebihan ?? 0),
            ],
        ];
    }

    /**
     * Statistik transaksi (untuk rekap manapun).
     */
    public function getStatistikTransaksi(array $filters = []): array
    {
        $query = TransaksiPembayaran::query();

        // Filter tanggal
        if (!empty($filters['tanggal_dari'])) {
            $query->whereDate('tanggal_transaksi', '>=', $filters['tanggal_dari']);
        }
        if (!empty($filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_transaksi', '<=', $filters['tanggal_sampai']);
        }

        // Filter sumber_data: mandiri / keluarga / semua
        if (!empty($filters['sumber_data'])) {
            if ($filters['sumber_data'] === 'keluarga') {
                $query->whereHas('jamaah', fn($q) => $q->whereNotNull('id_keluarga'));
            } elseif ($filters['sumber_data'] === 'mandiri') {
                $query->whereHas('jamaah', fn($q) => $q->whereNull('id_keluarga'));
            }
        }

        $masuk = (clone $query)->where('jenis_mutasi', 'masuk')->sum('jumlah_bayar');
        $keluar = (clone $query)->where('jenis_mutasi', 'keluar')->sum('jumlah_bayar');
        $countMasuk = (clone $query)->where('jenis_mutasi', 'masuk')->count();
        $countKeluar = (clone $query)->where('jenis_mutasi', 'keluar')->count();

        return [
            'total_masuk' => (int) $masuk,
            'total_keluar' => (int) $keluar,
            'net' => (int) $masuk - (int) $keluar,
            'count_masuk' => (int) $countMasuk,
            'count_keluar' => (int) $countKeluar,
        ];
    }

    /**
     * REKAP JAMAAH — HANYA jamaah mandiri (id_keluarga IS NULL).
     * Tampil per transaksi, sama seperti Riwayat Pembayaran.
     */
    public function getRekapJamaah(array $filters = [], int $perPage = 20)
    {
        $query = TransaksiPembayaran::with([
                'jamaah',       // ⬅️ TIDAK perlu load keluarga
                'metodePembayaran',
                'jenisPembayaran',
                'jenisTransaksi',
            ])
            ->whereHas('jamaah', fn($q) => $q->whereNull('id_keluarga')) // ✅ HANYA mandiri
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id_transaksi', 'desc');

        // Filter pencarian (nama / NIK / produk)
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->whereHas('jamaah', function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('nomor_paspor', 'like', "%{$s}%")
                  ->orWhere('produk_paket', 'like', "%{$s}%");
            });
        }

        // Filter jenis mutasi
        if (!empty($filters['jenis_mutasi'])) {
            $query->where('jenis_mutasi', $filters['jenis_mutasi']);
        }

        // Filter produk
        if (!empty($filters['produk_paket'])) {
            $query->whereHas('jamaah', fn($q) => $q->where('produk_paket', $filters['produk_paket']));
        }

        // Filter jenis pembayaran
        if (!empty($filters['id_jenis_pembayaran'])) {
            $query->where('id_jenis_pembayaran', $filters['id_jenis_pembayaran']);
        }

        // Filter metode pembayaran
        if (!empty($filters['id_metode_pembayaran'])) {
            $query->where('id_metode_pembayaran', $filters['id_metode_pembayaran']);
        }

        // Filter tanggal transaksi
        if (!empty($filters['tanggal_dari'])) {
            $query->whereDate('tanggal_transaksi', '>=', $filters['tanggal_dari']);
        }
        if (!empty($filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_transaksi', '<=', $filters['tanggal_sampai']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * REKAP KELUARGA — HANYA jamaah anggota keluarga (id_keluarga IS NOT NULL).
     * Tampil per transaksi.
     */
    public function getRekapKeluarga(array $filters = [], int $perPage = 20)
    {
        $query = TransaksiPembayaran::with([
                'jamaah.keluarga',
                'metodePembayaran',
                'jenisPembayaran',
                'jenisTransaksi',
            ])
            ->whereHas('jamaah', fn($q) => $q->whereNotNull('id_keluarga')) // ✅ HANYA keluarga
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id_transaksi', 'desc');

        // Filter pencarian
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->whereHas('jamaah', function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhereHas('keluarga', function ($qk) use ($s) {
                      $qk->where('nama_keluarga', 'like', "%{$s}%")
                         ->orWhere('kode_keluarga', 'like', "%{$s}%");
                  });
            });
        }

        if (!empty($filters['jenis_mutasi'])) {
            $query->where('jenis_mutasi', $filters['jenis_mutasi']);
        }

        if (!empty($filters['produk_paket'])) {
            $query->whereHas('jamaah', function ($q) use ($filters) {
                $q->where(function ($qq) use ($filters) {
                    $qq->where('produk_paket', $filters['produk_paket'])
                       ->orWhereHas('keluarga', fn($qk) => $qk->where('produk_paket', $filters['produk_paket']));
                });
            });
        }

        if (!empty($filters['id_jenis_pembayaran'])) {
            $query->where('id_jenis_pembayaran', $filters['id_jenis_pembayaran']);
        }

        if (!empty($filters['id_metode_pembayaran'])) {
            $query->where('id_metode_pembayaran', $filters['id_metode_pembayaran']);
        }

        if (!empty($filters['tanggal_dari'])) {
            $query->whereDate('tanggal_transaksi', '>=', $filters['tanggal_dari']);
        }
        if (!empty($filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_transaksi', '<=', $filters['tanggal_sampai']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * REKAP TRANSAKSI — semua transaksi (mandiri + keluarga).
     */
    public function getRekapTransaksi(array $filters = [], int $perPage = 20)
    {
        $query = TransaksiPembayaran::with([
                'jamaah.keluarga',
                'metodePembayaran',
                'jenisPembayaran',
                'jenisTransaksi',
            ])
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id_transaksi', 'desc');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->whereHas('jamaah', function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['jenis_mutasi'])) {
            $query->where('jenis_mutasi', $filters['jenis_mutasi']);
        }

        if (!empty($filters['id_jenis_pembayaran'])) {
            $query->where('id_jenis_pembayaran', $filters['id_jenis_pembayaran']);
        }

        if (!empty($filters['id_metode_pembayaran'])) {
            $query->where('id_metode_pembayaran', $filters['id_metode_pembayaran']);
        }

        if (!empty($filters['tanggal_dari'])) {
            $query->whereDate('tanggal_transaksi', '>=', $filters['tanggal_dari']);
        }
        if (!empty($filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_transaksi', '<=', $filters['tanggal_sampai']);
        }

        if (!empty($filters['sumber_data'])) {
            if ($filters['sumber_data'] === 'keluarga') {
                $query->whereHas('jamaah', fn($q) => $q->whereNotNull('id_keluarga'));
            } elseif ($filters['sumber_data'] === 'mandiri') {
                $query->whereHas('jamaah', fn($q) => $q->whereNull('id_keluarga'));
            }
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Filter options.
     */
    public function getFilterOptions(): array
    {
        return [
            'produk_pakets' => ProdukPaket::orderBy('nama_produk')->pluck('nama_produk')->toArray(),
            'jenis_pembayarans' => JenisPembayaran::orderBy('nama')->get(),
            'metode_pembayarans' => MetodePembayaran::active()->orderBy('nama_bank')->get(),
        ];
    }
}
