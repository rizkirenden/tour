<?php

namespace App\Services;

use App\Models\Keluarga;
use App\Models\Jamaah;
use App\Models\ProdukPaket;
use App\Models\KotaAsal;
use App\Models\Diskon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KeluargaService
{
    public function getAll(array $filters = [])
    {
        $query = Keluarga::with(['jamaahs']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
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
        return Keluarga::with(['jamaahs'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate kode keluarga
            $data['kode_keluarga'] = Keluarga::generateKodeKeluarga();

            // Set default values
            $data['fee_agent'] = $data['fee_agent'] ?? 0;
            $data['total_dibayar'] = $data['total_dibayar'] ?? 0;

            // Ambil data diskon dari ID (nilai diskon per orang)
            $nilaiDiskonPerOrang = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskonPerOrang = $diskonData->nilai_diskon;
                }
            }

            // Ambil produk paket dari keluarga
            $produkKeluarga = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
            $hargaProduk = $produkKeluarga ? $produkKeluarga->total_harga : 0;

            // Hitung total tagihan
            $totalTagihan = 0;
            $jumlahAnggota = count($data['jamaahs'] ?? []);

            if (!empty($data['jamaahs']) && $jumlahAnggota > 0) {
                $feeAgentPerOrang = $data['fee_agent'] / $jumlahAnggota;

                foreach ($data['jamaahs'] as $jamaahData) {
                    $jamaahData['produk_paket'] = $data['produk_paket'];
                    $totalTagihan += $hargaProduk + $feeAgentPerOrang;
                }
            }

            // Total diskon = diskon per orang x jumlah anggota
            $totalDiskon = $nilaiDiskonPerOrang * $jumlahAnggota;

            $data['total_tagihan_sebelum_diskon'] = $totalTagihan;
            $data['nilai_diskon'] = $totalDiskon;
            $data['total_diskon'] = $totalDiskon;
            $data['total_tagihan_setelah_diskon'] = $totalTagihan - $totalDiskon;
            $data['sisa_tagihan'] = $data['total_tagihan_setelah_diskon'] - $data['total_dibayar'];

            // Tentukan status pembayaran
            if ($data['total_dibayar'] == 0) {
                $data['status_pembayaran'] = 'Belum Bayar';
            } elseif ($data['total_dibayar'] >= $data['total_tagihan_setelah_diskon']) {
                $data['status_pembayaran'] = 'Lunas';
            } elseif ($data['total_dibayar'] >= $data['total_tagihan_setelah_diskon'] * 0.5) {
                $data['status_pembayaran'] = 'Setoran';
            } else {
                $data['status_pembayaran'] = 'DP';
            }

            // Create Keluarga
            $keluarga = Keluarga::create($data);

            // Update kuota diskon jika digunakan
            if ($diskonData && $diskonData->is_available) {
                $diskonData->increment('sudah_digunakan');
            }

            // Create Jamaah anggota keluarga
            if (!empty($data['jamaahs'])) {
                $feeAgentPerOrang = $data['fee_agent'] / $jumlahAnggota;
                $diskonPerOrang = $nilaiDiskonPerOrang;

                foreach ($data['jamaahs'] as $index => $jamaahData) {
                    $jamaahData['id_keluarga'] = $keluarga->id_keluarga;
                    $jamaahData['is_kepala_keluarga'] = $jamaahData['is_kepala_keluarga'] ?? false;
                    $jamaahData['produk_paket'] = $data['produk_paket'];

                    $jamaahData = $this->handleFileUploads($jamaahData, $index);

                    $kodeProduk = $produkKeluarga ? 'PKT' : 'PKT';
                    $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk, $index);

                    $jamaahData['bulan_keberangkatan'] = $jamaahData['bulan_keberangkatan'] ?? $keluarga->bulan_keberangkatan;
                    $jamaahData['tahun_keberangkatan'] = $jamaahData['tahun_keberangkatan'] ?? $keluarga->tahun_keberangkatan;

                    $totalTagihanJamaah = $hargaProduk + $feeAgentPerOrang;
                    $diskonJamaah = $diskonPerOrang;

                    $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaah;
                    $jamaahData['nilai_diskon'] = $diskonJamaah;
                    $jamaahData['total_diskon'] = $diskonJamaah;
                    $jamaahData['total_tagihan_setelah_diskon'] = $totalTagihanJamaah - $diskonJamaah;
                    $jamaahData['total_dibayar'] = 0;
                    $jamaahData['sisa_tagihan'] = $jamaahData['total_tagihan_setelah_diskon'];
                    $jamaahData['status_pembayaran'] = 'Belum Bayar';

                    Jamaah::create($jamaahData);
                }
            }

            return $keluarga->fresh();
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $keluarga = $this->getById($id);

            // Ambil data diskon baru (nilai per orang)
            $nilaiDiskonPerOrang = 0;
            $diskonData = null;
            if (!empty($data['id_diskon'])) {
                $diskonData = Diskon::find($data['id_diskon']);
                if ($diskonData && $diskonData->is_available) {
                    $nilaiDiskonPerOrang = $diskonData->nilai_diskon;
                }
            }

            // Update data keluarga
            $data['nilai_diskon'] = $nilaiDiskonPerOrang;
            $keluarga->update($data);

            // Update kuota diskon
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

                $produkKeluarga = ProdukPaket::where('nama_produk', $data['produk_paket'])->first();
                $hargaProduk = $produkKeluarga ? $produkKeluarga->total_harga : 0;
                $jumlahAnggota = count($data['jamaahs']);
                $feeAgentPerOrang = $data['fee_agent'] / max($jumlahAnggota, 1);
                $diskonPerOrang = $nilaiDiskonPerOrang;

                foreach ($data['jamaahs'] as $index => $jamaahData) {
                    $jamaahData['id_keluarga'] = $keluarga->id_keluarga;
                    $jamaahData['produk_paket'] = $data['produk_paket'];
                    $jamaahData['nilai_diskon'] = $diskonPerOrang;

                    $jamaahData = $this->handleFileUploads($jamaahData, $index, $jamaahData['id_jamaah'] ?? null);

                    if (empty($jamaahData['id_jamaah'])) {
                        $kodeProduk = $produkKeluarga ? 'PKT' : 'PKT';
                        $jamaahData['id_keberangkatan'] = $this->generateIdKeberangkatan($kodeProduk, $index);

                        $jamaahData['bulan_keberangkatan'] = $jamaahData['bulan_keberangkatan'] ?? $keluarga->bulan_keberangkatan;
                        $jamaahData['tahun_keberangkatan'] = $jamaahData['tahun_keberangkatan'] ?? $keluarga->tahun_keberangkatan;

                        $totalTagihanJamaah = $hargaProduk + $feeAgentPerOrang;
                        $diskonJamaah = $diskonPerOrang;

                        $jamaahData['total_tagihan_sebelum_diskon'] = $totalTagihanJamaah;
                        $jamaahData['total_diskon'] = $diskonJamaah;
                        $jamaahData['total_tagihan_setelah_diskon'] = $totalTagihanJamaah - $diskonJamaah;
                        $jamaahData['total_dibayar'] = 0;
                        $jamaahData['sisa_tagihan'] = $jamaahData['total_tagihan_setelah_diskon'];
                        $jamaahData['status_pembayaran'] = 'Belum Bayar';

                        Jamaah::create($jamaahData);
                    } else {
                        $jamaah = Jamaah::find($jamaahData['id_jamaah']);
                        if ($jamaah) {
                            unset($jamaahData['total_tagihan_sebelum_diskon']);
                            unset($jamaahData['total_diskon']);
                            unset($jamaahData['total_tagihan_setelah_diskon']);
                            unset($jamaahData['sisa_tagihan']);
                            unset($jamaahData['status_pembayaran']);

                            $jamaah->update($jamaahData);
                        }
                    }
                }
            }

            // Recalculate total tagihan keluarga
            $this->recalculateKeluarga($keluarga->id_keluarga);

            return $keluarga->fresh();
        });
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
}
