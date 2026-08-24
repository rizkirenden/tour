<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\JenisTransaksiService;
use Illuminate\Http\Request;

class JenisTransaksiController extends Controller
{
    protected $service;

    public function __construct(JenisTransaksiService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('jenis-transaksis.table', compact('data'));
        }

        return view('jenis-transaksis.index', compact('data'));
    }

    public function create()
    {
        return view('jenis-transaksis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:jenis_transaksis,nama',
            'keterangan' => 'nullable|string',
        ]);

        $jenis = $this->service->create($validated);

        return redirect()->route('master.jenis-transaksi.index')
            ->with('success', "Jenis transaksi '{$jenis->nama}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $jenis = $this->service->getById($id);
        return view('jenis-transaksis.show', compact('jenis'));
    }

    public function edit($id)
    {
        $jenis = $this->service->getById($id);
        return view('jenis-transaksis.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:jenis_transaksis,nama,' . $id . ',id_jenis',
            'keterangan' => 'nullable|string',
        ]);

        $jenis = $this->service->update($id, $validated);

        return redirect()->route('master.jenis-transaksi.index')
            ->with('success', "Jenis transaksi '{$jenis->nama}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.jenis-transaksi.index')
            ->with('success', "Jenis transaksi '{$nama}' berhasil dihapus!");
    }
}
