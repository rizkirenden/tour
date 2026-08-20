<?php

namespace App\Services;

use App\Models\PaketTour;
use App\Models\HotelPaketTour;
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

        return $query->with('hotels')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return PaketTour::findOrFail($id);
    }

    public function getByIdWithHotels($id)
    {
        return PaketTour::with(['hotels' => function($query) {
            $query->orderBy('hotel_paket_tour.urutan');
        }])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['harga_per_orang'] = $data['harga_per_orang'] ?? 0;
            unset($data['hotels']);
            return PaketTour::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $paketTour = $this->getById($id);
            unset($data['hotels']);
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

    public function syncHotels($paketTourId, array $hotels)
    {
        return DB::transaction(function () use ($paketTourId, $hotels) {
            HotelPaketTour::where('id_paket_tour', $paketTourId)->delete();

            foreach ($hotels as $hotel) {
                if (!empty($hotel['id_hotel'])) {
                    HotelPaketTour::create([
                        'id_paket_tour' => $paketTourId,
                        'id_hotel' => $hotel['id_hotel'],
                        'durasi_menginap' => $hotel['durasi_menginap'] ?? 1,
                        'harga_hotel' => $hotel['harga_hotel'] ?? 0,
                        'urutan' => $hotel['urutan'] ?? 0,
                        'catatan' => $hotel['catatan'] ?? null,
                    ]);
                }
            }

            return true;
        });
    }

    public function getPaketTourOptions()
    {
        return PaketTour::orderBy('kota_tujuan')->get(['id_paket_tour', 'kota_tujuan', 'negara', 'durasi_hari', 'harga_per_orang']);
    }
}
