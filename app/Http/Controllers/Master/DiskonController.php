<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DiskonService;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    protected $service;

    public function __construct(DiskonService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('diskons.table', compact('data'));
        }

        return view('diskons.index', compact('data'));
    }

    public function create()
    {
        $produkOptions = $this->service->getProdukOptions();
        return view('diskons.create', compact('produkOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_diskon' => 'required|string|max:100',
            'nilai_diskon' => 'required|integer|min:0',
            'berlaku_untuk_produk' => 'nullable|string|max:100',
            'kuota' => 'nullable|integer|min:1',
            'sudah_digunakan' => 'nullable|integer|min:0',
        ]);

        $validated['sudah_digunakan'] = $validated['sudah_digunakan'] ?? 0;

        $diskon = $this->service->create($validated);

        return redirect()->route('master.diskon.index')
            ->with('success', "Diskon '{$diskon->nama_diskon}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $diskon = $this->service->getById($id);
        return view('diskons.show', compact('diskon'));
    }

    public function edit($id)
    {
        $diskon = $this->service->getById($id);
        $produkOptions = $this->service->getProdukOptions();
        return view('diskons.edit', compact('diskon', 'produkOptions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_diskon' => 'required|string|max:100',
            'nilai_diskon' => 'required|integer|min:0',
            'berlaku_untuk_produk' => 'nullable|string|max:100',
            'kuota' => 'nullable|integer|min:1',
            'sudah_digunakan' => 'nullable|integer|min:0',
        ]);

        $validated['sudah_digunakan'] = $validated['sudah_digunakan'] ?? 0;

        $diskon = $this->service->update($id, $validated);

        return redirect()->route('master.diskon.index')
            ->with('success', "Diskon '{$diskon->nama_diskon}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.diskon.index')
            ->with('success', "Diskon '{$nama}' berhasil dihapus!");
    }
}
