<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\PaketTourService;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaketTourController extends Controller
{
    protected $service;

    public function __construct(PaketTourService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('paket-tours.table', compact('data'));
        }

        return view('paket-tours.index', compact('data'));
    }

    public function create()
    {
        $hotels = Hotel::orderBy('nama_hotel')->get();
        return view('paket-tours.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        // Ubah validasi: id_hotel tidak required, tapi jika ada harus valid
        $validated = $request->validate([
            'kota_tujuan' => 'nullable|string|max:50',
            'negara' => 'nullable|string|max:50',
            'durasi_hari' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
            'harga_per_orang' => 'nullable|integer|min:0',
            'hotels' => 'nullable|array',
            'hotels.*.id_hotel' => 'nullable|exists:hotels,id_hotel', // Ganti required jadi nullable
            'hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'hotels.*.harga_hotel' => 'nullable|integer|min:0',
            'hotels.*.urutan' => 'nullable|integer|min:0',
            'hotels.*.catatan' => 'nullable|string',
        ]);

        $paketTour = $this->service->create($validated);

        // Filter hotels yang memiliki id_hotel (tidak kosong)
        $hotels = [];
        if ($request->has('hotels') && is_array($request->hotels)) {
            foreach ($request->hotels as $hotel) {
                if (!empty($hotel['id_hotel'])) {
                    $hotels[] = $hotel;
                }
            }
        }

        if (!empty($hotels)) {
            $this->service->syncHotels($paketTour->id_paket_tour, $hotels);
        }

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour '{$paketTour->kota_tujuan}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $paketTour = $this->service->getByIdWithHotels($id);
        return view('paket-tours.show', compact('paketTour'));
    }

    public function edit($id)
    {
        $paketTour = $this->service->getByIdWithHotels($id);
        $hotels = Hotel::orderBy('nama_hotel')->get();
        return view('paket-tours.edit', compact('paketTour', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        // Ubah validasi: id_hotel tidak required, tapi jika ada harus valid
        $validated = $request->validate([
            'kota_tujuan' => 'nullable|string|max:50',
            'negara' => 'nullable|string|max:50',
            'durasi_hari' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
            'harga_per_orang' => 'nullable|integer|min:0',
            'hotels' => 'nullable|array',
            'hotels.*.id_hotel' => 'nullable|exists:hotels,id_hotel', // Ganti required jadi nullable
            'hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'hotels.*.harga_hotel' => 'nullable|integer|min:0',
            'hotels.*.urutan' => 'nullable|integer|min:0',
            'hotels.*.catatan' => 'nullable|string',
        ]);

        $paketTour = $this->service->update($id, $validated);

        // Filter hotels yang memiliki id_hotel (tidak kosong)
        $hotels = [];
        if ($request->has('hotels') && is_array($request->hotels)) {
            foreach ($request->hotels as $hotel) {
                if (!empty($hotel['id_hotel'])) {
                    $hotels[] = $hotel;
                }
            }
        }

        // Update relasi hotel
        if (!empty($hotels)) {
            $this->service->syncHotels($id, $hotels);
        } else {
            $this->service->syncHotels($id, []); // Hapus semua hotel jika tidak ada
        }

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour '{$nama}' berhasil dihapus!");
    }
}
