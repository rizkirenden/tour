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
        $tipeOptions = $this->service->getTipeOptions();
        return view('maskapais.create', compact('tipeOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_maskapai' => 'required|string|max:100|unique:maskapais,nama_maskapai',
            'tipe_penerbangan' => 'required|array|min:1',
            'tipe_penerbangan.*' => 'in:Domestik,Internasional',
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
        $tipeOptions = $this->service->getTipeOptions();
        return view('maskapais.edit', compact('maskapai', 'tipeOptions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_maskapai' => ['required', 'string', 'max:100', Rule::unique('maskapais', 'nama_maskapai')->ignore($id, 'id_maskapai')],
            'tipe_penerbangan' => 'required|array|min:1',
            'tipe_penerbangan.*' => 'in:Domestik,Internasional',
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
