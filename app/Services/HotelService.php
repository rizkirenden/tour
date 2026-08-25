<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;

class HotelService
{
    public function getAll(array $filters = [])
    {
        $query = Hotel::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_hotel', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%")
                  ->orWhere('negara', 'like', "%{$search}%")
                  ->orWhere('tipe_hotel', 'like', "%{$search}%");
            });
        }

        return $query->with('kamars')->orderBy('nama_hotel', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return Hotel::with('kamars')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $kamarData = $data['kamars'] ?? [];
            unset($data['kamars']);

            $hotel = Hotel::create($data);

            foreach ($kamarData as $kamar) {
                $kamar['id_hotel'] = $hotel->id_hotel;
                Kamar::create($kamar);
            }

            return $hotel->load('kamars');
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $hotel = $this->getById($id);

            $kamarData = $data['kamars'] ?? [];
            unset($data['kamars']);

            $hotel->update($data);

            if (!empty($kamarData)) {
                $existingIds = collect($kamarData)->pluck('id_kamar')->filter();

                if ($existingIds->isNotEmpty()) {
                    $hotel->kamars()->whereNotIn('id_kamar', $existingIds)->delete();
                } else {
                    $hotel->kamars()->delete();
                }

                foreach ($kamarData as $kamar) {
                    if (!empty($kamar['id_kamar'])) {
                        Kamar::where('id_kamar', $kamar['id_kamar'])
                            ->where('id_hotel', $hotel->id_hotel)
                            ->update($kamar);
                    } else {
                        $kamar['id_hotel'] = $hotel->id_hotel;
                        Kamar::create($kamar);
                    }
                }
            }

            return $hotel->load('kamars');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $hotel = $this->getById($id);
            $nama = $hotel->nama_hotel;
            $hotel->delete();
            return $nama;
        });
    }

    public function getKamarsByHotel($hotelId)
    {
        return Kamar::where('id_hotel', $hotelId)->get();
    }
}