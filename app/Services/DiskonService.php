<?php

namespace App\Services;

use App\Models\Diskon;
use App\Models\DiskonRiwayat;
use App\Models\ProdukPaket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DiskonService
{
    public function getAll(array $filters = [])
    {
        $query = Diskon::withCount('riwayats');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_diskon', 'like', "%{$search}%")
                  ->orWhere('berlaku_untuk_produk', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Diskon::with(['riwayats'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['sudah_digunakan'] = $data['sudah_digunakan'] ?? 0;
            $data['reset_count'] = 0;
            return Diskon::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $diskon = $this->getById($id);
            $data['sudah_digunakan'] = $data['sudah_digunakan'] ?? 0;
            $diskon->update($data);
            return $diskon->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $diskon = $this->getById($id);
            $nama = $diskon->nama_diskon;

            // Hapus semua riwayat terkait
            $diskon->riwayats()->delete();

            $diskon->delete();
            return $nama;
        });
    }

    public function resetDiskon($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $diskon = $this->getById($id);

            // Simpan data sebelum reset ke riwayat
            DiskonRiwayat::create([
                'id_diskon' => $diskon->id_diskon,
                'nama_diskon' => $diskon->nama_diskon,
                'nilai_diskon' => $diskon->nilai_diskon,
                'berlaku_untuk_produk' => $diskon->berlaku_untuk_produk,
                'kuota' => $diskon->kuota,
                'sudah_digunakan' => $diskon->sudah_digunakan,
                'kuota_baru' => $data['kuota_baru'] ?? null,
                'reset_ke' => ($diskon->reset_count ?? 0) + 1,
                'catatan' => $data['catatan'] ?? 'Reset kuota diskon',
                'direset_oleh' => Auth::user()->name ?? 'System',
            ]);

            // Reset data diskon
            $diskon->update([
                'sudah_digunakan' => 0,
                'kuota' => $data['kuota_baru'] ?? $diskon->kuota,
                'reset_count' => ($diskon->reset_count ?? 0) + 1,
            ]);

            return $diskon->fresh();
        });
    }

    public function getProdukOptions()
    {
        $produk = ProdukPaket::all();
        $options = [
            '' => 'Semua Produk',
        ];

        foreach ($produk as $p) {
            $options[$p->id_produk] = $p->nama_produk;
        }

        return $options;
    }

    public function getAvailableDiskon($produkId = null)
    {
        $query = Diskon::where(function($q) {
            $q->whereNull('kuota')
              ->orWhereRaw('kuota > sudah_digunakan');
        });

        if ($produkId) {
            $query->where(function($q) use ($produkId) {
                $q->where('berlaku_untuk_produk', $produkId)
                  ->orWhereNull('berlaku_untuk_produk')
                  ->orWhere('berlaku_untuk_produk', '');
            });
        }

        return $query->orderBy('nilai_diskon', 'desc')->get();
    }

    /**
     * Get riwayat reset berdasarkan ID diskon
     */
    public function getRiwayatByDiskonId($id)
    {
        return DiskonRiwayat::where('id_diskon', $id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
    }
}
