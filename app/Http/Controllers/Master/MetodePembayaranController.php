<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\MetodePembayaranService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MetodePembayaranController extends Controller
{
    protected $service;

    public function __construct(MetodePembayaranService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('metode-pembayarans.table', compact('data'));
        }

        return view('metode-pembayarans.index', compact('data'));
    }

    public function create()
    {
        return view('metode-pembayarans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_bank' => 'required|string|max:10|unique:metode_pembayarans,kode_bank',
            'nama_bank' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:20',
            'atas_nama' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $metode = $this->service->create($validated);

        return redirect()->route('metode-pembayarans.index')
            ->with('success', "Metode pembayaran '{$metode->nama_bank}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $metode = $this->service->getById($id);
        return view('metode-pembayarans.show', compact('metode'));
    }

    public function edit($id)
    {
        $metode = $this->service->getById($id);
        return view('metode-pembayarans.edit', compact('metode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_bank' => ['required', 'string', 'max:10', Rule::unique('metode_pembayarans', 'kode_bank')->ignore($id, 'id_metode')],
            'nama_bank' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:20',
            'atas_nama' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $metode = $this->service->update($id, $validated);

        return redirect()->route('metode-pembayarans.index')
            ->with('success', "Metode pembayaran '{$metode->nama_bank}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('metode-pembayarans.index')
            ->with('success', "Metode pembayaran '{$nama}' berhasil dihapus!");
    }

    public function toggleStatus($id)
    {
        try {
            $result = $this->service->toggleStatus($id);

            return redirect()->back()
                ->with('success', "Metode pembayaran '{$result['nama']}' berhasil {$result['status']}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status metode pembayaran: ' . $e->getMessage());
        }
    }
}
