<?php
// app/Http/Controllers/Master/JenisPembayaranController.php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\JenisPembayaranService;
use Illuminate\Http\Request;

class JenisPembayaranController extends Controller
{
    protected $service;

    public function __construct(JenisPembayaranService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('jenis-pembayarans.table', compact('data'));
        }

        return view('jenis-pembayarans.index', compact('data'));
    }

    public function create()
    {
        return view('jenis-pembayarans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:jenis_pembayarans,nama',
            'keterangan' => 'nullable|string',
        ]);

        $jenis = $this->service->create($validated);

        return redirect()->route('master.jenis-pembayaran.index')
            ->with('success', "Jenis pembayaran '{$jenis->nama}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $jenis = $this->service->getById($id);
        return view('jenis-pembayarans.show', compact('jenis'));
    }

    public function edit($id)
    {
        $jenis = $this->service->getById($id);
        return view('jenis-pembayarans.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:jenis_pembayarans,nama,' . $id . ',id_jenis_pembayaran',
            'keterangan' => 'nullable|string',
        ]);

        $jenis = $this->service->update($id, $validated);

        return redirect()->route('master.jenis-pembayaran.index')
            ->with('success', "Jenis pembayaran '{$jenis->nama}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.jenis-pembayaran.index')
            ->with('success', "Jenis pembayaran '{$nama}' berhasil dihapus!");
    }
}
