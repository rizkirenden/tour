<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\HotelService;
use App\Models\Kamar;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected $service;

    public function __construct(HotelService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('hotels.table', compact('data'));
        }

        return view('hotels.index', compact('data'));
    }

    public function create()
    {
        return view('hotels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:100',
            'lokasi' => 'nullable|string',
            'tipe_hotel' => 'nullable|string',
            'bintang' => 'nullable|integer|min:1|max:5',
            'negara' => 'nullable|string|max:50',
            'kota' => 'required|string|max:50',
            'fasilitas' => 'nullable|string',
            'kamars' => 'required|array|min:1',
            'kamars.*.tipe_kamar' => 'required|string|max:50',
            'kamars.*.kapasitas' => 'required|integer|min:1',
            'kamars.*.jumlah_kamar' => 'nullable|integer|min:1',
            'kamars.*.harga_per_malam' => 'nullable|numeric|min:0',
            'kamars.*.fasilitas_kamar' => 'nullable|string',
        ]);

        $hotel = $this->service->create($validated);

        return redirect()->route('master.hotel.index')
            ->with('success', "Hotel '{$hotel->nama_hotel}' berhasil ditambahkan dengan " . $hotel->kamars->count() . ' tipe kamar!');
    }

    public function show($id)
    {
        $hotel = $this->service->getById($id);
        return view('hotels.show', compact('hotel'));
    }

    public function edit($id)
    {
        $hotel = $this->service->getById($id);
        return view('hotels.edit', compact('hotel'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:100',
            'lokasi' => 'nullable|string',
            'tipe_hotel' => 'nullable|string',
            'bintang' => 'nullable|integer|min:1|max:5',
            'negara' => 'nullable|string|max:50',
            'kota' => 'required|string|max:50',
            'fasilitas' => 'nullable|string',
            'kamars' => 'array|min:1',
            'kamars.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'kamars.*.tipe_kamar' => 'required|string|max:50',
            'kamars.*.kapasitas' => 'required|integer|min:1',
            'kamars.*.jumlah_kamar' => 'nullable|integer|min:1',
            'kamars.*.harga_per_malam' => 'nullable|numeric|min:0',
            'kamars.*.fasilitas_kamar' => 'nullable|string',
        ]);

        $hotel = $this->service->update($id, $validated);

        return redirect()->route('master.hotel.index')
            ->with('success', "Hotel '{$hotel->nama_hotel}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.hotel.index')
            ->with('success', "Hotel '{$nama}' berhasil dihapus!");
    }

    // Method untuk manage kamar
    public function kamarIndex($hotelId)
    {
        $hotel = $this->service->getById($hotelId);
        return view('hotels.kamar.index', compact('hotel'));
    }

    public function kamarStore(Request $request, $hotelId)
    {
        $validated = $request->validate([
            'tipe_kamar' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
            'jumlah_kamar' => 'required|integer|min:1',
            'harga_per_malam' => 'nullable|numeric|min:0',
            'fasilitas_kamar' => 'nullable|string',
        ]);

        $validated['id_hotel'] = $hotelId;
        Kamar::create($validated);

        return redirect()->route('master.hotel.kamar.index', $hotelId)
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function kamarUpdate(Request $request, $hotelId, $kamarId)
    {
        $validated = $request->validate([
            'tipe_kamar' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
            'jumlah_kamar' => 'required|integer|min:1',
            'harga_per_malam' => 'nullable|numeric|min:0',
            'fasilitas_kamar' => 'nullable|string',
        ]);

        $kamar = Kamar::where('id_kamar', $kamarId)->where('id_hotel', $hotelId)->firstOrFail();
        $kamar->update($validated);

        return redirect()->route('master.hotel.kamar.index', $hotelId)
            ->with('success', 'Kamar berhasil diperbarui!');
    }

    public function kamarDestroy($hotelId, $kamarId)
    {
        $kamar = Kamar::where('id_kamar', $kamarId)->where('id_hotel', $hotelId)->firstOrFail();
        $kamar->delete();

        return redirect()->route('master.hotel.kamar.index', $hotelId)
            ->with('success', 'Kamar berhasil dihapus!');
    }
}
