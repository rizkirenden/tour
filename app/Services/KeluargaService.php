<?php
// app/Services/KeluargaService.php

namespace App\Services;

use App\Models\Keluarga;
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

class KeluargaService
{
    // ==========================================
    // READ
    // ==========================================
    public function getAll(array $filters = [])
    {
        $query = Keluarga::with(['jamaahs', 'diskon', 'upgradeHotels.hotel']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_keluarga', 'like', "%{$search}%")
                    ->orWhere('kode_keluarga', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status_pembayaran'])) {
            $query->where('status_pembayaran', $filters['status_pembayaran']);
        }

        if (!empty($filters['has_kelebihan'])) {
            $query->where('sisa_kelebihan_bayar', '>', 0);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Keluarga::with([
            'jamaahs.upgradeHotels.hotel',
            'jamaahs.upgradeHotels.kamar',
            'jamaahs.keberangkatan',
            'upgradeHotels.hotel',
            'upgradeHotels.kamar',
            'diskon',
            'produkKeberangkatan',
            'produkHargaBulanan',
        ])->findOrFail($id);
    }

    // ==========================================
    // ✅ HELPER: Normalisasi keberangkatan_data → field tunggal
    // ==========================================
    private function normalizeKeberangkatanData(array &$data): ?array
    {
        $keberangkatanData = $data['keberangkatan_data'] ?? [];
        unset($data['keberangkatan_data']);

        if (empty($keberangkatanData)) {
            return null;
        }

        $primary = $keberangkatanData[0];

        $data['id_produk_keberangkatan'] = $primary['id_produk_keberangkatan'] ?? $data['id_produk_keberangkatan'] ?? null;
        $data['id_produk_harga_bulanan'] = $primary['id_produk_harga_bulanan'] ?? $data['id_produk_harga_bulanan'] ?? null;
        $data['bulan_keberangkatan'] = $primary['bulan_keberangkatan'] ?? $data['bulan_keberangkatan'] ?? null;
        $data['tahun_keberangkatan'] = $primary['tahun_keberangkatan'] ?? $data['tahun_keberangkatan'] ?? null;
        $data['bulan_harga'] = $primary['bulan_harga'] ?? $data['bulan_harga'] ?? null;
        $data['tahun_harga'] = $primary['tahun_harga'] ?? $data['tahun_harga'] ?? null;

        return $primary;
    }

    // ==========================================
    // ✅ HELPER: Build pivot payload dari data
    // ==========================================
    private function buildPivotPayload(array $data, ?array $primary, ProdukKeberangkatan $keberangkatan, int $hargaProduk): array
    {
        return [
            'id_produk_harga_bulanan' => $data['id_produk_harga_bulanan'] ?? null,
            'bulan_keberangkatan' => (int) ($primary['bulan_keberangkatan'] ?? $keberangkatan->bulan),
            'tahun_keberangkatan' => (int) ($primary['tahun_keberangkatan'] ?? $keberangkatan->tahun),
            'bulan_harga' => (int) ($data['bulan_harga'] ?? $keberangkatan->bulan),
            'tahun_harga' => (int) ($data['tahun_harga'] ?? $keberangkatan->tahun),
            'harga' => (int) $hargaProduk,
        ];
    }

    // ==========================================
    // CREATE
    // ==========================================
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['kode_keluarga'] = Keluarga::generateKodeKeluarga();
            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
            $data['total_dibayar'] = (int) ($data['total_dibayar'] ?? 0);
            $data['total_refund'] = 0;

            // ✅ Normalisasi keberangkatan_data
            $primaryKeberangkatan = $this->normalizeKeberangkatanData($data);

            $hargaProduk = 0;
            $hargaBulanan = null;
            $idHargaBulanan = $data['id_produk_harga_bulanan'] ?? null;

            if ($idHargaBulanan) {
                $hargaBulanan = ProdukHargaBulanan::find($idHargaBulanan);
                if ($hargaBulanan) {
                    $hargaProduk = (int) $hargaBulanan->harga;
                    $data['bulan_harga'] = $hargaBulanan->bulan;
                    $data['tahun_harga'] = $hargaBulanan->tahun;
                }
            } else {
                $produkKeluarga = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
                if ($produkKeluarga) {
                    $harga = ProdukHargaBulanan::where('produk_paket_id', $produkKeluarga->id_produk)
                        ->where('bulan', $data['bulan_keberangkatan'])
                        ->where('tahun', $data['tahun_keberangkatan'])
                        ->where('is_active', true)
                        ->first();
                    if ($harga) {
                        $hargaProduk = (int) $harga->harga;
                        $hargaBulanan = $harga;
                        $data['bulan_harga'] = $harga->bulan;
                        $data['tahun_harga'] = $harga->tahun;
                    }
                }
            }

            // ✅ Fallback: kalau primary punya harga sendiri, pakai itu
            if ($primaryKeberangkatan && !empty($primaryKeberangkatan['harga'])) {
                $hargaProduk = (int) $primaryKeberangkatan['harga'];
            }

            $jumlahAnggota = count($data['jamaahs'] ?? []);
            $this->validateKuota($idHargaBulanan, $jumlahAnggota);

            $feeAgentTotal = (int) ($data['fee_agent'] ?? 0);
            $feeAgentPerJamaah = $jumlahAnggota > 0 ? (int) round($feeAgentTotal / $jumlahAnggota) : 0;
            $sisaPembulatanFeeAgent = $feeAgentTotal - ($feeAgentPerJamaah * $jumlahAnggota);

            $totalDiskonKelompok = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $totalDiskonKelompok = (int) $diskonData->nilai_diskon;
                }
            }

            $diskonPerOrang = $jumlahAnggota > 0 ? (int) round($totalDiskonKelompok / $jumlahAnggota) : 0;
            $sisaPembulatanDiskon = (int) ($totalDiskonKelompok - ($diskonPerOrang * $jumlahAnggota));

            // ✅ HITUNG TOTAL UPGRADE KELUARGA (per pax)
            $totalUpgradeKeluarga = 0;
            $upgradeKeluargaData = $data['upgrade_hotels'] ?? [];
            foreach ($upgradeKeluargaData as $up) {
                $hargaPerPax = (int) ($up['harga_per_pax'] ?? 0);
                $jumlahPax   = (int) ($up['jumlah_pax'] ?? $jumlahAnggota);
                $totalUpgradeKeluarga += $hargaPerPax * $jumlahPax;
            }
            $upgradeKeluargaPerJamaah = $jumlahAnggota > 0
                ? (int) round($totalUpgradeKeluarga / $jumlahAnggota)
                : 0;

            $totalTagihanKotor = 0;
            if (!empty($data['jamaahs'])) {
                foreach ($data['jamaahs'] as $j) {
                    $isPassport = !empty($j['is_pembuatan_passport']) && $j['is_pembuatan_passport'] == 1;
                    $feePetugas = $isPassport ? (int) ($j['pendampingan_fee_petugas'] ?? 0) : 0;
                    $passportFee = (int) ($j['pendampingan_fee'] ?? 0) + $feePetugas;

                    // ✅ Personal: selalu 1 pax
                    $jUpgradePersonal = 0;
                    foreach ($j['upgrade_hotels'] ?? [] as $up) {
                        $jUpgradePersonal += (int) ($up['harga_per_pax'] ?? 0);
                    }

                    $totalTagihanKotor += (int) $hargaProduk + (int) $passportFee + (int) $jUpgradePersonal + (int) $upgradeKeluargaPerJamaah;
                }
            }

            $totalTagihanSetelahDiskon = max(0, $totalTagihanKotor - $totalDiskonKelompok);

            $data['total_tagihan_sebelum_diskon'] = (int) $totalTagihanKotor;
            $data['nilai_diskon'] = (int) $diskonPerOrang;
            $data['total_diskon'] = (int) $totalDiskonKelompok;
            $data['total_tagihan_setelah_diskon'] = (int) $totalTagihanSetelahDiskon;

            $sisa = max(0, $totalTagihanSetelahDiskon - $data['total_dibayar']);
            $kelebihan = max(0, $data['total_dibayar'] - $totalTagihanSetelahDiskon);

            $data['sisa_tagihan'] = (int) $sisa;
            $data['sisa_kelebihan_bayar'] = (int) $kelebihan;
            $data['status_pembayaran'] = $this->determinePaymentStatus(
                $data['total_dibayar'],
                $totalTagihanSetelahDiskon
            );

            unset($data['upgrade_hotels']);
            $keluarga = Keluarga::create($data);

            $this->saveUpgradeHotelsKeluarga($keluarga->id_keluarga, $upgradeKeluargaData);

            if ($diskonData && $diskonData->is_available) {
                $diskonData->increment('sudah_digunakan');
            }

            if (!empty($data['jamaahs'])) {
                foreach ($data['jamaahs'] as $index => $jamaahData) {
                    $upgradeJamaahData = $jamaahData['upgrade_hotels'] ?? [];
                    unset($jamaahData['upgrade_hotels']);

                    $jamaahData['id_keluarga'] = $keluarga->id_keluarga;
                    $jamaahData['is_kepala_keluarga'] = $jamaahData['is_kepala_keluarga'] ?? false;
                    $jamaahData['produk_paket'] = $data['produk_paket'];

                    $feeAgentJamaah = $feeAgentPerJamaah;
                    if ($index === 0 && $sisaPembulatanFeeAgent != 0) {
                        $feeAgentJamaah += $sisaPembulatanFeeAgent;
                    }
                    $jamaahData['fee_agent'] = $feeAgentJamaah;
                    $jamaahData['agent_name'] = $data['agent'] ?? null;

                    $jamaahData['status_kawin'] = $jamaahData['status_kawin'] ?? null;

                    $jamaahData['is_pembuatan_passport'] = (bool) ($jamaahData['is_pembuatan_passport'] ?? false);
                    $jamaahData['pendampingan_fee'] = (int) ($jamaahData['pendampingan_fee'] ?? 0);
                    $jamaahData['pendampingan_fee_petugas'] = (int) ($jamaahData['pendampingan_fee_petugas'] ?? 0);

                    if (!$jamaahData['is_pembuatan_passport']) {
                        $jamaahData['pendampingan_fee_petugas'] = 0;
                    }

                    $jamaahData = $this->handleFileUploads($jamaahData, $index);
                    $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan('PKT', $index);

                    $jamaahData['bulan_keberangkatan'] = $data['bulan_keberangkatan'];
                    $jamaahData['tahun_keberangkatan'] = $data['tahun_keberangkatan'];

                    if (!empty($data['bulan_harga'])) {
                        $jamaahData['bulan_harga'] = $data['bulan_harga'];
                        $jamaahData['tahun_harga'] = $data['tahun_harga'];
                    }
                    if (!empty($data['id_produk_keberangkatan'])) {
                        $jamaahData['id_produk_keberangkatan'] = $data['id_produk_keberangkatan'];
                    }
                    if (!empty($data['id_produk_harga_bulanan'])) {
                        $jamaahData['id_produk_harga_bulanan'] = $data['id_produk_harga_bulanan'];
                    }

                    $passportFeeJamaah = (int) $jamaahData['pendampingan_fee'] + (int) $jamaahData['pendampingan_fee_petugas'];

                    // ✅ Personal: selalu 1 pax
                    $jUpgradePersonal = 0;
                    foreach ($upgradeJamaahData as $up) {
                        $jUpgradePersonal += (int) ($up['harga_per_pax'] ?? 0);
                    }

                    $totalTagihanJamaahKotor = (int) $hargaProduk + (int) $passportFeeJamaah + (int) $jUpgradePersonal + (int) $upgradeKeluargaPerJamaah;

                    $diskonJamaah = (int) $diskonPerOrang;
                    if ($index === 0 && $sisaPembulatanDiskon != 0) {
                        $diskonJamaah += (int) $sisaPembulatanDiskon;
                    }

                    $tagihanSetelahDiskonJamaah = max(0, $totalTagihanJamaahKotor - $diskonJamaah);

                    $jamaahData['total_tagihan_sebelum_diskon'] = (int) $totalTagihanJamaahKotor;
                    $jamaahData['nilai_diskon'] = (int) $diskonPerOrang;
                    $jamaahData['total_diskon'] = (int) $diskonJamaah;
                    $jamaahData['total_tagihan_setelah_diskon'] = (int) $tagihanSetelahDiskonJamaah;
                    $jamaahData['total_dibayar'] = 0;
                    $jamaahData['total_refund'] = 0;
                    $jamaahData['sisa_kelebihan_bayar'] = 0;
                    $jamaahData['sisa_tagihan'] = (int) $tagihanSetelahDiskonJamaah;
                    $jamaahData['status_pembayaran'] = 'Belum Bayar';

                    $jamaah = Jamaah::create($jamaahData);

                    $this->saveUpgradeHotelsJamaah($jamaah->id_jamaah, $upgradeJamaahData);

                    if (!empty($data['id_produk_keberangkatan'])) {
                        $keberangkatan = ProdukKeberangkatan::find($data['id_produk_keberangkatan']);
                        if ($keberangkatan) {
                            $jamaah->keberangkatan()->attach($keberangkatan->id, [
                                'id_produk_harga_bulanan' => $idHargaBulanan,
                                'bulan_keberangkatan' => $keberangkatan->bulan,
                                'tahun_keberangkatan' => $keberangkatan->tahun,
                                'bulan_harga' => $hargaBulanan->bulan ?? $keberangkatan->bulan,
                                'tahun_harga' => $hargaBulanan->tahun ?? $keberangkatan->tahun,
                                'harga' => (int) $hargaProduk,
                            ]);
                        }
                    }
                }
            }

            $this->recalculateKeluarga($keluarga->id_keluarga);

            return $keluarga->fresh();
        });
    }

    // ==========================================
    // UPDATE
    // ==========================================
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $keluarga = $this->getById($id);

            // ✅ Normalisasi keberangkatan_data (override field tunggal)
            $primaryKeberangkatan = $this->normalizeKeberangkatanData($data);

            $hargaProduk = 0;
            $hargaBulanan = null;
            $idHargaBulanan = $data['id_produk_harga_bulanan'] ?? null;

            if ($idHargaBulanan) {
                $hargaBulanan = ProdukHargaBulanan::find($idHargaBulanan);
                if ($hargaBulanan) {
                    $hargaProduk = (int) $hargaBulanan->harga;
                    $data['bulan_harga'] = $hargaBulanan->bulan;
                    $data['tahun_harga'] = $hargaBulanan->tahun;
                }
            } else {
                $produkKeluarga = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
                if ($produkKeluarga) {
                    $harga = ProdukHargaBulanan::where('produk_paket_id', $produkKeluarga->id_produk)
                        ->where('bulan', $data['bulan_keberangkatan'])
                        ->where('tahun', $data['tahun_keberangkatan'])
                        ->where('is_active', true)
                        ->first();
                    if ($harga) {
                        $hargaProduk = (int) $harga->harga;
                        $hargaBulanan = $harga;
                        $data['bulan_harga'] = $harga->bulan;
                        $data['tahun_harga'] = $harga->tahun;
                    }
                }
            }

            // ✅ Fallback: kalau primary punya harga sendiri, pakai itu
            if ($primaryKeberangkatan && !empty($primaryKeberangkatan['harga'])) {
                $hargaProduk = (int) $primaryKeberangkatan['harga'];
            }

            $jamaahBaru = 0;
            if (!empty($data['jamaahs'])) {
                foreach ($data['jamaahs'] as $j) {
                    if (empty($j['id_jamaah'])) $jamaahBaru++;
                }
            }
            $excludeJamaahIds = $keluarga->jamaahs->pluck('id_jamaah')->toArray();
            if ($jamaahBaru > 0) {
                $this->validateKuota($idHargaBulanan, $jamaahBaru, $excludeJamaahIds);
            }

            $totalDiskonKelompok = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $totalDiskonKelompok = (int) $diskonData->nilai_diskon;
                }
            }

            $jumlahJamaah = collect($data['jamaahs'] ?? [])->count();
            $diskonPerOrang = $jumlahJamaah > 0 ? (int) round($totalDiskonKelompok / $jumlahJamaah) : 0;
            $sisaPembulatanDiskon = (int) ($totalDiskonKelompok - ($diskonPerOrang * $jumlahJamaah));

            $feeAgentTotal = (int) ($data['fee_agent'] ?? 0);
            $feeAgentPerJamaah = $jumlahJamaah > 0 ? (int) round($feeAgentTotal / $jumlahJamaah) : 0;
            $sisaPembulatanFeeAgent = $feeAgentTotal - ($feeAgentPerJamaah * $jumlahJamaah);

            // ✅ HITUNG TOTAL UPGRADE KELUARGA (per pax)
            $totalUpgradeKeluarga = 0;
            $upgradeKeluargaData = $data['upgrade_hotels'] ?? [];
            foreach ($upgradeKeluargaData as $up) {
                $hargaPerPax = (int) ($up['harga_per_pax'] ?? 0);
                $jumlahPax   = (int) ($up['jumlah_pax'] ?? $jumlahJamaah);
                $totalUpgradeKeluarga += $hargaPerPax * $jumlahPax;
            }
            $upgradeKeluargaPerJamaah = $jumlahJamaah > 0
                ? (int) round($totalUpgradeKeluarga / $jumlahJamaah)
                : 0;

            $data['fee_agent'] = $feeAgentTotal;
            $data['nilai_diskon'] = (int) $diskonPerOrang;
            $data['total_diskon'] = (int) $totalDiskonKelompok;
            unset($data['upgrade_hotels']);

            $oldDiskonId = $keluarga->id_diskon;
            $keluarga->update($data);

            $this->saveUpgradeHotelsKeluarga($keluarga->id_keluarga, $upgradeKeluargaData);

            if ($oldDiskonId && $oldDiskonId != ($data['id_diskon'] ?? null)) {
                $oldDiskon = Diskon::find($oldDiskonId);
                if ($oldDiskon) $oldDiskon->decrement('sudah_digunakan');
            }
            if ($diskonData && $diskonData->is_available && $oldDiskonId != ($data['id_diskon'] ?? null)) {
                $diskonData->increment('sudah_digunakan');
            }

            if (!empty($data['jamaahs'])) {
                $existingIds = collect($data['jamaahs'])->pluck('id_jamaah')->filter()->toArray();
                $keluarga->jamaahs()->whereNotIn('id_jamaah', $existingIds)->delete();

                foreach ($data['jamaahs'] as $index => $jamaahData) {
                    $upgradeJamaahData = $jamaahData['upgrade_hotels'] ?? [];
                    unset($jamaahData['upgrade_hotels']);

                    $jamaahData['id_keluarga'] = $keluarga->id_keluarga;
                    $jamaahData['produk_paket'] = $data['produk_paket'];
                    $jamaahData['nilai_diskon'] = (int) $diskonPerOrang;

                    $feeAgentJamaah = $feeAgentPerJamaah;
                    if ($index === 0 && $sisaPembulatanFeeAgent != 0) {
                        $feeAgentJamaah += $sisaPembulatanFeeAgent;
                    }
                    $jamaahData['fee_agent'] = $feeAgentJamaah;
                    $jamaahData['agent_name'] = $data['agent'] ?? null;

                    $jamaahData['status_kawin'] = $jamaahData['status_kawin'] ?? null;

                    $jamaahData['is_pembuatan_passport'] = (bool) ($jamaahData['is_pembuatan_passport'] ?? false);
                    $jamaahData['pendampingan_fee'] = (int) ($jamaahData['pendampingan_fee'] ?? 0);
                    $jamaahData['pendampingan_fee_petugas'] = (int) ($jamaahData['pendampingan_fee_petugas'] ?? 0);

                    if (!$jamaahData['is_pembuatan_passport']) {
                        $jamaahData['pendampingan_fee_petugas'] = 0;
                    }

                    $jamaahData = $this->handleFileUploads($jamaahData, $index, $jamaahData['id_jamaah'] ?? null);

                    $jamaahData['bulan_keberangkatan'] = $data['bulan_keberangkatan'];
                    $jamaahData['tahun_keberangkatan'] = $data['tahun_keberangkatan'];

                    if (!empty($data['bulan_harga'])) {
                        $jamaahData['bulan_harga'] = $data['bulan_harga'];
                        $jamaahData['tahun_harga'] = $data['tahun_harga'];
                    }
                    if (!empty($data['id_produk_keberangkatan'])) {
                        $jamaahData['id_produk_keberangkatan'] = $data['id_produk_keberangkatan'];
                    }
                    if (!empty($data['id_produk_harga_bulanan'])) {
                        $jamaahData['id_produk_harga_bulanan'] = $data['id_produk_harga_bulanan'];
                    }

                    $passportFeeJamaah = (int) $jamaahData['pendampingan_fee'] + (int) $jamaahData['pendampingan_fee_petugas'];

                    // ✅ Personal: selalu 1 pax
                    $jUpgradePersonal = 0;
                    foreach ($upgradeJamaahData as $up) {
                        $jUpgradePersonal += (int) ($up['harga_per_pax'] ?? 0);
                    }

                    $totalTagihanJamaahKotor = (int) $hargaProduk + (int) $passportFeeJamaah + (int) $jUpgradePersonal + (int) $upgradeKeluargaPerJamaah;

                    $diskonJamaah = (int) $diskonPerOrang;
                    if ($index === 0 && $sisaPembulatanDiskon != 0) {
                        $diskonJamaah += (int) $sisaPembulatanDiskon;
                    }

                    $tagihanSetelahDiskonJamaah = max(0, $totalTagihanJamaahKotor - $diskonJamaah);

                    if (empty($jamaahData['id_jamaah'])) {
                        $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan('PKT', $index);
                        $jamaahData['total_tagihan_sebelum_diskon'] = (int) $totalTagihanJamaahKotor;
                        $jamaahData['total_diskon'] = (int) $diskonJamaah;
                        $jamaahData['total_tagihan_setelah_diskon'] = (int) $tagihanSetelahDiskonJamaah;
                        $jamaahData['total_dibayar'] = 0;
                        $jamaahData['total_refund'] = 0;
                        $jamaahData['sisa_kelebihan_bayar'] = 0;
                        $jamaahData['sisa_tagihan'] = (int) $tagihanSetelahDiskonJamaah;
                        $jamaahData['status_pembayaran'] = 'Belum Bayar';

                        $jamaah = Jamaah::create($jamaahData);
                        $this->saveUpgradeHotelsJamaah($jamaah->id_jamaah, $upgradeJamaahData);

                        if (!empty($data['id_produk_keberangkatan'])) {
                            $keberangkatan = ProdukKeberangkatan::find($data['id_produk_keberangkatan']);
                            if ($keberangkatan) {
                                $pivotPayload = $this->buildPivotPayload($data, $primaryKeberangkatan, $keberangkatan, $hargaProduk);
                                $jamaah->keberangkatan()->attach($keberangkatan->id, $pivotPayload);
                            }
                        }
                    } else {
                        $jamaah = Jamaah::find($jamaahData['id_jamaah']);
                        if ($jamaah) {
                            $sisaTagihanBaru = max(0, $tagihanSetelahDiskonJamaah - (int) $jamaah->total_dibayar);
                            $totalRefund = (int) ($jamaah->total_refund ?? 0);
                            $kelebihanBaru = max(0, (int) $jamaah->total_dibayar - $tagihanSetelahDiskonJamaah);

                            if ($sisaTagihanBaru <= 0 && $jamaah->total_dibayar > 0) {
                                $statusBaru = 'Lunas';
                            } elseif ($jamaah->total_dibayar == 0) {
                                $statusBaru = 'Belum Bayar';
                            } elseif ($jamaah->total_dibayar >= $tagihanSetelahDiskonJamaah * 0.5) {
                                $statusBaru = 'Setoran';
                            } else {
                                $statusBaru = 'DP';
                            }

                            $jamaahData['total_tagihan_sebelum_diskon'] = (int) $totalTagihanJamaahKotor;
                            $jamaahData['nilai_diskon'] = (int) $diskonPerOrang;
                            $jamaahData['total_diskon'] = (int) $diskonJamaah;
                            $jamaahData['total_tagihan_setelah_diskon'] = (int) $tagihanSetelahDiskonJamaah;
                            $jamaahData['sisa_tagihan'] = (int) $sisaTagihanBaru;
                            $jamaahData['sisa_kelebihan_bayar'] = max(0, $kelebihanBaru - $totalRefund);
                            $jamaahData['status_pembayaran'] = $statusBaru;

                            $jamaah->update($jamaahData);
                            $this->saveUpgradeHotelsJamaah($jamaah->id_jamaah, $upgradeJamaahData);

                            if (!empty($data['id_produk_keberangkatan'])) {
                                $keberangkatan = ProdukKeberangkatan::find($data['id_produk_keberangkatan']);
                                if ($keberangkatan) {
                                    $pivotPayload = $this->buildPivotPayload($data, $primaryKeberangkatan, $keberangkatan, $hargaProduk);
                                    $jamaah->keberangkatan()->sync([$keberangkatan->id => $pivotPayload]);
                                }
                            } else {
                                $jamaah->keberangkatan()->detach();
                            }
                        }
                    }
                }
            }

            $this->recalculateKeluarga($keluarga->id_keluarga);
            return $keluarga->fresh();
        });
    }

    // ==========================================
    // SAVE UPGRADE HOTELS (PER PAX)
    // ==========================================
    private function saveUpgradeHotelsKeluarga($idKeluarga, array $upgradeHotels)
    {
        JamaahUpgradeHotel::where('id_keluarga', $idKeluarga)
            ->whereNull('id_jamaah')
            ->delete();

        $jumlahJamaah = (int) \App\Models\Jamaah::where('id_keluarga', $idKeluarga)->count();
        $jumlahPax = max(1, $jumlahJamaah);

        foreach ($upgradeHotels as $item) {
            $idHotel = $item['id_hotel'] ?? null;
            if (!$idHotel) continue;

            $hargaPerPax = (int) ($item['harga_per_pax'] ?? 0);
            $totalHarga  = $hargaPerPax * $jumlahPax;

            JamaahUpgradeHotel::create([
                'id_keluarga'     => $idKeluarga,
                'id_jamaah'       => null,
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

    private function saveUpgradeHotelsJamaah($idJamaah, array $upgradeHotels)
    {
        JamaahUpgradeHotel::where('id_jamaah', $idJamaah)->delete();

        foreach ($upgradeHotels as $item) {
            $idHotel = $item['id_hotel'] ?? null;
            if (!$idHotel) continue;

            $hargaPerPax = (int) ($item['harga_per_pax'] ?? 0);
            $jumlahPax   = 1;
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
                'jumlah_kamar'    => 1,
                'durasi_menginap' => 1,
                'harga_per_malam' => 0,
                'catatan'         => $item['catatan'] ?? null,
            ]);
        }
    }

    private function validateKuota($idHargaBulanan, $jumlahOrang, $excludeJamaahIds = [])
    {
        if (!$idHargaBulanan) return;

        $hargaBulanan = ProdukHargaBulanan::find($idHargaBulanan);
        if (!$hargaBulanan) return;
        if ($hargaBulanan->kuota <= 0) return;

        $query = DB::table('jamaah_keberangkatans')
            ->where('id_produk_harga_bulanan', $idHargaBulanan);

        if (!empty($excludeJamaahIds)) {
            $query->whereNotIn('id_jamaah', $excludeJamaahIds);
        }

        $terpakai = $query->count();
        $sisa = max(0, $hargaBulanan->kuota - $terpakai);
        $label = $hargaBulanan->bulan_formatted . ' ' . $hargaBulanan->tahun;

        if ($sisa <= 0) {
            throw new \Exception(
                "Kuota untuk harga {$label} sudah PENUH ({$terpakai}/{$hargaBulanan->kuota} orang)."
            );
        }
        if ($jumlahOrang > $sisa) {
            throw new \Exception(
                "Kuota tidak mencukupi untuk harga {$label}. Sisa: {$sisa} orang, diminta: {$jumlahOrang} orang."
            );
        }
    }

    private function handleFileUploads(array $jamaahData, $index, $idJamaah = null)
    {
        $fileFields = [
            'file_ktp_kk' => 'ktp',
            'file_vaksin' => 'vaksin',
            'file_visa' => 'visa',
            'file_paspor' => 'paspor'
        ];

        foreach ($fileFields as $field => $label) {
            if (isset($jamaahData[$field]) && $jamaahData[$field] instanceof \Illuminate\Http\UploadedFile) {
                $file = $jamaahData[$field];
                $filename = time() . '_' . $index . '_' . $label . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
                $jamaahData[$field] = $path;
            } else {
                unset($jamaahData[$field]);
            }
        }

        return $jamaahData;
    }

    public function cleanupUploadedFiles(array $validated)
    {
        if (empty($validated['jamaahs'])) return;

        foreach ($validated['jamaahs'] as $jamaah) {
            foreach (['file_ktp_kk', 'file_vaksin', 'file_visa', 'file_paspor'] as $field) {
                if (!empty($jamaah[$field]) && is_string($jamaah[$field]) && Storage::disk('public')->exists($jamaah[$field])) {
                    Storage::disk('public')->delete($jamaah[$field]);
                }
            }
        }
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $keluarga = $this->getById($id);

            if ($keluarga->id_diskon) {
                $diskon = Diskon::find($keluarga->id_diskon);
                if ($diskon) $diskon->decrement('sudah_digunakan');
            }

            JamaahUpgradeHotel::where('id_keluarga', $keluarga->id_keluarga)->delete();
            $keluarga->jamaahs()->delete();
            $nama = $keluarga->nama_keluarga;
            $keluarga->delete();

            return $nama;
        });
    }

    // ==========================================
    // RECALCULATE
    // ==========================================
    public function recalculateKeluarga($keluargaId)
    {
        $keluarga = $this->getById($keluargaId);
        $jamaahs = $keluarga->jamaahs;

        $totalUpgradeKeluarga = $keluarga->upgradeHotels->sum('total_harga');
        $jumlahJamaah = $jamaahs->count();
        $upgradePerJamaah = $jumlahJamaah > 0 ? (int) round($totalUpgradeKeluarga / $jumlahJamaah) : 0;

        foreach ($jamaahs as $jamaah) {
            $upgradePersonal = $jamaah->upgradeHotels->sum('total_harga');
            $totalUpgradeJamaah = (int) $upgradePersonal + (int) $upgradePerJamaah;

            $baseKotor = (int) $jamaah->total_tagihan_sebelum_diskon - ((int) $jamaah->upgradeHotels->sum('total_harga') + (int) $upgradePerJamaah);
            $totalKotor = (int) $baseKotor + (int) $totalUpgradeJamaah;
            $setelahDiskon = max(0, $totalKotor - (int) $jamaah->total_diskon);

            $sisa = max(0, $setelahDiskon - (int) $jamaah->total_dibayar);
            $totalRefundJamaah = (int) ($jamaah->total_refund ?? 0);
            $kelebihanJamaah = max(0, (int) $jamaah->total_dibayar - $setelahDiskon);

            $status = $this->determinePaymentStatus((int) $jamaah->total_dibayar, (int) $setelahDiskon);

            $jamaah->update([
                'total_tagihan_sebelum_diskon' => (int) $totalKotor,
                'total_tagihan_setelah_diskon' => (int) $setelahDiskon,
                'sisa_tagihan' => (int) $sisa,
                'sisa_kelebihan_bayar' => max(0, $kelebihanJamaah - $totalRefundJamaah),
                'status_pembayaran' => $status,
            ]);
        }

        $keluarga = $this->getById($keluargaId);
        $jamaahs = $keluarga->jamaahs;

        $totalKotor = (int) $jamaahs->sum('total_tagihan_sebelum_diskon');
        $totalDibayar = (int) $jamaahs->sum('total_dibayar');
        $totalRefund = (int) $jamaahs->sum('total_refund');
        $totalDiskon = (int) $jamaahs->sum('total_diskon');
        $totalSetelahDiskon = max(0, $totalKotor - $totalDiskon);

        $sisaTagihan = max(0, $totalSetelahDiskon - $totalDibayar);
        $kelebihanBayar = max(0, $totalDibayar - $totalSetelahDiskon);
        $diskonPerOrang = $jamaahs->count() > 0 ? (int) round($totalDiskon / $jamaahs->count()) : 0;

        $keluarga->update([
            'total_tagihan_sebelum_diskon' => (int) $totalKotor,
            'nilai_diskon' => (int) $diskonPerOrang,
            'total_diskon' => (int) $totalDiskon,
            'total_tagihan_setelah_diskon' => (int) $totalSetelahDiskon,
            'total_dibayar' => (int) $totalDibayar,
            'total_refund' => (int) $totalRefund,
            'sisa_kelebihan_bayar' => max(0, $kelebihanBayar - $totalRefund),
            'sisa_tagihan' => (int) $sisaTagihan,
            'status_pembayaran' => $this->determinePaymentStatus($totalDibayar, $totalSetelahDiskon),
        ]);
    }

    private function determinePaymentStatus($totalDibayar, $totalTagihan)
    {
        if ($totalDibayar == 0) return 'Belum Bayar';
        if ($totalDibayar >= $totalTagihan) return 'Lunas';
        if ($totalDibayar >= $totalTagihan * 0.5) return 'Setoran';
        return 'DP';
    }

    private function generateIdKeberangkatan($kodeProduk, $index)
    {
        $year = date('Y');
        $month = date('m');
        $index = (int) $index;
        $number = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
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

    public function getHubunganOptions()
    {
        return [
            'Kepala Keluarga', 'Istri', 'Suami', 'Anak', 'Orang Tua',
            'Saudara', 'Mertua', 'Cucu', 'Keponakan', 'Lainnya'
        ];
    }

    public function getDiskons()
    {
        return Diskon::available()->orderBy('nama_diskon')->get();
    }

    public function getKeberangkatanByProduk($namaProduk, $excludeJamaahIds = [])
    {
        $produk = ProdukPaket::where('nama_produk', $namaProduk)->first();
        if (!$produk) return ['keberangkatan' => [], 'harga_bulanan' => []];

        $hargaBulanan = ProdukHargaBulanan::where('produk_paket_id', $produk->id_produk)
            ->where('is_active', true)
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(function ($h) use ($excludeJamaahIds) {
                $query = DB::table('jamaah_keberangkatans')
                    ->where('id_produk_harga_bulanan', $h->id);

                if (!empty($excludeJamaahIds)) {
                    $query->whereNotIn('id_jamaah', $excludeJamaahIds);
                }

                $terpakai = $query->count();
                $isFull = $h->kuota > 0 && $terpakai >= $h->kuota;
                $sisa = $h->kuota > 0 ? max(0, $h->kuota - $terpakai) : -1;

                if ($h->kuota <= 0) $kuotaFormatted = 'Tidak Terbatas';
                elseif ($isFull) $kuotaFormatted = 'PENUH (' . $h->kuota . ')';
                else $kuotaFormatted = 'Sisa ' . $sisa . '/' . $h->kuota;

                return [
                    'id' => $h->id, 'bulan' => $h->bulan, 'tahun' => $h->tahun,
                    'bulan_formatted' => $h->bulan_formatted,
                    'harga' => (int) $h->harga,
                    'harga_formatted' => 'Rp ' . number_format($h->harga, 0, ',', '.'),
                    'kuota' => $h->kuota ?? 0, 'kuota_terpakai' => $terpakai,
                    'kuota_sisa' => $sisa, 'is_full' => $isFull,
                    'kuota_formatted' => $kuotaFormatted,
                ];
            })->values()->toArray();

        $keberangkatan = ProdukKeberangkatan::where('produk_paket_id', $produk->id_produk)
            ->where('is_active', true)
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(function ($k) use ($hargaBulanan) {
                $hargaDefault = collect($hargaBulanan)->first(function ($h) use ($k) {
                    return $h['bulan'] == $k->bulan && $h['tahun'] == $k->tahun;
                });
                return [
                    'id' => $k->id, 'bulan' => $k->bulan, 'tahun' => $k->tahun,
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

        return ['keberangkatan' => $keberangkatan, 'harga_bulanan' => $hargaBulanan];
    }

    public function prosesRefund($keluargaId, array $data)
    {
        return DB::transaction(function () use ($keluargaId, $data) {
            $keluarga = $this->getById($keluargaId);

            $jumlahRefund = (int) ($data['jumlah_refund'] ?? 0);
            $maxRefund    = (int) $keluarga->sisa_kelebihan_bayar;

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

            $jamaahs = $keluarga->jamaahs;
            $totalTagihanKeluarga = (int) $keluarga->total_tagihan_setelah_diskon;

            $refundPerJamaah = [];
            $sisaRefund = $jumlahRefund;

            foreach ($jamaahs as $idx => $jamaah) {
                if ($idx === $jamaahs->count() - 1) {
                    $nominal = $sisaRefund;
                } else {
                    $proporsi = $totalTagihanKeluarga > 0
                        ? $jamaah->total_tagihan_setelah_diskon / $totalTagihanKeluarga
                        : 1 / $jamaahs->count();
                    $nominal = (int) round($jumlahRefund * $proporsi);
                    $sisaRefund -= $nominal;
                }
                $refundPerJamaah[] = [
                    'jamaah' => $jamaah,
                    'nominal' => max(0, $nominal),
                ];
            }

            foreach ($refundPerJamaah as $item) {
                if ($item['nominal'] <= 0) continue;
                $jamaah = $item['jamaah'];

                TransaksiPembayaran::create([
                    'id_jamaah'            => $jamaah->id_jamaah,
                    'id_metode_pembayaran' => $data['id_metode_pembayaran'],
                    'id_jenis_transaksi'   => null,
                    'id_jenis_pembayaran'  => $jenisRefund->id_jenis_pembayaran,
                    'tanggal_transaksi'    => $data['tanggal_transaksi'] ?? now()->toDateString(),
                    'jumlah_bayar'         => $item['nominal'],
                    'jenis_mutasi'         => 'keluar',
                    'bukti_pembayaran'     => $data['bukti_pembayaran'] ?? null,
                    'keterangan'           => $data['keterangan'] ?? 'Pengembalian kelebihan bayar keluarga',
                    'keterangan_refund'    => $data['keterangan_refund'] ?? null,
                    'created_by'           => Auth::user()->name ?? 'System',
                ]);

                $totalRefundJamaah = (int) ($jamaah->total_refund ?? 0) + $item['nominal'];
                $sisaKelebihanJamaah = max(0, $jamaah->kelebihan_bayar - $totalRefundJamaah);

                $jamaah->update([
                    'total_refund'         => $totalRefundJamaah,
                    'sisa_kelebihan_bayar' => $sisaKelebihanJamaah,
                ]);
            }

            $totalRefundKeluarga = (int) ($keluarga->total_refund ?? 0) + $jumlahRefund;
            $sisaKelebihanKeluarga = max(0, $keluarga->kelebihan_bayar - $totalRefundKeluarga);

            $keluarga->update([
                'total_refund'         => $totalRefundKeluarga,
                'sisa_kelebihan_bayar' => $sisaKelebihanKeluarga,
            ]);

            return $keluarga->fresh();
        });
    }
}
