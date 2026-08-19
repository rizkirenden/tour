<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Perlengkapan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerlengkapanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perlengkapan::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_perlengkapan', 'like', "%{$search}%")
                  ->orWhere('nama_perlengkapan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('nama_perlengkapan', 'asc')->paginate(10);

        if ($request->ajax()) {
            return view('perlengkapans.table', compact('data'));
        }

        return view('perlengkapans.index', compact('data'));
    }

    public function create()
    {
        $kategoriOptions = [
            'Koper' => 'Koper',
            'Pakaian' => 'Pakaian',
            'Aksesoris' => 'Aksesoris',
            'Dokumen' => 'Dokumen',
            'Lainnya' => 'Lainnya',
        ];
        return view('perlengkapans.create', compact('kategoriOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_perlengkapan' => 'required|string|max:20|unique:perlengkapans,kode_perlengkapan',
            'nama_perlengkapan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:20',
            'kategori' => 'nullable|in:Koper,Pakaian,Aksesoris,Dokumen,Lainnya',
        ]);

        $perlengkapan = Perlengkapan::create($validated);

        return redirect()->route('master.perlengkapan.index')
            ->with('success', "Perlengkapan '{$perlengkapan->nama_perlengkapan}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);
        return view('perlengkapans.show', compact('perlengkapan'));
    }

    public function edit($id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);
        $kategoriOptions = [
            'Koper' => 'Koper',
            'Pakaian' => 'Pakaian',
            'Aksesoris' => 'Aksesoris',
            'Dokumen' => 'Dokumen',
            'Lainnya' => 'Lainnya',
        ];
        return view('perlengkapans.edit', compact('perlengkapan', 'kategoriOptions'));
    }

    public function update(Request $request, $id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);

        $validated = $request->validate([
            'kode_perlengkapan' => ['required', 'string', 'max:20', Rule::unique('perlengkapans', 'kode_perlengkapan')->ignore($id, 'id_perlengkapan')],
            'nama_perlengkapan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:20',
            'kategori' => 'nullable|in:Koper,Pakaian,Aksesoris,Dokumen,Lainnya',
        ]);

        $perlengkapan->update($validated);

        return redirect()->route('master.perlengkapan.index')
            ->with('success', "Perlengkapan '{$perlengkapan->nama_perlengkapan}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);
        $nama = $perlengkapan->nama_perlengkapan;
        $perlengkapan->delete();

        return redirect()->route('master.perlengkapan.index')
            ->with('success', "Perlengkapan '{$nama}' berhasil dihapus!");
    }
}
