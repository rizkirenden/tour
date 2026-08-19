<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\KotaAsalService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KotaAsalController extends Controller
{
    protected $service;

    public function __construct(KotaAsalService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('kota-asals.table', compact('data'));
        }

        return view('kota-asals.index', compact('data'));
    }

    public function create()
    {
        return view('kota-asals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kota' => 'required|string|max:50|unique:kota_asals,nama_kota',
            'provinsi' => 'nullable|string|max:50',
            'pulau' => 'nullable|string|max:20',
            'bandara_terdekat' => 'nullable|string|max:50',
        ]);

        $kota = $this->service->create($validated);

        return redirect()->route('master.kota-asal.index')
            ->with('success', "Kota asal '{$kota->nama_kota}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $kota = $this->service->getById($id);
        return view('kota-asals.show', compact('kota'));
    }

    public function edit($id)
    {
        $kota = $this->service->getById($id);
        return view('kota-asals.edit', compact('kota'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kota' => ['required', 'string', 'max:50', Rule::unique('kota_asals', 'nama_kota')->ignore($id, 'id_kota')],
            'provinsi' => 'nullable|string|max:50',
            'pulau' => 'nullable|string|max:20',
            'bandara_terdekat' => 'nullable|string|max:50',
        ]);

        $kota = $this->service->update($id, $validated);

        return redirect()->route('master.kota-asal.index')
            ->with('success', "Kota asal '{$kota->nama_kota}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.kota-asal.index')
            ->with('success', "Kota asal '{$nama}' berhasil dihapus!");
    }
}
