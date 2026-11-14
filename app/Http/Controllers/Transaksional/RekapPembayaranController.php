<?php
// app/Http/Controllers/Transaksional/RekapPembayaranController.php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\RekapPembayaranService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RekapPembayaranController extends Controller
{
    protected $service;

    public function __construct(RekapPembayaranService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $summary = $this->service->getSummaryGlobal();
        return view('rekap-pembayaran.index', compact('summary'));
    }

    public function jamaah(Request $request)
    {
        $filters = $request->only([
            'search', 'jenis_mutasi',
            'produk_paket', 'id_jenis_pembayaran', 'id_metode_pembayaran',
            'tanggal_dari', 'tanggal_sampai',
        ]);

        // Force sumber_data = mandiri untuk rekap jamaah (khusus pendaftaran mandiri)
        $statFilters = array_merge($filters, ['sumber_data' => 'mandiri']);

        $data = $this->service->getRekapJamaah($filters, 20);
        $statistik = $this->service->getStatistikTransaksi($statFilters);
        $filterOptions = $this->service->getFilterOptions();
        $hasDateFilter = !empty($filters['tanggal_dari']) || !empty($filters['tanggal_sampai']);

        return view('rekap-pembayaran.jamaah', compact('data', 'filters', 'statistik', 'filterOptions', 'hasDateFilter'));
    }

    public function keluarga(Request $request)
    {
        $filters = $request->only([
            'search', 'jenis_mutasi', 'produk_paket',
            'id_jenis_pembayaran', 'id_metode_pembayaran',
            'tanggal_dari', 'tanggal_sampai',
        ]);

        // Force sumber_data = keluarga untuk rekap keluarga
        $statFilters = array_merge($filters, ['sumber_data' => 'keluarga']);

        $data = $this->service->getRekapKeluarga($filters, 20);
        $statistik = $this->service->getStatistikTransaksi($statFilters);
        $filterOptions = $this->service->getFilterOptions();
        $hasDateFilter = !empty($filters['tanggal_dari']) || !empty($filters['tanggal_sampai']);

        return view('rekap-pembayaran.keluarga', compact('data', 'filters', 'statistik', 'filterOptions', 'hasDateFilter'));
    }

    public function transaksi(Request $request)
    {
        $filters = $request->only([
            'search', 'jenis_mutasi', 'id_jenis_pembayaran',
            'id_metode_pembayaran', 'tanggal_dari', 'tanggal_sampai',
            'sumber_data',
        ]);

        $data = $this->service->getRekapTransaksi($filters, 20);
        $statistik = $this->service->getStatistikTransaksi($filters);
        $filterOptions = $this->service->getFilterOptions();

        return view('rekap-pembayaran.transaksi', compact('data', 'filters', 'statistik', 'filterOptions'));
    }

    public function exportCsv(Request $request)
    {
        $tipe = $request->input('tipe', 'jamaah');
        $filters = $request->only([
            'search', 'jenis_mutasi', 'sumber_data',
            'produk_paket', 'id_jenis_pembayaran', 'id_metode_pembayaran',
            'tanggal_dari', 'tanggal_sampai',
        ]);

        $filename = 'rekap_' . $tipe . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tipe, $filters) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($tipe === 'keluarga') {
                fputcsv($out, [
                    'Tanggal', 'Keluarga', 'Kode Keluarga', 'Jamaah', 'Produk',
                    'Bank', 'Jenis', 'Mutasi', 'Jumlah', 'Keterangan', 'Dibuat Oleh',
                ]);
            } else {
                fputcsv($out, [
                    'Tanggal', 'Jamaah', 'NIK', 'Produk',
                    'Bank', 'Jenis', 'Mutasi', 'Jumlah', 'Keterangan', 'Dibuat Oleh',
                ]);
            }

            if ($tipe === 'jamaah') {
                $data = $this->service->getRekapJamaah($filters, 99999);
                foreach ($data as $t) {
                    fputcsv($out, [
                        $t->tanggal_transaksi ? $t->tanggal_transaksi->format('Y-m-d') : '-',
                        $t->jamaah->nama_lengkap ?? '-',
                        $t->jamaah->nik ?? '-',
                        $t->jamaah->produk_paket ?? '-',
                        $t->metodePembayaran->nama_bank ?? ($t->metodePembayaran->jenis_pembayaran ?? '-'),
                        $t->jenisPembayaran->nama ?? '-',
                        $t->jenis_mutasi,
                        (int) $t->jumlah_bayar,
                        $t->keterangan ?? '-',
                        $t->created_by ?? '-',
                    ]);
                }
            } elseif ($tipe === 'keluarga') {
                $data = $this->service->getRekapKeluarga($filters, 99999);
                foreach ($data as $t) {
                    fputcsv($out, [
                        $t->tanggal_transaksi ? $t->tanggal_transaksi->format('Y-m-d') : '-',
                        $t->jamaah->keluarga->nama_keluarga ?? '-',
                        $t->jamaah->keluarga->kode_keluarga ?? '-',
                        $t->jamaah->nama_lengkap ?? '-',
                        $t->jamaah->produk_paket ?? '-',
                        $t->metodePembayaran->nama_bank ?? ($t->metodePembayaran->jenis_pembayaran ?? '-'),
                        $t->jenisPembayaran->nama ?? '-',
                        $t->jenis_mutasi,
                        (int) $t->jumlah_bayar,
                        $t->keterangan ?? '-',
                        $t->created_by ?? '-',
                    ]);
                }
            } else {
                fputcsv($out, [
                    'Tanggal', 'Jamaah', 'Keluarga', 'Produk',
                    'Bank', 'Jenis', 'Mutasi', 'Jumlah', 'Keterangan', 'Dibuat Oleh',
                ]);
                $data = $this->service->getRekapTransaksi($filters, 99999);
                foreach ($data as $t) {
                    fputcsv($out, [
                        $t->tanggal_transaksi ? $t->tanggal_transaksi->format('Y-m-d') : '-',
                        $t->jamaah->nama_lengkap ?? '-',
                        $t->jamaah->keluarga->nama_keluarga ?? '-',
                        $t->jamaah->produk_paket ?? '-',
                        $t->metodePembayaran->nama_bank ?? ($t->metodePembayaran->jenis_pembayaran ?? '-'),
                        $t->jenisPembayaran->nama ?? '-',
                        $t->jenis_mutasi,
                        (int) $t->jumlah_bayar,
                        $t->keterangan ?? '-',
                        $t->created_by ?? '-',
                    ]);
                }
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $tipe = $request->input('tipe', 'jamaah');
        $filters = $request->only([
            'search', 'jenis_mutasi', 'sumber_data',
            'produk_paket', 'id_jenis_pembayaran', 'id_metode_pembayaran',
            'tanggal_dari', 'tanggal_sampai',
        ]);

        $summary = $this->service->getSummaryGlobal();
        $statFilters = $filters;
        if ($tipe === 'jamaah') {
            $statFilters['sumber_data'] = 'mandiri';
        } elseif ($tipe === 'keluarga') {
            $statFilters['sumber_data'] = 'keluarga';
        }
        $statistik = $this->service->getStatistikTransaksi($statFilters);
        $hasDateFilter = !empty($filters['tanggal_dari']) || !empty($filters['tanggal_sampai']);

        if ($tipe === 'jamaah') {
            $data = $this->service->getRekapJamaah($filters, 9999);
        } elseif ($tipe === 'keluarga') {
            $data = $this->service->getRekapKeluarga($filters, 9999);
        } else {
            $data = $this->service->getRekapTransaksi($filters, 9999);
        }

        $logoBase64 = $this->loadDokumenBase64('logo');

        $data_pdf = [
            'tipe' => $tipe,
            'data' => $data,
            'filters' => $filters,
            'summary' => $summary,
            'statistik' => $statistik,
            'hasDateFilter' => $hasDateFilter,
            'logoBase64' => $logoBase64,
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
            'dicetak_oleh' => Auth::user()->name ?? 'System',
        ];

        $pdf = Pdf::loadView('rekap-pembayaran.pdf-rekap', $data_pdf)
            ->setPaper('A4', 'landscape');

        return $pdf->download('Rekap_' . ucfirst($tipe) . '_' . date('Ymd_His') . '.pdf');
    }

    private function loadDokumenBase64($jenis)
    {
        $dokumen = \App\Models\DokumenPerusahaan::where('jenis', $jenis)->first();

        if (!$dokumen || !$dokumen->path) {
            if ($jenis == 'logo') {
                $fallbackPath = public_path('assets/logo.PNG');
                if (file_exists($fallbackPath) && filesize($fallbackPath) < 500000) {
                    $data = file_get_contents($fallbackPath);
                    return 'data:image/png;base64,' . base64_encode($data);
                }
            }
            return null;
        }

        $fullPath = storage_path('app/public/' . $dokumen->path);
        if (!file_exists($fullPath)) return null;

        $data = file_get_contents($fullPath);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

        return 'data:' . $mimeType . ';base64,' . base64_encode($data);
    }
}
