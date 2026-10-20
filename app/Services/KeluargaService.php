<?php

namespace App\Services;

use App\Models\Keluarga;
use App\Models\Jamaah;
use App\Models\JamaahUpgradeHotel;
use App\Models\ProdukPaket;
use App\Models\ProdukHargaBulanan;
use App\Models\ProdukKeberangkatan;
use App\Models\KotaAsal;
use App\Models\Diskon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KeluargaService
{
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

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Keluarga::with([
            'jamaahs.upgradeHotels.hotel',
            'jamaahs.upgradeHotels.kamar',
            'upgradeHotels.hotel',
            'upgradeHotels.kamar',
            'diskon',
            'produkKeberangkatan',
            'produkHargaBulanan',
        ])->findOrFail($id);
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

            $hargaProduk = 0;
            $hargaBulanan = null;
            $idHargaBulanan = $data['id_produk_harga_bulanan'] ?? null;

            if ($idHargaBulanan) {
                $hargaBulanan = ProdukHargaBulanan::find($idHargaBulanan);
                if ($hargaBulanan) {
                    $hargaProduk = $hargaBulanan->harga;
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
                        $hargaProduk = $harga->harga;
                        $hargaBulanan = $harga;
                        $data['bulan_harga'] = $harga->bulan;
                        $data['tahun_harga'] = $harga->tahun;
                    }
                }
            }

            $jumlahAnggota = count($data['jamaahs'] ?? []);
            $this->validateKuota($idHargaBulanan, $jumlahAnggota);

            $totalDiskonKelompok = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $totalDiskonKelompok = (int) $diskonData->nilai_diskon;
                }
            }

            $diskonPerOrang = $jumlahAnggota > 0
                ? (int) round($totalDiskonKelompok / $jumlahAnggota)
                : 0;

            $sisaPembulatan = $totalDiskonKelompok - ($diskonPerOrang * $jumlahAnggota);

            $totalUpgradeKeluarga = 0;
            $upgradeKeluargaData = $data['upgrade_hotels'] ?? [];
            foreach ($upgradeKeluargaData as $up) {
                $jumlahKamar = max(1, (int) ($up['jumlah_kamar'] ?? 1));
                $durasi = max(1, (int) ($up['durasi_menginap'] ?? 1));
                $harga = (int) ($up['harga_per_malam'] ?? 0);
                $totalUpgradeKeluarga += $jumlahKamar * $harga * $durasi;
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

                    $jUpgradePersonal = 0;
                    foreach ($j['upgrade_hotels'] ?? [] as $up) {
                        $jk = max(1, (int) ($up['jumlah_kamar'] ?? 1));
                        $dd = max(1, (int) ($up['durasi_menginap'] ?? 1));
                        $hh = (int) ($up['harga_per_malam'] ?? 0);
                        $jUpgradePersonal += $jk * $hh * $dd;
                    }

                    $totalTagihanKotor += $hargaProduk + $passportFee + $jUpgradePersonal + $upgradeKeluargaPerJamaah;
                }
            }

            $totalTagihanSetelahDiskon = max(0, $totalTagihanKotor - $totalDiskonKelompok);

            $data['total_tagihan_sebelum_diskon'] = $totalTagihanKotor;
            $data['nilai_diskon'] = $diskonPerOrang;
            $data['total_diskon'] = $totalDiskonKelompok;
            $data['total_tagihan_setelah_diskon'] = $totalTagihanSetelahDiskon;
            $data['sisa_tagihan'] = $totalTagihanSetelahDiskon - $data['total_dibayar'];
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

                    $jamaahData['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
                    $jamaahData['agent_name'] = $data['agent'] ?? null;

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

                    $passportFeeJamaah = $jamaahData['pendampingan_fee'] + $jamaahData['pendampingan_fee_petugas'];

                    $jUpgradePersonal = 0;
                    foreach ($upgradeJamaahData as $up) {
                        $jk = max(1, (int) ($up['jumlah_kamar'] ?? 1));
                        $dd = max(1, (int) ($up['durasi_menginap'] ?? 1));
                        $hh = (int) ($up['harga_per_malam'] ?? 0);
                        $jUpgradePersonal += $jk * $hh * $dd;
                    }

                    $totalTagihanJamaahKotor = $hargaProduk + $passportFeeJamaah + $jUpgradePersonal + $upgradeKeluargaPerJamaah;

                    $diskonJamaah = $diskonPerOrang;
                    if ($index === 0 && $sisaPembulatan != 0) {
                        $diskonJamaah += $sisaPembulatan;
                    }

                    $tagihanSetelahDiskonJamaah = max(0, $totalTagihanJamaahKotor - $diskonJamaah);

                    $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaahKotor;
                    $jamaahData['nilai_diskon'] = $diskonPerOrang;
                    $jamaahData['total_diskon'] = $diskonJamaah;
                    $jamaahData['total_tagihan_setelah_diskon'] = $tagihanSetelahDiskonJamaah;
                    $jamaahData['total_dibayar'] = 0;
                    $jamaahData['sisa_tagihan'] = $tagihanSetelahDiskonJamaah;
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
                                'harga' => $hargaProduk,
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

            $hargaProduk = 0;
            $hargaBulanan = null;
            $idHargaBulanan = $data['id_produk_harga_bulanan'] ?? null;

            if ($idHargaBulanan) {
                $hargaBulanan = ProdukHargaBulanan::find($idHargaBulanan);
                if ($hargaBulanan) {
                    $hargaProduk = $hargaBulanan->harga;
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
                        $hargaProduk = $harga->harga;
                        $hargaBulanan = $harga;
                        $data['bulan_harga'] = $harga->bulan;
                        $data['tahun_harga'] = $harga->tahun;
                    }
                }
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
            $diskonPerOrang = $jumlahJamaah > 0
                ? (int) round($totalDiskonKelompok / $jumlahJamaah)
                : 0;

            $sisaPembulatan = $totalDiskonKelompok - ($diskonPerOrang * $jumlahJamaah);

            $totalUpgradeKeluarga = 0;
            $upgradeKeluargaData = $data['upgrade_hotels'] ?? [];
            foreach ($upgradeKeluargaData as $up) {
                $jumlahKamar = max(1, (int) ($up['jumlah_kamar'] ?? 1));
                $durasi = max(1, (int) ($up['durasi_menginap'] ?? 1));
                $harga = (int) ($up['harga_per_malam'] ?? 0);
                $totalUpgradeKeluarga += $jumlahKamar * $harga * $durasi;
            }
            $upgradeKeluargaPerJamaah = $jumlahJamaah > 0
                ? (int) round($totalUpgradeKeluarga / $jumlahJamaah)
                : 0;

            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
            $data['nilai_diskon'] = $diskonPerOrang;
            $data['total_diskon'] = $totalDiskonKelompok;
            unset($data['upgrade_hotels']);

            $keluarga->update($data);

            $this->saveUpgradeHotelsKeluarga($keluarga->id_keluarga, $upgradeKeluargaData);

            if ($keluarga->id_diskon && $keluarga->id_diskon != ($data['id_diskon'] ?? null)) {
                $oldDiskon = Diskon::find($keluarga->id_diskon);
                if ($oldDiskon) $oldDiskon->decrement('sudah_digunakan');
            }
            if ($diskonData && $diskonData->is_available && $keluarga->id_diskon != ($data['id_diskon'] ?? null)) {
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
                    $jamaahData['nilai_diskon'] = $diskonPerOrang;
                    $jamaahData['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
                    $jamaahData['agent_name'] = $data['agent'] ?? null;

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

                    $passportFeeJamaah = $jamaahData['pendampingan_fee'] + $jamaahData['pendampingan_fee_petugas'];

                    $jUpgradePersonal = 0;
                    foreach ($upgradeJamaahData as $up) {
                        $jk = max(1, (int) ($up['jumlah_kamar'] ?? 1));
                        $dd = max(1, (int) ($up['durasi_menginap'] ?? 1));
                        $hh = (int) ($up['harga_per_malam'] ?? 0);
                        $jUpgradePersonal += $jk * $hh * $dd;
                    }

                    $totalTagihanJamaahKotor = $hargaProduk + $passportFeeJamaah + $jUpgradePersonal + $upgradeKeluargaPerJamaah;

                    $diskonJamaah = $diskonPerOrang;
                    if ($index === 0 && $sisaPembulatan != 0) {
                        $diskonJamaah += $sisaPembulatan;
                    }

                    $tagihanSetelahDiskonJamaah = max(0, $totalTagihanJamaahKotor - $diskonJamaah);

                    if (empty($jamaahData['id_jamaah'])) {
                        $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan('PKT', $index);
                        $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaahKotor;
                        $jamaahData['total_diskon'] = $diskonJamaah;
                        $jamaahData['total_tagihan_setelah_diskon'] = $tagihanSetelahDiskonJamaah;
                        $jamaahData['total_dibayar'] = 0;
                        $jamaahData['sisa_tagihan'] = $tagihanSetelahDiskonJamaah;
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
                                    'harga' => $hargaProduk,
                                ]);
                            }
                        }
                    } else {
                        $jamaah = Jamaah::find($jamaahData['id_jamaah']);
                        if ($jamaah) {
                            $sisaTagihanBaru = max(0, $tagihanSetelahDiskonJamaah - $jamaah->total_dibayar);

                            if ($sisaTagihanBaru <= 0 && $jamaah->total_dibayar > 0) {
                                $statusBaru = 'Lunas';
                            } elseif ($jamaah->total_dibayar == 0) {
                                $statusBaru = 'Belum Bayar';
                            } elseif ($jamaah->total_dibayar >= $tagihanSetelahDiskonJamaah * 0.5) {
                                $statusBaru = 'Setoran';
                            } else {
                                $statusBaru = 'DP';
                            }

                            $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaahKotor;
                            $jamaahData['nilai_diskon'] = $diskonPerOrang;
                            $jamaahData['total_diskon'] = $diskonJamaah;
                            $jamaahData['total_tagihan_setelah_diskon'] = $tagihanSetelahDiskonJamaah;
                            $jamaahData['sisa_tagihan'] = $sisaTagihanBaru;
                            $jamaahData['status_pembayaran'] = $statusBaru;

                            $jamaah->update($jamaahData);
                            $this->saveUpgradeHotelsJamaah($jamaah->id_jamaah, $upgradeJamaahData);

                            if (!empty($data['id_produk_keberangkatan'])) {
                                $keberangkatan = ProdukKeberangkatan::find($data['id_produk_keberangkatan']);
                                if ($keberangkatan) {
                                    $jamaah->keberangkatan()->sync([
                                        $keberangkatan->id => [
                                            'id_produk_harga_bulanan' => $idHargaBulanan,
                                            'bulan_keberangkatan' => $keberangkatan->bulan,
                                            'tahun_keberangkatan' => $keberangkatan->tahun,
                                            'bulan_harga' => $hargaBulanan->bulan ?? $keberangkatan->bulan,
                                            'tahun_harga' => $hargaBulanan->tahun ?? $keberangkatan->tahun,
                                            'harga' => $hargaProduk,
                                        ],
                                    ]);
                                }
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
    // SAVE UPGRADE HOTELS
    // ==========================================
    private function saveUpgradeHotelsKeluarga($idKeluarga, array $upgradeHotels)
    {
        JamaahUpgradeHotel::where('id_keluarga', $idKeluarga)
            ->whereNull('id_jamaah')
            ->delete();

        foreach ($upgradeHotels as $item) {
            $idHotel = $item['id_hotel'] ?? null;
            if (!$idHotel) continue;

            $jumlahKamar = max(1, (int) ($item['jumlah_kamar'] ?? 1));
            $durasi = max(1, (int) ($item['durasi_menginap'] ?? 1));
            $harga = (int) ($item['harga_per_malam'] ?? 0);

            JamaahUpgradeHotel::create([
                'id_keluarga' => $idKeluarga,
                'id_jamaah' => null,
                'id_hotel' => $idHotel,
                'id_kamar' => $item['id_kamar'] ?? null,
                'tipe_kamar' => $item['tipe_kamar'] ?? null,
                'jumlah_kamar' => $jumlahKamar,
                'durasi_menginap' => $durasi,
                'harga_per_malam' => $harga,
                'total_harga' => $jumlahKamar * $harga * $durasi,
                'catatan' => $item['catatan'] ?? null,
            ]);
        }
    }

    private function saveUpgradeHotelsJamaah($idJamaah, array $upgradeHotels)
    {
        JamaahUpgradeHotel::where('id_jamaah', $idJamaah)->delete();

        foreach ($upgradeHotels as $item) {
            $idHotel = $item['id_hotel'] ?? null;
            if (!$idHotel) continue;

            $jumlahKamar = max(1, (int) ($item['jumlah_kamar'] ?? 1));
            $durasi = max(1, (int) ($item['durasi_menginap'] ?? 1));
            $harga = (int) ($item['harga_per_malam'] ?? 0);

            JamaahUpgradeHotel::create([
                'id_jamaah' => $idJamaah,
                'id_keluarga' => null,
                'id_hotel' => $idHotel,
                'id_kamar' => $item['id_kamar'] ?? null,
                'tipe_kamar' => $item['tipe_kamar'] ?? null,
                'jumlah_kamar' => $jumlahKamar,
                'durasi_menginap' => $durasi,
                'harga_per_malam' => $harga,
                'total_harga' => $jumlahKamar * $harga * $durasi,
                'catatan' => $item['catatan'] ?? null,
            ]);
        }
    }

    // ==========================================
    // VALIDASI KUOTA
    // ==========================================
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

    // ==========================================
    // FILE UPLOADS
    // ==========================================
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

    // ==========================================
    // CLEANUP FILES
    // ==========================================
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

    // ==========================================
    // DELETE
    // ==========================================
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
            $totalUpgradeJamaah = $upgradePersonal + $upgradePerJamaah;

            $baseKotor = $jamaah->total_tagihan_sebelum_diskon - ($jamaah->upgradeHotels->sum('total_harga') + $upgradePerJamaah);
            $totalKotor = $baseKotor + $totalUpgradeJamaah;
            $setelahDiskon = max(0, $totalKotor - $jamaah->total_diskon);
            $sisa = max(0, $setelahDiskon - $jamaah->total_dibayar);

            $status = $this->determinePaymentStatus($jamaah->total_dibayar, $setelahDiskon);

            $jamaah->update([
                'total_tagihan_sebelum_diskon' => $totalKotor,
                'total_tagihan_setelah_diskon' => $setelahDiskon,
                'sisa_tagihan' => $sisa,
                'status_pembayaran' => $status,
            ]);
        }

        $keluarga = $this->getById($keluargaId);
        $jamaahs = $keluarga->jamaahs;

        $totalKotor = $jamaahs->sum('total_tagihan_sebelum_diskon');
        $totalDibayar = $jamaahs->sum('total_dibayar');
        $totalDiskon = $jamaahs->sum('total_diskon');
        $totalSetelahDiskon = max(0, $totalKotor - $totalDiskon);
        $sisaTagihan = max(0, $totalSetelahDiskon - $totalDibayar);
        $diskonPerOrang = $jamaahs->count() > 0 ? (int) round($totalDiskon / $jamaahs->count()) : 0;

        $keluarga->update([
            'total_tagihan_sebelum_diskon' => $totalKotor,
            'nilai_diskon' => $diskonPerOrang,
            'total_diskon' => $totalDiskon,
            'total_tagihan_setelah_diskon' => $totalSetelahDiskon,
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan' => $sisaTagihan,
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
        $number = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        return "{$kodeProduk}-{$year}{$month}-{$number}";
    }

    // ==========================================
    // OPTIONS
    // ==========================================
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

    // ==========================================
    // AJAX: KEBERANGKATAN BY PRODUK
    // ==========================================
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
                    'id' => $h->id,
                    'bulan' => $h->bulan,
                    'tahun' => $h->tahun,
                    'bulan_formatted' => $h->bulan_formatted,
                    'harga' => $h->harga,
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
            ->orderBy('tahun')->orderBy('bulan')
            ->get()
            ->map(function ($k) use ($hargaBulanan) {
                $hargaDefault = collect($hargaBulanan)->first(function ($h) use ($k) {
                    return $h['bulan'] == $k->bulan && $h['tahun'] == $k->tahun;
                });

                return [
                    'id' => $k->id,
                    'bulan' => $k->bulan,
                    'tahun' => $k->tahun,
                    'bulan_formatted' => $k->bulan_formatted,
                    'keberangkatan_formatted' => $k->keberangkatan_formatted,
                    'harga_default' => $hargaDefault ? $hargaDefault['harga'] : 0,
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
