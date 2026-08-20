<?php

namespace App\Services;

use App\Models\PaketTour;
use Illuminate\Support\Facades\DB;

class PaketTourService
{
    public function getAll(array $filters = [])
    {
        $query = PaketTour::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kota_tujuan', 'like', "%{$search}%")
                  ->orWhere('negara', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return PaketTour::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['harga_include'] = $data['harga_include'] ?? true;
            $data['harga_per_orang'] = $data['harga_per_orang'] ?? 0;
            
            return PaketTour::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $paketTour = $this->getById($id);
            $data['harga_include'] = $data['harga_include'] ?? true;
            $paketTour->update($data);
            return $paketTour->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $paketTour = $this->getById($id);
            $nama = $paketTour->kota_tujuan ?? 'Tour';
            $paketTour->delete();
            return $nama;
        });
    }

    public function getPaketTourOptions()
    {
        return PaketTour::orderBy('kota_tujuan')->get(['id_paket_tour', 'kota_tujuan', 'negara', 'durasi_hari', 'harga_per_orang']);
    }
}