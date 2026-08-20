<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\PaketTourService;
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
        return view('paket-tours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kota_tujuan' => 'nullable|string|max:50',
            'negara' => 'nullable|string|max:50',
            'durasi_hari' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
            'harga_include' => 'nullable|boolean',
            'harga_per_orang' => 'nullable|integer|min:0',
        ]);

        $paketTour = $this->service->create($validated);

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour '{$paketTour->kota_tujuan}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $paketTour = $this->service->getById($id);
        return view('paket-tours.show', compact('paketTour'));
    }

    public function edit($id)
    {
        $paketTour = $this->service->getById($id);
        return view('paket-tours.edit', compact('paketTour'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kota_tujuan' => 'nullable|string|max:50',
            'negara' => 'nullable|string|max:50',
            'durasi_hari' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
            'harga_include' => 'nullable|boolean',
            'harga_per_orang' => 'nullable|integer|min:0',
        ]);

        $paketTour = $this->service->update($id, $validated);

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