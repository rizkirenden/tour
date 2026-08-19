<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\MaskapaiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaskapaiController extends Controller
{
    protected $service;

    public function __construct(MaskapaiService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('maskapais.table', compact('data'));
        }

        return view('maskapais.index', compact('data'));
    }

    public function create()
    {
        return view('maskapais.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_maskapai' => 'required|string|max:10|unique:maskapais,kode_maskapai',
            'nama_maskapai' => 'required|string|max:50',
            'tipe_penerbangan' => 'required|in:Domestik,Internasional',
        ]);

        $maskapai = $this->service->create($validated);

        return redirect()->route('master.maskapai.index')
            ->with('success', "Maskapai '{$maskapai->nama_maskapai}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $maskapai = $this->service->getById($id);
        return view('maskapais.show', compact('maskapai'));
    }

    public function edit($id)
    {
        $maskapai = $this->service->getById($id);
        return view('maskapais.edit', compact('maskapai'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_maskapai' => ['required', 'string', 'max:10', Rule::unique('maskapais', 'kode_maskapai')->ignore($id, 'id_maskapai')],
            'nama_maskapai' => 'required|string|max:50',
            'tipe_penerbangan' => 'required|in:Domestik,Internasional',
        ]);

        $maskapai = $this->service->update($id, $validated);

        return redirect()->route('master.maskapai.index')
            ->with('success', "Maskapai '{$maskapai->nama_maskapai}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.maskapai.index')
            ->with('success', "Maskapai '{$nama}' berhasil dihapus!");
    }
}
