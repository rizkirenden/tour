<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\MetodePembayaranService;
use App\Models\MetodePembayaran;
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
        $filters = $request->only(['search', 'jenis_pembayaran']);
        $data = $this->service->getAll($filters);

        $jenisList = [
            '' => 'Semua Jenis',
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Tunai',
            'e_wallet' => 'E-Wallet'
        ];

        if ($request->ajax()) {
            return view('metode-pembayarans.table', compact('data', 'jenisList'));
        }

        return view('metode-pembayarans.index', compact('data', 'jenisList'));
    }

    public function create()
    {
        $jenisList = [
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Tunai',
            'e_wallet' => 'E-Wallet'
        ];
        return view('metode-pembayarans.create', compact('jenisList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'jenis_pembayaran' => ['required', 'string', Rule::in(['bank_transfer', 'cash', 'e_wallet'])],
            'is_active' => 'nullable|boolean',
        ];

        if ($request->jenis_pembayaran === 'bank_transfer') {
            $rules['kode_bank'] = 'required|string|max:10|unique:metode_pembayarans,kode_bank';
            $rules['nama_bank'] = 'required|string|max:50';
            $rules['nomor_rekening'] = 'required|string|max:20';
            $rules['atas_nama'] = 'required|string|max:100';
        } elseif ($request->jenis_pembayaran === 'e_wallet') {
            $rules['e_wallet_type'] = 'required|string|max:50|in:OVO,GoPay,DANA,LinkAja,ShopeePay';
            $rules['nomor_telepon'] = 'required|string|max:20';
            $rules['nama_bank'] = 'nullable|string|max:50';
            $rules['kode_bank'] = 'nullable|string|max:10|unique:metode_pembayarans,kode_bank';
        }

        $validated = $request->validate($rules);

        if ($request->jenis_pembayaran === 'cash') {
            $validated['kode_bank'] = 'CASH';
            $validated['nama_bank'] = 'Cash / Tunai';
            $validated['nomor_rekening'] = null;
            $validated['atas_nama'] = null;
        }

        if (!isset($validated['kode_bank']) || empty($validated['kode_bank'])) {
            if ($request->jenis_pembayaran === 'e_wallet') {
                $validated['kode_bank'] = strtoupper(substr($request->e_wallet_type, 0, 4)) . rand(100, 999);
            }
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $metode = $this->service->create($validated);

        return redirect()->route('master.metode-pembayaran.index')
            ->with('success', "Metode pembayaran '{$metode->display_name}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $metode = $this->service->getById($id);
        return view('metode-pembayarans.show', compact('metode'));
    }

    public function edit($id)
    {
        $metode = $this->service->getById($id);
        $jenisList = [
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Tunai',
            'e_wallet' => 'E-Wallet'
        ];
        return view('metode-pembayarans.edit', compact('metode', 'jenisList'));
    }

    public function update(Request $request, $id)
    {
        $metode = $this->service->getById($id);

        $rules = [
            'jenis_pembayaran' => ['required', 'string', Rule::in(['bank_transfer', 'cash', 'e_wallet'])],
            'is_active' => 'nullable|boolean',
        ];

        if ($request->jenis_pembayaran === 'bank_transfer') {
            $rules['kode_bank'] = ['required', 'string', 'max:10', Rule::unique('metode_pembayarans', 'kode_bank')->ignore($id, 'id_metode')];
            $rules['nama_bank'] = 'required|string|max:50';
            $rules['nomor_rekening'] = 'required|string|max:20';
            $rules['atas_nama'] = 'required|string|max:100';
        } elseif ($request->jenis_pembayaran === 'e_wallet') {
            $rules['e_wallet_type'] = 'required|string|max:50|in:OVO,GoPay,DANA,LinkAja,ShopeePay';
            $rules['nomor_telepon'] = 'required|string|max:20';
            $rules['nama_bank'] = 'nullable|string|max:50';
            $rules['kode_bank'] = ['nullable', 'string', 'max:10', Rule::unique('metode_pembayarans', 'kode_bank')->ignore($id, 'id_metode')];
        }

        $validated = $request->validate($rules);

        if ($request->jenis_pembayaran === 'cash') {
            $validated['kode_bank'] = 'CASH';
            $validated['nama_bank'] = 'Cash / Tunai';
            $validated['nomor_rekening'] = null;
            $validated['atas_nama'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $updated = $this->service->update($id, $validated);

        return redirect()->route('master.metode-pembayaran.index')
            ->with('success', "Metode pembayaran '{$updated->display_name}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        try {
            $nama = $this->service->delete($id);
            return redirect()->route('master.metode-pembayaran.index')
                ->with('success', "Metode pembayaran '{$nama}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
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
