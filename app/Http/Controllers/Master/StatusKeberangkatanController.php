<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\StatusKeberangkatanService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusKeberangkatanController extends Controller
{
    protected $service;

    public function __construct(StatusKeberangkatanService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('status-keberangkatans.table', compact('data'));
        }

        return view('status-keberangkatans.index', compact('data'));
    }

    public function create()
    {
        return view('status-keberangkatans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|max:50|unique:status_keberangkatans,nama_status',
            'keterangan' => 'nullable|string',
        ]);

        $status = $this->service->create($validated);

        return redirect()->route('master.status-keberangkatan.index')
            ->with('success', "Status keberangkatan '{$status->nama_status}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $status = $this->service->getById($id);
        return view('status-keberangkatans.show', compact('status'));
    }

    public function edit($id)
    {
        $status = $this->service->getById($id);
        return view('status-keberangkatans.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_status' => ['required', 'string', 'max:50', Rule::unique('status_keberangkatans', 'nama_status')->ignore($id, 'id_status')],
            'keterangan' => 'nullable|string',
        ]);

        $status = $this->service->update($id, $validated);

        return redirect()->route('master.status-keberangkatan.index')
            ->with('success', "Status keberangkatan '{$status->nama_status}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.status-keberangkatan.index')
            ->with('success', "Status keberangkatan '{$nama}' berhasil dihapus!");
    }
}
