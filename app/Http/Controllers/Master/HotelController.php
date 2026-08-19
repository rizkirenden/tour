<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'kode_hotel' => 'required|string|max:20|unique:hotels,kode_hotel',
            'nama_hotel' => 'required|string|max:100',
            'lokasi' => 'nullable|string',
            'tipe_hotel' => 'nullable|string',
            'bintang' => 'nullable|integer|min:1|max:5',
            'tipe_kamar' => 'nullable|string|max:50',
            'harga_per_malam' => 'nullable|integer|min:0',
            'kapasitas' => 'nullable|integer|min:1',
            'negara' => 'nullable|string|max:50',
            'kota' => 'nullable|string|max:50',
            'fasilitas' => 'nullable|string',
        ]);

        $hotel = $this->service->create($validated);

        return redirect()->route('master.hotel.index')
            ->with('success', "Hotel '{$hotel->nama_hotel}' berhasil ditambahkan!");
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
            'kode_hotel' => ['required', 'string', 'max:20', Rule::unique('hotels', 'kode_hotel')->ignore($id, 'id_hotel')],
            'nama_hotel' => 'required|string|max:100',
            'lokasi' => 'nullable|string',
            'tipe_hotel' => 'nullable|string',
            'bintang' => 'nullable|integer|min:1|max:5',
            'tipe_kamar' => 'nullable|string|max:50',
            'harga_per_malam' => 'nullable|integer|min:0',
            'kapasitas' => 'nullable|integer|min:1',
            'negara' => 'nullable|string|max:50',
            'kota' => 'nullable|string|max:50',
            'fasilitas' => 'nullable|string',
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
}
