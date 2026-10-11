<?php

namespace App\Services;

use App\Models\Jamaah;
use App\Models\ProdukPaket;
use App\Models\ProdukHargaBulanan;
use App\Models\ProdukKeberangkatan;
use App\Models\KotaAsal;
use App\Models\Diskon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JamaahService
{
    public function getAll(array $filters = [])
    {
        $query = Jamaah::with(['diskon', 'keluarga', 'keberangkatan']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_paspor', 'like', "%{$search}%")
                    ->orWhere('id_keberangkatan', 'like', "%{$search}%")
                    ->orWhere('produk_paket', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('agent_name', 'like', "%{$search}%")
                    ->orWhere('pendampingan_nama', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status_pembayaran'])) {
            $query->where('status_pembayaran', $filters['status_pembayaran']);
        }

        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (!empty($filters['sumber_data'])) {
            if ($filters['sumber_data'] == 'keluarga') {
                $query->whereNotNull('id_keluarga');
            } elseif ($filters['sumber_data'] == 'jamaah') {
                $query->whereNull('id_keluarga');
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Jamaah::with(['diskon', 'keluarga', 'keberangkatan'])->findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return Jamaah::with([
            'keluarga',
            'transaksis.metodePembayaran',
            'transaksis.jenisTransaksi',
            'diskon',
            'keberangkatan'
        ])->findOrFail($id);
    }

    // ==========================================
    // CREATE — FIXED
    // ==========================================
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['id_keberangkatan'])) {
                $produk = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
                $kodeProduk = $produk ? 'PKT' : 'PKT';
                $data['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk);
            }

            $kota = KotaAsal::where('nama_kota', $data['kota_asal'])->first();
            if ($kota) {
                $data['pulau'] = $kota->pulau;
                $data['bandara_keberangkatan'] = $kota->bandara_terdekat;
            }

            $data['agent_name'] = $data['agent_name'] ?? null;
            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
            $data['pendampingan_nama'] = $data['pendampingan_nama'] ?? null;
            $data['pendampingan_fee'] = (int) ($data['pendampingan_fee'] ?? 0);
            $data['pendampingan_fee_petugas'] = (int) ($data['pendampingan_fee_petugas'] ?? 0);

            $diskonData = null;
            $nilaiDiskon = 0;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskon = $diskonData->nilai_diskon;
                }
            }

            $data['total_dibayar'] = (int) ($data['total_dibayar'] ?? 0);

            $keberangkatanData = $data['keberangkatan_data'] ?? [];
            unset($data['keberangkatan_data']);

            // Validasi kuota
            $this->validateKuota($keberangkatanData);

            // ✅ AMBIL bulan_keberangkatan & tahun_keberangkatan DARI PRODUKKEBERANGKATAN
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

            $data['total_tagihan_sebelum_diskon'] = $hargaProduk;
            $data['nilai_diskon'] = $nilaiDiskon;
            $data['total_diskon'] = $nilaiDiskon;
            $data['total_tagihan_setelah_diskon'] = $hargaProduk - $nilaiDiskon;
            $data['sisa_tagihan'] = $data['total_tagihan_setelah_diskon'] - $data['total_dibayar'];
            $data['status_pembayaran'] = $this->determinePaymentStatus(
                $data['total_dibayar'],
                $data['total_tagihan_setelah_diskon']
            );

            $jamaah = Jamaah::create($data);

            // ✅ SIMPAN PIVOT LENGKAP
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
                        'bulan_keberangkatan'     => (int) $keberangkatan->bulan,
                        'tahun_keberangkatan'     => (int) $keberangkatan->tahun,
                        'bulan_harga'             => $bulanHarga,
                        'tahun_harga'             => $tahunHarga,
                        'harga'                   => (int) ($item['harga'] ?? 0),
                    ];
                }
                if (!empty($pivotData)) {
                    $jamaah->keberangkatan()->attach($pivotData);
                }
            }

            if ($diskonData && $diskonData->is_available) {
                $diskonData->increment('sudah_digunakan');
            }

            return $jamaah->fresh();
        });
    }

    // ==========================================
    // UPDATE — FIXED
    // ==========================================
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $jamaah = $this->getById($id);

            $kota = KotaAsal::where('nama_kota', $data['kota_asal'])->first();
            if ($kota) {
                $data['pulau'] = $kota->pulau;
                $data['bandara_keberangkatan'] = $kota->bandara_terdekat;
            }

            $data['agent_name'] = $data['agent_name'] ?? null;
            $data['fee_agent'] = (int) ($data['fee_agent'] ?? 0);
            $data['pendampingan_nama'] = $data['pendampingan_nama'] ?? null;
            $data['pendampingan_fee'] = (int) ($data['pendampingan_fee'] ?? 0);
            $data['pendampingan_fee_petugas'] = (int) ($data['pendampingan_fee_petugas'] ?? 0);

            $diskonData = null;
            $nilaiDiskon = 0;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskon = $diskonData->nilai_diskon;
                }
            }

            $data['total_dibayar'] = (int) ($data['total_dibayar'] ?? 0);

            if ($jamaah->id_diskon && $jamaah->id_diskon != ($data['id_diskon'] ?? null)) {
                $oldDiskon = Diskon::find($jamaah->id_diskon);
                if ($oldDiskon) {
                    $oldDiskon->decrement('sudah_digunakan');
                }
            }
            if ($diskonData && $diskonData->is_available && $jamaah->id_diskon != ($data['id_diskon'] ?? null)) {
                $diskonData->increment('sudah_digunakan');
            }

            $keberangkatanData = $data['keberangkatan_data'] ?? [];
            unset($data['keberangkatan_data']);

            // Validasi kuota (exclude jamaah ini)
            $this->validateKuota($keberangkatanData, $id);

            // ✅ AMBIL bulan_keberangkatan & tahun_keberangkatan DARI PRODUKKEBERANGKATAN
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

            $data['total_tagihan_sebelum_diskon'] = $hargaProduk;
            $data['nilai_diskon'] = $nilaiDiskon;
            $data['total_diskon'] = $nilaiDiskon;
            $data['total_tagihan_setelah_diskon'] = $hargaProduk - $nilaiDiskon;
            $data['sisa_tagihan'] = $data['total_tagihan_setelah_diskon'] - $data['total_dibayar'];

            $jamaah->update($data);

            // ✅ SYNC PIVOT LENGKAP
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
                        'bulan_keberangkatan'     => (int) $keberangkatan->bulan,
                        'tahun_keberangkatan'     => (int) $keberangkatan->tahun,
                        'bulan_harga'             => $bulanHarga,
                        'tahun_harga'             => $tahunHarga,
                        'harga'                   => (int) ($item['harga'] ?? 0),
                    ];
                }
                if (!empty($pivotData)) {
                    $jamaah->keberangkatan()->sync($pivotData);
                } else {
                    $jamaah->keberangkatan()->detach();
                }
            } else {
                $jamaah->keberangkatan()->detach();
            }

            return $jamaah->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jamaah = $this->getById($id);

            if ($jamaah->id_diskon) {
                $diskon = Diskon::find($jamaah->id_diskon);
                if ($diskon) {
                    $diskon->decrement('sudah_digunakan');
                }
            }

            $nama = $jamaah->nama_lengkap;
            $jamaah->delete();
            return $nama;
        });
    }

    // ==========================================
    // VALIDASI KUOTA (INTERNAL)
    // ==========================================
    private function validateKuota(array $keberangkatanData, $excludeJamaahId = null)
    {
        foreach ($keberangkatanData as $item) {
            $hargaBulananId = $item['id_produk_harga_bulanan'] ?? null;

            if (!$hargaBulananId) {
                continue;
            }

            $hargaBulanan = ProdukHargaBulanan::find($hargaBulananId);
            if (!$hargaBulanan) {
                continue;
            }

            if ($hargaBulanan->kuota <= 0) {
                continue;
            }

            $query = DB::table('jamaah_keberangkatans')
                ->where('id_produk_harga_bulanan', $hargaBulananId);

            if ($excludeJamaahId) {
                $query->where('id_jamaah', '!=', $excludeJamaahId);
            }

            $terpakai = $query->count();

            if ($terpakai >= $hargaBulanan->kuota) {
                $label = $hargaBulanan->bulan_formatted . ' ' . $hargaBulanan->tahun;
                throw new \Exception(
                    "Kuota untuk harga {$label} sudah PENUH ({$terpakai}/{$hargaBulanan->kuota} orang). " .
                    "Silakan pilih bulan/tahun harga lain atau tambah kuota terlebih dahulu."
                );
            }
        }
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
                    'bulan'           => (int) $h->bulan,
                    'tahun'           => (int) $h->tahun,
                    'bulan_formatted' => $h->bulan_formatted,
                    'harga'           => (int) $h->harga,
                    'harga_formatted' => 'Rp ' . number_format($h->harga, 0, ',', '.'),
                    'kuota'           => $h->kuota ?? 0,
                    'kuota_terpakai'  => $terpakai,
                    'kuota_sisa'      => $sisa,
                    'is_full'         => $isFull,
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
                    'id'                      => (int) $k->id,
                    'bulan'                   => (int) $k->bulan,
                    'tahun'                   => (int) $k->tahun,
                    'bulan_formatted'         => $k->bulan_formatted,
                    'keberangkatan_formatted' => $k->keberangkatan_formatted,
                    'harga_default'           => $hargaDefault ? (int) $hargaDefault['harga'] : 0,
                    'harga_default_formatted' => $hargaDefault ? $hargaDefault['harga_formatted'] : 'Rp 0',
                    'id_harga_default'        => $hargaDefault ? (int) $hargaDefault['id'] : null,
                    'kuota_default'           => $hargaDefault ? ($hargaDefault['kuota'] ?? 0) : 0,
                    'kuota_default_formatted' => $hargaDefault ? $hargaDefault['kuota_formatted'] : 'Tidak Terbatas',
                    'kuota_default_is_full'   => $hargaDefault ? $hargaDefault['is_full'] : false,
                ];
            })->values()->toArray();

        return [
            'keberangkatan' => $keberangkatan,
            'harga_bulanan' => $hargaBulanan,
        ];
    }
}
