<?php

namespace App\Services;

use App\Models\Keluarga;
use App\Models\Jamaah;
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
        $query = Keluarga::with(['jamaahs', 'diskon']);

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
        return Keluarga::with(['jamaahs', 'diskon', 'produkKeberangkatan', 'produkHargaBulanan'])->findOrFail($id);
    }

    // ==========================================
    // CREATE
    // ==========================================
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate kode keluarga
            $data['kode_keluarga'] = Keluarga::generateKodeKeluarga();

            // Set default values
            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
            $data['total_dibayar'] = (int) ($data['total_dibayar'] ?? 0);

            // ==========================================
            // AMBIL HARGA dari id_produk_harga_bulanan
            // ==========================================
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

            // ==========================================
            // VALIDASI KUOTA — SEBELUM CREATE
            // Jumlah anggota keluarga yang akan ditambahkan
            // ==========================================
            $jumlahAnggota = count($data['jamaahs'] ?? []);
            $this->validateKuota($idHargaBulanan, $jumlahAnggota);

            // Ambil data diskon (nilai per orang)
            $nilaiDiskonPerOrang = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskonPerOrang = $diskonData->nilai_diskon;
                }
            }

            // Hitung total tagihan
            $totalTagihan = 0;
            if (!empty($data['jamaahs']) && $jumlahAnggota > 0) {
                foreach ($data['jamaahs'] as $jamaahData) {
                    $totalTagihan += $hargaProduk;
                }
            }

            $totalDiskon = $nilaiDiskonPerOrang * $jumlahAnggota;

            $data['total_tagihan_sebelum_diskon'] = $totalTagihan;
            $data['nilai_diskon'] = $totalDiskon;
            $data['total_diskon'] = $totalDiskon;
            $data['total_tagihan_setelah_diskon'] = $totalTagihan - $totalDiskon;
            $data['sisa_tagihan'] = $data['total_tagihan_setelah_diskon'] - $data['total_dibayar'];
            $data['status_pembayaran'] = $this->determinePaymentStatus($data['total_dibayar'], $data['total_tagihan_setelah_diskon']);

            $keluarga = Keluarga::create($data);

            if ($diskonData && $diskonData->is_available) {
                $diskonData->increment('sudah_digunakan');
            }

            // ==========================================
            // CREATE JAMAAH ANGGOTA
            // ==========================================
            if (!empty($data['jamaahs'])) {
                $diskonPerOrang = $nilaiDiskonPerOrang;
                $produkKeluarga = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();

                foreach ($data['jamaahs'] as $index => $jamaahData) {
                    $jamaahData['id_keluarga'] = $keluarga->id_keluarga;
                    $jamaahData['is_kepala_keluarga'] = $jamaahData['is_kepala_keluarga'] ?? false;
                    $jamaahData['produk_paket'] = $data['produk_paket'];

                    $jamaahData['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
                    $jamaahData['agent_name'] = $data['agent'] ?? null;

                    $jamaahData['pendampingan_nama'] = $jamaahData['pendampingan_nama'] ?? null;
                    $jamaahData['pendampingan_fee'] = (int) ($jamaahData['pendampingan_fee'] ?? 0);
                    $jamaahData['pendampingan_fee_petugas'] = (int) ($jamaahData['pendampingan_fee_petugas'] ?? 0);

                    $jamaahData = $this->handleFileUploads($jamaahData, $index);

                    $kodeProduk = $produkKeluarga ? 'PKT' : 'PKT';
                    $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk, $index);

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

                    $totalTagihanJamaah = $hargaProduk;
                    $diskonJamaah = $diskonPerOrang;

                    $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaah;
                    $jamaahData['nilai_diskon'] = $diskonJamaah;
                    $jamaahData['total_diskon'] = $diskonJamaah;
                    $jamaahData['total_tagihan_setelah_diskon'] = $totalTagihanJamaah - $diskonJamaah;
                    $jamaahData['total_dibayar'] = 0;
                    $jamaahData['sisa_tagihan'] = $jamaahData['total_tagihan_setelah_diskon'];
                    $jamaahData['status_pembayaran'] = 'Belum Bayar';

                    $jamaah = Jamaah::create($jamaahData);

                    // Simpan pivot keberangkatan
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

            // ==========================================
            // VALIDASI KUOTA — UPDATE
            // Hanya hitung jamaah BARU (yang belum ada id_jamaah)
            // Exclude jamaah existing dari keluarga ini yang sudah pakai harga ini
            // ==========================================
            $jamaahBaru = 0;
            if (!empty($data['jamaahs'])) {
                foreach ($data['jamaahs'] as $j) {
                    if (empty($j['id_jamaah'])) {
                        $jamaahBaru++;
                    }
                }
            }

            // Exclude semua jamaah yang saat ini ada di keluarga ini (mereka sudah "memakai" slot)
            // dari perhitungan
            $excludeJamaahIds = $keluarga->jamaahs->pluck('id_jamaah')->toArray();

            if ($jamaahBaru > 0) {
                $this->validateKuota($idHargaBulanan, $jamaahBaru, $excludeJamaahIds);
            }

            // Ambil data diskon baru
            $nilaiDiskonPerOrang = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskonPerOrang = $diskonData->nilai_diskon;
                }
            }

            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
            $data['nilai_diskon'] = $nilaiDiskonPerOrang;
            $keluarga->update($data);

            if ($keluarga->id_diskon && $keluarga->id_diskon != ($data['id_diskon'] ?? null)) {
                $oldDiskon = Diskon::find($keluarga->id_diskon);
                if ($oldDiskon) {
                    $oldDiskon->decrement('sudah_digunakan');
                }
            }
            if ($diskonData && $diskonData->is_available && $keluarga->id_diskon != ($data['id_diskon'] ?? null)) {
                $diskonData->increment('sudah_digunakan');
            }

            // Update jamaah anggota
            if (!empty($data['jamaahs'])) {
                $existingIds = collect($data['jamaahs'])->pluck('id_jamaah')->filter()->toArray();
                $keluarga->jamaahs()->whereNotIn('id_jamaah', $existingIds)->delete();

                $diskonPerOrang = $nilaiDiskonPerOrang;
                $produkKeluarga = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();

                foreach ($data['jamaahs'] as $index => $jamaahData) {
                    $jamaahData['id_keluarga'] = $keluarga->id_keluarga;
                    $jamaahData['produk_paket'] = $data['produk_paket'];
                    $jamaahData['nilai_diskon'] = $diskonPerOrang;
                    $jamaahData['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
                    $jamaahData['agent_name'] = $data['agent'] ?? null;

                    $jamaahData['pendampingan_nama'] = $jamaahData['pendampingan_nama'] ?? null;
                    $jamaahData['pendampingan_fee'] = (int) ($jamaahData['pendampingan_fee'] ?? 0);
                    $jamaahData['pendampingan_fee_petugas'] = (int) ($jamaahData['pendampingan_fee_petugas'] ?? 0);

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

                    if (empty($jamaahData['id_jamaah'])) {
                        // Create baru
                        $kodeProduk = $produkKeluarga ? 'PKT' : 'PKT';
                        $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk, $index);

                        $totalTagihanJamaah = $hargaProduk;
                        $diskonJamaah = $diskonPerOrang;

                        $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaah;
                        $jamaahData['total_diskon'] = $diskonJamaah;
                        $jamaahData['total_tagihan_setelah_diskon'] = $totalTagihanJamaah - $diskonJamaah;
                        $jamaahData['total_dibayar'] = 0;
                        $jamaahData['sisa_tagihan'] = $jamaahData['total_tagihan_setelah_diskon'];
                        $jamaahData['status_pembayaran'] = 'Belum Bayar';

                        $jamaah = Jamaah::create($jamaahData);

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
                        // Update existing
                        $jamaah = Jamaah::find($jamaahData['id_jamaah']);
                        if ($jamaah) {
                            unset($jamaahData['total_tagihan_sebelum_diskon']);
                            unset($jamaahData['total_diskon']);
                            unset($jamaahData['total_tagihan_setelah_diskon']);
                            unset($jamaahData['sisa_tagihan']);
                            unset($jamaahData['status_pembayaran']);

                            $jamaah->update($jamaahData);

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
    // VALIDASI KUOTA (INTERNAL)
    // ==========================================
    private function validateKuota($idHargaBulanan, $jumlahOrang, $excludeJamaahIds = [])
    {
        if (!$idHargaBulanan) {
            return;
        }

        $hargaBulanan = ProdukHargaBulanan::find($idHargaBulanan);
        if (!$hargaBulanan) {
            return;
        }

        // Kuota 0 = tidak terbatas
        if ($hargaBulanan->kuota <= 0) {
            return;
        }

        // Hitung terpakai
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
                "Kuota untuk harga {$label} sudah PENUH ({$terpakai}/{$hargaBulanan->kuota} orang). " .
                "Silakan pilih bulan/tahun harga lain atau tambah kuota terlebih dahulu."
            );
        }

        if ($jumlahOrang > $sisa) {
            throw new \Exception(
                "Kuota tidak mencukupi untuk harga {$label}. " .
                "Sisa kuota: {$sisa} orang, jumlah anggota yang akan ditambahkan: {$jumlahOrang} orang. " .
                "Silakan kurangi jumlah anggota atau pilih bulan/tahun harga lain."
            );
        }
    }

    // ==========================================
    // HANDLE FILE UPLOADS
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
    // DELETE
    // ==========================================
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $keluarga = $this->getById($id);

            if ($keluarga->id_diskon) {
                $diskon = Diskon::find($keluarga->id_diskon);
                if ($diskon) {
                    $diskon->decrement('sudah_digunakan');
                }
            }

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

        $totalTagihan = $jamaahs->sum('total_tagihan_sebelum_diskon');
        $totalDibayar = $jamaahs->sum('total_dibayar');
        $totalDiskon = $jamaahs->sum('total_diskon');
        $totalTagihanSetelahDiskon = $totalTagihan - $totalDiskon;
        $sisaTagihan = $totalTagihanSetelahDiskon - $totalDibayar;

        $keluarga->update([
            'total_tagihan_sebelum_diskon' => $totalTagihan,
            'total_diskon' => $totalDiskon,
            'total_tagihan_setelah_diskon' => $totalTagihanSetelahDiskon,
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan' => $sisaTagihan,
            'status_pembayaran' => $this->determinePaymentStatus($totalDibayar, $totalTagihanSetelahDiskon)
        ]);
    }

    private function determinePaymentStatus($totalDibayar, $totalTagihan)
    {
        if ($totalDibayar == 0) {
            return 'Belum Bayar';
        } elseif ($totalDibayar >= $totalTagihan) {
            return 'Lunas';
        } elseif ($totalDibayar >= $totalTagihan * 0.5) {
            return 'Setoran';
        } else {
            return 'DP';
        }
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
            'Kepala Keluarga',
            'Istri',
            'Suami',
            'Anak',
            'Orang Tua',
            'Saudara',
            'Mertua',
            'Cucu',
            'Keponakan',
            'Lainnya'
        ];
    }

    public function getDiskons()
    {
        return Diskon::available()->orderBy('nama_diskon')->get();
    }

    // ==========================================
    // GET KEBERANGKATAN BY PRODUK (dengan kuota realtime)
    // ==========================================
    public function getKeberangkatanByProduk($namaProduk)
    {
        $produk = ProdukPaket::where('nama_produk', $namaProduk)->first();
        if (!$produk) {
            return [
                'keberangkatan' => [],
                'harga_bulanan' => [],
            ];
        }

        // Ambil semua harga bulanan + hitung terpakai
        $hargaBulanan = ProdukHargaBulanan::where('produk_paket_id', $produk->id_produk)
            ->where('is_active', true)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function ($h) {
                $terpakai = DB::table('jamaah_keberangkatans')
                    ->where('id_produk_harga_bulanan', $h->id)
                    ->count();

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
                    'id'              => $h->id,
                    'bulan'           => $h->bulan,
                    'tahun'           => $h->tahun,
                    'bulan_formatted' => $h->bulan_formatted,
                    'harga'           => $h->harga,
                    'harga_formatted' => 'Rp ' . number_format($h->harga, 0, ',', '.'),
                    'kuota'           => $h->kuota ?? 0,
                    'kuota_terpakai'  => $terpakai,
                    'kuota_sisa'      => $sisa,
                    'is_full'         => $isFull,
                    'kuota_formatted' => $kuotaFormatted,
                ];
            })->values()->toArray();

        // Ambil semua keberangkatan
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
                    'id' => $k->id,
                    'bulan' => $k->bulan,
                    'tahun' => $k->tahun,
                    'bulan_formatted' => $k->bulan_formatted,
                    'keberangkatan_formatted' => $k->keberangkatan_formatted,
                    'harga_default' => $hargaDefault ? $hargaDefault['harga'] : 0,
                    'harga_default_formatted' => $hargaDefault ? $hargaDefault['harga_formatted'] : 'Rp 0',
                    'id_harga_default' => $hargaDefault ? $hargaDefault['id'] : null,
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
