<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\KategoriPengeluaranService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriPengeluaranController extends Controller
{
    protected $service;

    public function __construct(KategoriPengeluaranService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('kategori-pengeluarans.table', compact('data'));
        }

        return view('kategori-pengeluarans.index', compact('data'));
    }

    public function create()
    {
        return view('kategori-pengeluarans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_pengeluarans,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = $this->service->create($validated);

        return redirect()->route('master.kategori-pengeluaran.index')
            ->with('success', "Kategori pengeluaran '{$kategori->nama_kategori}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $kategori = $this->service->getById($id);
        return view('kategori-pengeluarans.show', compact('kategori'));
    }

    public function edit($id)
    {
        $kategori = $this->service->getById($id);
        return view('kategori-pengeluarans.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', Rule::unique('kategori_pengeluarans', 'nama_kategori')->ignore($id, 'id_kategori')],
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = $this->service->update($id, $validated);

        return redirect()->route('master.kategori-pengeluaran.index')
            ->with('success', "Kategori pengeluaran '{$kategori->nama_kategori}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.kategori-pengeluaran.index')
            ->with('success', "Kategori pengeluaran '{$nama}' berhasil dihapus!");
    }
}
