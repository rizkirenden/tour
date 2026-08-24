<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Perlengkapan;
use App\Services\PerlengkapanService;
use Illuminate\Http\Request;

class PerlengkapanController extends Controller
{
    protected $service;

    public function __construct(PerlengkapanService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('perlengkapans.table', compact('data'));
        }

        return view('perlengkapans.index', compact('data'));
    }

    public function create()
    {
        $kategoriOptions = $this->service->getKategoriOptions();
        return view('perlengkapans.create', compact('kategoriOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perlengkapan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:20',
            'kategori' => 'nullable|in:Koper,Pakaian,Aksesoris,Dokumen,Lainnya',
        ]);

        $perlengkapan = $this->service->create($validated);

        return redirect()->route('master.perlengkapan.index')
            ->with('success', "Perlengkapan '{$perlengkapan->nama_perlengkapan}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $perlengkapan = $this->service->getById($id);
        return view('perlengkapans.show', compact('perlengkapan'));
    }

    public function edit($id)
    {
        $perlengkapan = $this->service->getById($id);
        $kategoriOptions = $this->service->getKategoriOptions();
        return view('perlengkapans.edit', compact('perlengkapan', 'kategoriOptions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_perlengkapan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:20',
            'kategori' => 'nullable|in:Koper,Pakaian,Aksesoris,Dokumen,Lainnya',
        ]);

        $perlengkapan = $this->service->update($id, $validated);

        return redirect()->route('master.perlengkapan.index')
            ->with('success', "Perlengkapan '{$perlengkapan->nama_perlengkapan}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.perlengkapan.index')
            ->with('success', "Perlengkapan '{$nama}' berhasil dihapus!");
    }
}
