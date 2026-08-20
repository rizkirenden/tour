<?php

namespace App\Services;

use App\Models\ProdukPaket;
use App\Models\PaketProdukPerlengkapan;
use Illuminate\Support\Facades\DB;

class ProdukPaketService
{
    public function getAll(array $filters = [])
    {
        $query = ProdukPaket::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%");
            });
        }

        return $query->with(['perlengkapans', 'paketTour'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    public function getById($id)
    {
        return ProdukPaket::findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return ProdukPaket::with([
            'hotelMekkah',
            'hotelMadinah',
            'hotelTransit',
            'paketTour',
            'paketTour.hotels',
            'statusKeberangkatan',
            'perlengkapans'
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['kode_produk'])) {
                $data['kode_produk'] = $this->generateKodeProduk();
            }

            // Set default values
            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
            $data['paket_tour_id'] = $data['paket_tour_id'] ?? null;
            $data['status_keberangkatan_id'] = $data['status_keberangkatan_id'] ?? null;
            $data['durasi_mekkah'] = $data['durasi_mekkah'] ?? 4;
            $data['durasi_madinah'] = $data['durasi_madinah'] ?? 4;
            $data['durasi_transit'] = $data['durasi_transit'] ?? 1;
            $data['harga_visa'] = $data['harga_visa'] ?? 0;
            $data['harga_handling'] = $data['harga_handling'] ?? 0;
            $data['harga_muthowwif'] = $data['harga_muthowwif'] ?? 0;

            // Jika include_tur = false, set paket_tour_id menjadi null
            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            // Extract perlengkapan data
            $perlengkapanData = $data['perlengkapans'] ?? [];
            unset($data['perlengkapans']);

            // Create produk
            $produk = ProdukPaket::create($data);

            // Sync perlengkapan
            if (!empty($perlengkapanData)) {
                $this->syncPerlengkapans($produk->id_produk, $perlengkapanData);
            }

            return $produk->fresh();
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $produk = $this->getById($id);

            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;

            // Jika include_tur = false, set paket_tour_id menjadi null
            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            // Extract perlengkapan data
            $perlengkapanData = $data['perlengkapans'] ?? [];
            unset($data['perlengkapans']);

            // Update produk
            $produk->update($data);

            // Sync perlengkapan
            if (!empty($perlengkapanData)) {
                $this->syncPerlengkapans($id, $perlengkapanData);
            } else {
                // Hapus semua perlengkapan jika tidak ada yang dipilih
                PaketProdukPerlengkapan::where('id_produk', $id)->delete();
            }

            return $produk->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $produk = $this->getById($id);
            $nama = $produk->nama_produk;
            $produk->delete();
            return $nama;
        });
    }

    public function toggleStatus($id)
    {
        return DB::transaction(function () use ($id) {
            $produk = $this->getById($id);
            $produk->is_active = !$produk->is_active;
            $produk->save();

            return [
                'nama' => $produk->nama_produk,
                'status' => $produk->is_active ? 'diaktifkan' : 'dinonaktifkan',
                'is_active' => $produk->is_active
            ];
        });
    }

    public function updateStatusKeberangkatan($id, $statusId)
    {
        return DB::transaction(function () use ($id, $statusId) {
            $produk = $this->getById($id);
            $produk->update(['status_keberangkatan_id' => $statusId]);
            return $produk->fresh();
        });
    }

    public function syncPerlengkapans($produkId, array $perlengkapanData)
    {
        return DB::transaction(function () use ($produkId, $perlengkapanData) {
            // Hapus semua relasi lama
            PaketProdukPerlengkapan::where('id_produk', $produkId)->delete();

            foreach ($perlengkapanData as $item) {
                if (!empty($item['id_perlengkapan'])) {
                    PaketProdukPerlengkapan::create([
                        'id_produk' => $produkId,
                        'id_perlengkapan' => $item['id_perlengkapan'],
                        'kuantitas' => $item['kuantitas'] ?? 1,
                        'catatan' => $item['catatan'] ?? null,
                    ]);
                }
            }

            return true;
        });
    }

    public function getPerlengkapanByProduk($produkId)
    {
        return PaketProdukPerlengkapan::with('perlengkapan')
                                     ->where('id_produk', $produkId)
                                     ->get();
    }

    private function generateKodeProduk()
    {
        $prefix = 'PKT';
        $year = date('Y');
        $last = ProdukPaket::where('kode_produk', 'like', "{$prefix}-%")->count();
        $number = str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$number}-{$year}";
    }
}
