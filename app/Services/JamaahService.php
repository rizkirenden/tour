<?php

namespace App\Services;

use App\Models\Jamaah;
use App\Models\ProdukPaket;
use App\Models\KotaAsal;
use App\Models\Diskon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JamaahService
{
    public function getAll(array $filters = [])
    {
        $query = Jamaah::with(['diskon']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_paspor', 'like', "%{$search}%")
                  ->orWhere('id_keberangkatan', 'like', "%{$search}%")
                  ->orWhere('produk_paket', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status_pembayaran'])) {
            $query->where('status_pembayaran', $filters['status_pembayaran']);
        }

        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Jamaah::with(['diskon'])->findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return Jamaah::with(['keluarga', 'transaksis.metodePembayaran', 'transaksis.jenisTransaksi', 'diskon'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate ID Keberangkatan
            if (empty($data['id_keberangkatan'])) {
                $produk = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
                $kodeProduk = $produk ? $produk->kode_produk : 'PKT';
                $data['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk);
            }

            // Set kota asal
            $kota = KotaAsal::where('nama_kota', $data['kota_asal'])->first();
            if ($kota) {
                $data['pulau'] = $kota->pulau;
                $data['bandara_keberangkatan'] = $kota->bandara_terdekat;
            }

            // Ambil data diskon (nilai nominal per orang)
            $diskonData = null;
            $nilaiDiskon = 0;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskon = $diskonData->nilai_diskon;
                }
            }

            // Set default values
            $data['fee_agent'] = $data['fee_agent'] ?? 0;
            $data['total_dibayar'] = $data['total_dibayar'] ?? 0;

            // Ambil harga produk
            $produk = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
            $hargaProduk = $produk ? $produk->harga_dasar : 0;

            // Hitung tagihan
            $data['total_tagihan_sebelum_diskon'] = $hargaProduk + $data['fee_agent'];
            $data['nilai_diskon'] = $nilaiDiskon;
            $data['total_diskon'] = $nilaiDiskon;
            $data['total_tagihan_setelah_diskon'] = $data['total_tagihan_sebelum_diskon'] - $nilaiDiskon;
            $data['sisa_tagihan'] = $data['total_tagihan_setelah_diskon'] - $data['total_dibayar'];

            // Tentukan status pembayaran
            $data['status_pembayaran'] = $this->determinePaymentStatus($data['total_dibayar'], $data['total_tagihan_setelah_diskon']);

            // Create Jamaah
            $jamaah = Jamaah::create($data);

            // Update kuota diskon
            if ($diskonData && $diskonData->is_available) {
                $diskonData->increment('sudah_digunakan');
            }

            return $jamaah->fresh();
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $jamaah = $this->getById($id);

            // Set kota asal
            $kota = KotaAsal::where('nama_kota', $data['kota_asal'])->first();
            if ($kota) {
                $data['pulau'] = $kota->pulau;
                $data['bandara_keberangkatan'] = $kota->bandara_terdekat;
            }

            // Ambil data diskon baru (nilai nominal)
            $diskonData = null;
            $nilaiDiskon = 0;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskon = $diskonData->nilai_diskon;
                }
            }

            // Set default values
            $data['fee_agent'] = $data['fee_agent'] ?? 0;
            $data['total_dibayar'] = $data['total_dibayar'] ?? 0;

            // Ambil harga produk
            $produk = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
            $hargaProduk = $produk ? $produk->harga_dasar : 0;

            // Update kuota diskon
            if ($jamaah->id_diskon && $jamaah->id_diskon != ($data['id_diskon'] ?? null)) {
                $oldDiskon = Diskon::find($jamaah->id_diskon);
                if ($oldDiskon) {
                    $oldDiskon->decrement('sudah_digunakan');
                }
            }
            if ($diskonData && $diskonData->is_available && $jamaah->id_diskon != ($data['id_diskon'] ?? null)) {
                $diskonData->increment('sudah_digunakan');
            }

            // Hitung tagihan (jangan update jika sudah ada pembayaran)
            if ($jamaah->total_dibayar == 0) {
                $data['total_tagihan_sebelum_diskon'] = $hargaProduk + $data['fee_agent'];
                $data['nilai_diskon'] = $nilaiDiskon;
                $data['total_diskon'] = $nilaiDiskon;
                $data['total_tagihan_setelah_diskon'] = $data['total_tagihan_sebelum_diskon'] - $nilaiDiskon;
                $data['sisa_tagihan'] = $data['total_tagihan_setelah_diskon'] - $data['total_dibayar'];
            }

            $jamaah->update($data);
            return $jamaah->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jamaah = $this->getById($id);

            // Kembalikan kuota diskon
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
}
