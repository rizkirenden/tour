<?php
// app/Http/Controllers/Master/DokumenController.php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DokumenService;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    protected $dokumenService;

    public function __construct(DokumenService $dokumenService)
    {
        $this->dokumenService = $dokumenService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->dokumenService->getAll();
        return view('dokumen.index', $data);
    }

    /**
     * Upload dokumen
     */
    public function upload(Request $request)
    {
        $jenis = $request->input('jenis');

        // Validasi
        $validator = $this->dokumenService->validateUpload($request, $jenis);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Cek ukuran file secara manual jika perlu
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileSize = $file->getSize() / 1024 / 1024; // Convert ke MB
                
                if ($fileSize > 20) {
                    return redirect()->back()
                        ->with('error', 'Ukuran file terlalu besar. Maksimal 20MB.');
                }
            }

            $data = [
                'nama_penandatangan' => $request->nama_penandatangan,
                'jabatan' => $request->jabatan,
            ];

            $this->dokumenService->upload($jenis, $request->file('file'), $data);

            $messages = [
                'logo' => 'Logo berhasil diupload!',
                'ttd' => 'TTD berhasil diupload!',
                'cap' => 'Cap perusahaan berhasil diupload!',
            ];

            return redirect()->route('master.dokumen.index')
                ->with('success', $messages[$jenis]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    /**
     * Delete dokumen
     */
    public function delete($id)
    {
        try {
            $this->dokumenService->delete($id);
            return redirect()->route('master.dokumen.index')
                ->with('success', 'Dokumen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}