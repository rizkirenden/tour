<?php
// app/Services/JamaahService.php

namespace App\Services;

use App\Models\Jamaah;
use App\Models\JamaahUpgradeHotel;
use App\Models\ProdukPaket;
use App\Models\ProdukHargaBulanan;
use App\Models\ProdukKeberangkatan;
use App\Models\KotaAsal;
use App\Models\Diskon;
use App\Models\JenisPembayaran;
use App\Models\TransaksiPembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JamaahService
{
    // ==========================================
    // READ
    // ==========================================
    public function getAll(array $filters = [])
    {
        $query = Jamaah::with([
            'diskon',
            'keluarga',
            'keberangkatan',
            'upgradeHotels.hotel',
            'upgradeHotels.kamar',
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_paspor', 'like', "%{$search}%")
                    ->orWhere('id_keberangkatan', 'like', "%{$search}%")
                    ->orWhere('produk_paket', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('agent_name', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status_pembayaran'])) {
            $query->where('status_pembayaran', $filters['status_pembayaran']);
        }

        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (!empty($filters['status_kawin'])) {
            $query->where('status_kawin', $filters['status_kawin']);
        }

        if (!empty($filters['sumber_data'])) {
            if ($filters['sumber_data'] == 'keluarga') {
                $query->whereNotNull('id_keluarga');
            } elseif ($filters['sumber_data'] == 'jamaah') {
                $query->whereNull('id_keluarga');
            }
        }

        if (!empty($filters['has_kelebihan'])) {
            $query->where('sisa_kelebihan_bayar', '>', 0);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Jamaah::with([
            'diskon',
            'keluarga.upgradeHotels.hotel',
            'keluarga.jamaahs',
            'keberangkatan',
            'upgradeHotels.hotel',
            'upgradeHotels.kamar',
        ])->findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return Jamaah::with([
            'keluarga.upgradeHotels.hotel',
            'keluarga.jamaahs',
            'transaksis.metodePembayaran',
            'transaksis.jenisPembayaran',
            'transaksis.jenisTransaksi',
            'diskon',
            'keberangkatan',
            'upgradeHotels.hotel',
            'upgradeHotels.kamar',
        ])->findOrFail($id);
    }

    // ==========================================
    // CREATE
    // ==========================================
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['id_keberangkatan'])) {
                $produk = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
                $kodeProduk = $produk ? ($produk->kode_produk ?? 'PKT') : 'PKT';
                $data['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk);
            }

            $kota = KotaAsal::where('nama_kota', $data['kota_asal'] ?? null)->first();
            if ($kota) {
                $data['pulau'] = $kota->pulau;
                $data['bandara_keberangkatan'] = $kota->bandara_terdekat;
            }

            $data['agent_name'] = $data['agent_name'] ?? null;
            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);

            $data['is_pembuatan_passport'] = (bool) ($data['is_pembuatan_passport'] ?? false);
            $data['pendampingan_fee'] = (int) ($data['pendampingan_fee'] ?? 0);
            $data['pendampingan_fee_petugas'] = (int) ($data['pendampingan_fee_petugas'] ?? 0);

            if (!$data['is_pembuatan_passport']) {
                $data['pendampingan_fee_petugas'] = 0;
            }

            $data['status_kawin'] = $data['status_kawin'] ?? null;

            $diskonData = null;
            $nilaiDiskon = 0;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskon = $diskonData->nilai_diskon;
                }
            }

            $data['total_dibayar'] = (int) ($data['total_dibayar'] ?? 0);
            $data['total_refund'] = 0;

            $keberangkatanData = $data['keberangkatan_data'] ?? [];
            unset($data['keberangkatan_data']);

            $upgradeHotels = $data['upgrade_hotels'] ?? [];
            unset($data['upgrade_hotels']);

            $this->validateKuota($keberangkatanData);

            if (!empty($keberangkatanData)) {
                $first = $keberangkatanData[0];
                $keberangkatanId = $first['id_produk_keberangkatan'] ?? null;
                if ($keberangkatanId) {
                    $keberangkatanFirst = ProdukKeberangkatan::find($keberangkatanId);
                    if ($keberangkatanFirst) {
                        $data['bulan_keberangkatan'] = (int) $keberangkatanFirst->bulan;
                        $data['tahun_keberangkatan'] = (int) $keberangkatanFirst->tahun;
                        $data['bulan_harga'] = !empty($first['bulan_harga'])
                            ? (int) $first['bulan_harga']
                            : (int) $keberangkatanFirst->bulan;
                        $data['tahun_harga'] = !empty($first['tahun_harga'])
                            ? (int) $first['tahun_harga']
                            : (int) $keberangkatanFirst->tahun;
                    }
                }
            }

            $hargaProduk = 0;
            if (!empty($keberangkatanData)) {
                $hargaProduk = (int) ($keberangkatanData[0]['harga'] ?? 0);
            }

            // ✅ HITUNG TOTAL UPGRADE (PER PAX)
            // Personal jamaah: jumlah_pax selalu 1 per item, tapi kita baca dari input
            $totalUpgrade = 0;
            foreach ($upgradeHotels as $up) {
                $hargaPerPax = (int) ($up['harga_per_pax'] ?? 0);
                $jumlahPax   = (int) ($up['jumlah_pax'] ?? 1);
                $totalUpgrade += $hargaPerPax * $jumlahPax;
            }

            $passportFee = $data['pendampingan_fee'] + $data['pendampingan_fee_petugas'];
            $totalTagihanKotor = $hargaProduk + $passportFee + $totalUpgrade;
            $totalTagihanSetelahDiskon = max(0, $totalTagihanKotor - $nilaiDiskon);

            $data['total_tagihan_sebelum_diskon'] = $totalTagihanKotor;
            $data['nilai_diskon'] = $nilaiDiskon;
            $data['total_diskon'] = $nilaiDiskon;
            $data['total_tagihan_setelah_diskon'] = $totalTagihanSetelahDiskon;

            $sisa = max(0, $totalTagihanSetelahDiskon - $data['total_dibayar']);
            $kelebihan = max(0, $data['total_dibayar'] - $totalTagihanSetelahDiskon);

            $data['sisa_tagihan'] = $sisa;
            $data['sisa_kelebihan_bayar'] = max(0, $kelebihan - (int) ($data['total_refund'] ?? 0));

            if ($sisa <= 0) {
                $data['status_pembayaran'] = 'Lunas';
            } else {
                $data['status_pembayaran'] = $this->determinePaymentStatus(
                    $data['total_dibayar'],
                    $totalTagihanSetelahDiskon
                );
            }

            $jamaah = Jamaah::create($data);

            $this->saveUpgradeHotels($jamaah->id_jamaah, $upgradeHotels);

            if (!empty($keberangkatanData)) {
                $pivotData = [];
                foreach ($keberangkatanData as $item) {
                    $keberangkatanId = $item['id_produk_keberangkatan'] ?? null;
                    if (!$keberangkatanId) continue;

                    $keberangkatan = ProdukKeberangkatan::find($keberangkatanId);
                    if (!$keberangkatan) continue;

                    $bulanHarga = !empty($item['bulan_harga'])
                        ? (int) $item['bulan_harga']
                        : (int) $keberangkatan->bulan;

                    $tahunHarga = !empty($item['tahun_harga'])
                        ? (int) $item['tahun_harga']
                        : (int) $keberangkatan->tahun;

                    $pivotData[$keberangkatanId] = [
                        'id_produk_harga_bulanan' => !empty($item['id_produk_harga_bulanan'])
                            ? $item['id_produk_harga_bulanan']
                            : null,
                        'bulan_keberangkatan' => (int) $keberangkatan->bulan,
                        'tahun_keberangkatan' => (int) $keberangkatan->tahun,
                        'bulan_harga' => $bulanHarga,
                        'tahun_harga' => $tahunHarga,
                        'harga' => (int) ($item['harga'] ?? 0),
                    ];
                }
                if (!empty($pivotData)) {
                    $jamaah->keberangkatan()->attach($pivotData);
                }
            }

            if ($diskonData && $diskonData->is_available) {
                $diskonData->increment('sudah_digunakan');
            }

            if ($jamaah->id_keluarga) {
                $keluargaService = new \App\Services\KeluargaService();
                $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
            }

            return $jamaah->fresh();
        });
    }

    // ==========================================
    // UPDATE
    // ==========================================
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $jamaah = $this->getById($id);

            $hargaProdukLama   = (int) $jamaah->harga_produk;
            $produkLama        = $jamaah->produk_paket;
            $totalTagihanLama  = (int) $jamaah->total_tagihan_setelah_diskon;

            $kota = KotaAsal::where('nama_kota', $data['kota_asal'] ?? null)->first();
            if ($kota) {
                $data['pulau'] = $kota->pulau;
                $data['bandara_keberangkatan'] = $kota->bandara_terdekat;
            }

            $data['agent_name'] = $data['agent_name'] ?? null;
            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);

            $data['is_pembuatan_passport'] = (bool) ($data['is_pembuatan_passport'] ?? false);
            $data['pendampingan_fee'] = (int) ($data['pendampingan_fee'] ?? 0);
            $data['pendampingan_fee_petugas'] = (int) ($data['pendampingan_fee_petugas'] ?? 0);

            if (!$data['is_pembuatan_passport']) {
                $data['pendampingan_fee_petugas'] = 0;
            }

            $data['status_kawin'] = $data['status_kawin'] ?? null;

            $diskonData = null;
            $nilaiDiskon = 0;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskon = $diskonData->nilai_diskon;
                }
            }

            $data['total_dibayar'] = (int) ($data['total_dibayar'] ?? $jamaah->total_dibayar);

            if ($jamaah->id_diskon && $jamaah->id_diskon != ($data['id_diskon'] ?? null)) {
                $oldDiskon = Diskon::find($jamaah->id_diskon);
                if ($oldDiskon) $oldDiskon->decrement('sudah_digunakan');
            }
            if ($diskonData && $diskonData->is_available && $jamaah->id_diskon != ($data['id_diskon'] ?? null)) {
                $diskonData->increment('sudah_digunakan');
            }

            $keberangkatanData = $data['keberangkatan_data'] ?? [];
            unset($data['keberangkatan_data']);

            $upgradeHotels = $data['upgrade_hotels'] ?? [];
            unset($data['upgrade_hotels']);

            $this->validateKuota($keberangkatanData, $id);

            if (!empty($keberangkatanData)) {
                $first = $keberangkatanData[0];
                $keberangkatanId = $first['id_produk_keberangkatan'] ?? null;
                if ($keberangkatanId) {
                    $keberangkatanFirst = ProdukKeberangkatan::find($keberangkatanId);
                    if ($keberangkatanFirst) {
                        $data['bulan_keberangkatan'] = (int) $keberangkatanFirst->bulan;
                        $data['tahun_keberangkatan'] = (int) $keberangkatanFirst->tahun;
                        $data['bulan_harga'] = !empty($first['bulan_harga'])
                            ? (int) $first['bulan_harga']
                            : (int) $keberangkatanFirst->bulan;
                        $data['tahun_harga'] = !empty($first['tahun_harga'])
                            ? (int) $first['tahun_harga']
                            : (int) $keberangkatanFirst->tahun;
                    }
                }
            }

            $hargaProduk = 0;
            if (!empty($keberangkatanData)) {
                $hargaProduk = (int) ($keberangkatanData[0]['harga'] ?? 0);
            }

            // ✅ HITUNG TOTAL UPGRADE (PER PAX)
            $totalUpgrade = 0;
            foreach ($upgradeHotels as $up) {
                $hargaPerPax = (int) ($up['harga_per_pax'] ?? 0);
                $jumlahPax   = (int) ($up['jumlah_pax'] ?? 1);
                $totalUpgrade += $hargaPerPax * $jumlahPax;
            }

            $passportFee = $data['pendampingan_fee'] + $data['pendampingan_fee_petugas'];
            $totalTagihanKotor = $hargaProduk + $passportFee + $totalUpgrade;
            $totalTagihanSetelahDiskon = max(0, $totalTagihanKotor - $nilaiDiskon);

            $data['total_tagihan_sebelum_diskon'] = $totalTagihanKotor;
            $data['nilai_diskon'] = $nilaiDiskon;
            $data['total_diskon'] = $nilaiDiskon;
            $data['total_tagihan_setelah_diskon'] = $totalTagihanSetelahDiskon;

            $totalDibayar = (int) $data['total_dibayar'];
            $totalRefund  = (int) ($jamaah->total_refund ?? 0);

            $sisa = max(0, $totalTagihanSetelahDiskon - $totalDibayar);
            $kelebihan = max(0, $totalDibayar - $totalTagihanSetelahDiskon);

            $data['sisa_tagihan'] = $sisa;
            $data['sisa_kelebihan_bayar'] = max(0, $kelebihan - $totalRefund);

            if ($sisa <= 0) {
                $data['status_pembayaran'] = 'Lunas';
            } else {
                $data['status_pembayaran'] = $this->determinePaymentStatus($totalDibayar, $totalTagihanSetelahDiskon);
            }

            $jamaah->update($data);

            $this->saveUpgradeHotels($jamaah->id_jamaah, $upgradeHotels);

            $produkBaru = $data['produk_paket'] ?? $produkLama;
            $hargaBerubah = ($hargaProduk !== $hargaProdukLama) || ($produkBaru !== $produkLama);

            if (!empty($keberangkatanData)) {
                $pivotData = [];
                foreach ($keberangkatanData as $item) {
                    $keberangkatanId = $item['id_produk_keberangkatan'] ?? null;
                    if (!$keberangkatanId) continue;

                    $keberangkatan = ProdukKeberangkatan::find($keberangkatanId);
                    if (!$keberangkatan) continue;

                    $bulanHarga = !empty($item['bulan_harga'])
                        ? (int) $item['bulan_harga']
                        : (int) $keberangkatan->bulan;

                    $tahunHarga = !empty($item['tahun_harga'])
                        ? (int) $item['tahun_harga']
                        : (int) $keberangkatan->tahun;

                    $pivotData[$keberangkatanId] = [
                        'id_produk_harga_bulanan' => !empty($item['id_produk_harga_bulanan'])
                            ? $item['id_produk_harga_bulanan']
                            : null,
                        'bulan_keberangkatan' => (int) $keberangkatan->bulan,
                        'tahun_keberangkatan' => (int) $keberangkatan->tahun,
                        'bulan_harga' => $bulanHarga,
                        'tahun_harga' => $tahunHarga,
                        'harga' => (int) ($item['harga'] ?? 0),
                    ];
                }
                if (!empty($pivotData)) {
                    $jamaah->keberangkatan()->sync($pivotData);
                } else {
                    $jamaah->keberangkatan()->detach();
                }
            } else {
                if ($hargaBerubah && $jamaah->keberangkatan->isNotEmpty()) {
                    $existingPivot = [];
                    foreach ($jamaah->keberangkatan as $keb) {
                        $existingPivot[$keb->id] = [
                            'id_produk_harga_bulanan' => $keb->pivot->id_produk_harga_bulanan,
                            'bulan_keberangkatan' => $keb->pivot->bulan_keberangkatan,
                            'tahun_keberangkatan' => $keb->pivot->tahun_keberangkatan,
                            'bulan_harga' => $keb->pivot->bulan_harga,
                            'tahun_harga' => $keb->pivot->tahun_harga,
                            'harga' => (int) $hargaProduk,
                        ];
                    }
                    $jamaah->keberangkatan()->sync($existingPivot);
                }
            }

            if ($hargaBerubah) {
                $this->logPerubahanProduk($jamaah, [
                    'produk_lama'   => $produkLama,
                    'produk_baru'   => $produkBaru,
                    'harga_lama'    => $hargaProdukLama,
                    'harga_baru'    => $hargaProduk,
                    'tagihan_lama'  => $totalTagihanLama,
                    'tagihan_baru'  => $totalTagihanSetelahDiskon,
                    'dibayar'       => $totalDibayar,
                    'selisih'       => $totalDibayar - $totalTagihanSetelahDiskon,
                    'kelebihan'     => $kelebihan,
                ]);
            }

            if ($jamaah->id_keluarga) {
                $keluargaService = new \App\Services\KeluargaService();
                $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
            }

            return $jamaah->fresh();
        });
    }

    // ==========================================
    // SAVE UPGRADE HOTELS (PER PAX)
    // ==========================================
    private function saveUpgradeHotels($idJamaah, array $upgradeHotels)
    {
        JamaahUpgradeHotel::where('id_jamaah', $idJamaah)->delete();

        foreach ($upgradeHotels as $item) {
            $idHotel = $item['id_hotel'] ?? null;
            if (!$idHotel) continue;

            $hargaPerPax = (int) ($item['harga_per_pax'] ?? 0);
            $jumlahPax   = (int) ($item['jumlah_pax'] ?? 1);
            $totalHarga  = $hargaPerPax * $jumlahPax;

            JamaahUpgradeHotel::create([
                'id_jamaah'       => $idJamaah,
                'id_keluarga'     => null,
                'id_hotel'        => $idHotel,
                'id_kamar'        => $item['id_kamar'] ?? null,
                'tipe_kamar'      => $item['tipe_kamar'] ?? null,
                'harga_per_pax'   => $hargaPerPax,
                'jumlah_pax'      => $jumlahPax,
                'total_harga'     => $totalHarga,
                'jumlah_kamar'    => $jumlahPax,
                'durasi_menginap' => 1,
                'harga_per_malam' => 0,
                'catatan'         => $item['catatan'] ?? null,
            ]);
        }
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jamaah = $this->getById($id);

            if ($jamaah->id_diskon) {
                $diskon = Diskon::find($jamaah->id_diskon);
                if ($diskon) $diskon->decrement('sudah_digunakan');
            }

            JamaahUpgradeHotel::where('id_jamaah', $id)->delete();

            $keluargaId = $jamaah->id_keluarga;
            $nama = $jamaah->nama_lengkap;
            $jamaah->delete();

            if ($keluargaId) {
                $keluargaService = new \App\Services\KeluargaService();
                $keluargaService->recalculateKeluarga($keluargaId);
            }

            return $nama;
        });
    }

    // ==========================================
    // LOG PERUBAHAN PRODUK / HARGA
    // ==========================================
    private function logPerubahanProduk(Jamaah $jamaah, array $info)
    {
        $dibayar   = (int) $info['dibayar'];
        $tagihan   = (int) $info['tagihan_baru'];
        $selisih   = (int) $info['selisih'];
        $kelebihan = (int) $info['kelebihan'];

        if ($selisih > 0) {
            $status = 'KELEBIHAN BAYAR';
            $pesan  = 'Ada kelebihan bayar sebesar Rp ' . number_format($kelebihan, 0, ',', '.') . ' — bisa diproses refund.';
        } elseif ($selisih < 0) {
            $status = 'SISA TAGIHAN';
            $pesan  = 'Sisa tagihan sebesar Rp ' . number_format(abs($selisih), 0, ',', '.') . '.';
        } else {
            $status = 'PAS';
            $pesan  = 'Pembayaran pas dengan tagihan baru (Lunas).';
        }

        $keterangan = sprintf(
            '[PINDAH PRODUK] %s → %s. Harga: Rp %s → Rp %s. Tagihan: Rp %s → Rp %s. Dibayar: Rp %s. Status: %s. %s',
            $info['produk_lama'] ?? '-',
            $info['produk_baru'] ?? '-',
            number_format((int) $info['harga_lama'], 0, ',', '.'),
            number_format((int) $info['harga_baru'], 0, ',', '.'),
            number_format((int) $info['tagihan_lama'], 0, ',', '.'),
            number_format($tagihan, 0, ',', '.'),
            number_format($dibayar, 0, ',', '.'),
            $status,
            $pesan
        );

        Log::info($keterangan, [
            'id_jamaah' => $jamaah->id_jamaah,
            'info'      => $info,
        ]);
    }

    // ==========================================
    // PROSES REFUND
    // ==========================================
    public function prosesRefund($jamaahId, array $data)
    {
        return DB::transaction(function () use ($jamaahId, $data) {
            $jamaah = $this->getById($jamaahId);

            $jumlahRefund = (int) ($data['jumlah_refund'] ?? 0);
            $maxRefund    = (int) $jamaah->sisa_kelebihan_bayar;

            if ($jumlahRefund <= 0) {
                throw new \Exception('Jumlah refund harus lebih dari 0.');
            }

            if ($jumlahRefund > $maxRefund) {
                throw new \Exception(
                    'Jumlah refund melebihi sisa kelebihan bayar. Maksimal: Rp ' .
                    number_format($maxRefund, 0, ',', '.')
                );
            }

            $jenisRefund = JenisPembayaran::byKode('REFUND')->first();

            if (!$jenisRefund) {
                $jenisRefund = JenisPembayaran::create([
                    'nama'       => 'Refund',
                    'keterangan' => 'Pengembalian dana ke jamaah (kelebihan bayar / pindah produk)',
                ]);
            }

            TransaksiPembayaran::create([
                'id_jamaah'             => $jamaahId,
                'id_metode_pembayaran'  => $data['id_metode_pembayaran'],
                'id_jenis_transaksi'    => null,
                'id_jenis_pembayaran'   => $jenisRefund->id_jenis_pembayaran,
                'tanggal_transaksi'     => $data['tanggal_transaksi'] ?? now()->toDateString(),
                'jumlah_bayar'          => $jumlahRefund,
                'jenis_mutasi'          => 'keluar',
                'bukti_pembayaran'      => $data['bukti_pembayaran'] ?? null,
                'keterangan'            => $data['keterangan'] ?? 'Pengembalian kelebihan bayar',
                'keterangan_refund'     => $data['keterangan_refund'] ?? null,
                'created_by'            => Auth::user()->name ?? 'System',
            ]);

            $totalRefundBaru   = (int) ($jamaah->total_refund ?? 0) + $jumlahRefund;
            $sisaKelebihanBaru = max(0, $jamaah->kelebihan_bayar - $totalRefundBaru);

            $jamaah->update([
                'total_refund'         => $totalRefundBaru,
                'sisa_kelebihan_bayar' => $sisaKelebihanBaru,
            ]);

            return $jamaah->fresh();
        });
    }

    // ==========================================
    // HELPERS
    // ==========================================
    private function validateKuota(array $keberangkatanData, $excludeJamaahId = null)
    {
        foreach ($keberangkatanData as $item) {
            $hargaBulananId = $item['id_produk_harga_bulanan'] ?? null;
            if (!$hargaBulananId) continue;

            $hargaBulanan = ProdukHargaBulanan::find($hargaBulananId);
            if (!$hargaBulanan) continue;
            if ($hargaBulanan->kuota <= 0) continue;

            $query = DB::table('jamaah_keberangkatans')
                ->where('id_produk_harga_bulanan', $hargaBulananId);

            if ($excludeJamaahId) {
                $query->where('id_jamaah', '!=', $excludeJamaahId);
            }

            $terpakai = $query->count();

            if ($terpakai >= $hargaBulanan->kuota) {
                $label = $hargaBulanan->bulan_formatted . ' ' . $hargaBulanan->tahun;
                throw new \Exception(
                    "Kuota untuk harga {$label} sudah PENUH ({$terpakai}/{$hargaBulanan->kuota} orang)."
                );
            }
        }
    }

    private function determinePaymentStatus($totalDibayar, $totalTagihan)
    {
        if ($totalDibayar == 0) return 'Belum Bayar';
        if ($totalDibayar >= $totalTagihan) return 'Lunas';
        if ($totalDibayar >= $totalTagihan * 0.5) return 'Setoran';
        return 'DP';
    }

    private function generateIdKeberangkatan($kodeProduk)
    {
        $year = date('Y');
        $month = date('m');
        $last = Jamaah::where('id_keberangkatan', 'like', "{$kodeProduk}-%")->count();
        $number = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        return "{$kodeProduk}-{$year}{$month}-{$number}";
    }

    public function getProdukPakets()
    {
        return ProdukPaket::orderBy('nama_produk')->get();
    }

    public function getKotaAsals()
    {
        return KotaAsal::orderBy('nama_kota')->get();
    }

    public function getDiskons()
    {
        return Diskon::available()->orderBy('nama_diskon')->get();
    }

    public function getKeberangkatanByProduk($namaProduk, $excludeJamaahId = null)
    {
        $produk = ProdukPaket::where('nama_produk', $namaProduk)->first();
        if (!$produk) {
            return ['keberangkatan' => [], 'harga_bulanan' => []];
        }

        $hargaBulanan = ProdukHargaBulanan::where('produk_paket_id', $produk->id_produk)
            ->where('is_active', true)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function ($h) use ($excludeJamaahId) {
                $query = DB::table('jamaah_keberangkatans')
                    ->where('id_produk_harga_bulanan', $h->id);

                if ($excludeJamaahId) {
                    $query->where('id_jamaah', '!=', $excludeJamaahId);
                }

                $terpakai = $query->count();

                $isFull = $h->kuota > 0 && $terpakai >= $h->kuota;
                $sisa = $h->kuota > 0 ? max(0, $h->kuota - $terpakai) : -1;

                if ($h->kuota <= 0) {
                    $kuotaFormatted = 'Tidak Terbatas';
                } elseif ($isFull) {
                    $kuotaFormatted = 'PENUH (' . $h->kuota . ')';
                } else {
                    $kuotaFormatted = 'Sisa ' . $sisa . '/' . $h->kuota;
                }

                return [
                    'id' => $h->id,
                    'bulan' => (int) $h->bulan,
                    'tahun' => (int) $h->tahun,
                    'bulan_formatted' => $h->bulan_formatted,
                    'harga' => (int) $h->harga,
                    'harga_formatted' => 'Rp ' . number_format($h->harga, 0, ',', '.'),
                    'kuota' => $h->kuota ?? 0,
                    'kuota_terpakai' => $terpakai,
                    'kuota_sisa' => $sisa,
                    'is_full' => $isFull,
                    'kuota_formatted' => $kuotaFormatted,
                ];
            })->values()->toArray();

        $keberangkatan = ProdukKeberangkatan::where('produk_paket_id', $produk->id_produk)
            ->where('is_active', true)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function ($k) use ($hargaBulanan) {
                $hargaDefault = collect($hargaBulanan)->first(function ($h) use ($k) {
                    return $h['bulan'] == $k->bulan && $h['tahun'] == $k->tahun;
                });

                return [
                    'id' => (int) $k->id,
                    'bulan' => (int) $k->bulan,
                    'tahun' => (int) $k->tahun,
                    'bulan_formatted' => $k->bulan_formatted,
                    'keberangkatan_formatted' => $k->keberangkatan_formatted,
                    'harga_default' => $hargaDefault ? (int) $hargaDefault['harga'] : 0,
                    'harga_default_formatted' => $hargaDefault ? $hargaDefault['harga_formatted'] : 'Rp 0',
                    'id_harga_default' => $hargaDefault ? (int) $hargaDefault['id'] : null,
                    'kuota_default' => $hargaDefault ? ($hargaDefault['kuota'] ?? 0) : 0,
                    'kuota_default_formatted' => $hargaDefault ? $hargaDefault['kuota_formatted'] : 'Tidak Terbatas',
                    'kuota_default_is_full' => $hargaDefault ? $hargaDefault['is_full'] : false,
                ];
            })->values()->toArray();

        return [
            'keberangkatan' => $keberangkatan,
            'harga_bulanan' => $hargaBulanan,
        ];
    }
}
