<?php
// app/Services/DokumenService.php

namespace App\Services;

use App\Models\DokumenPerusahaan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokumenService
{
    /**
     * Get all dokumen for index page
     */
    public function getAll()
    {
        return [
            'logo' => DokumenPerusahaan::getLogo(),
            'ttd' => DokumenPerusahaan::getTtd(),
            'cap' => DokumenPerusahaan::getCap(),
        ];
    }

    /**
     * Get dokumen by jenis
     */
    public function getByJenis($jenis)
    {
        return DokumenPerusahaan::getByJenis($jenis);
    }

    /**
     * Upload dokumen
     */
    public function upload($jenis, UploadedFile $file, $data = [])
    {
        // Validasi jenis
        $allowedTypes = [DokumenPerusahaan::JENIS_LOGO, DokumenPerusahaan::JENIS_TTD, DokumenPerusahaan::JENIS_CAP];
        if (!in_array($jenis, $allowedTypes)) {
            throw new \Exception('Jenis dokumen tidak valid');
        }

        // Hapus file lama
        $old = DokumenPerusahaan::getByJenis($jenis);
        if ($old && $old->path && Storage::disk('public')->exists($old->path)) {
            Storage::disk('public')->delete($old->path);
            $old->delete();
        }

        // Upload file baru
        $filename = time() . '_' . $jenis . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen/' . $jenis, $filename, 'public');

        // Simpan ke database
        $dokumenData = [
            'jenis' => $jenis,
            'path' => $path,
        ];

        if ($jenis == DokumenPerusahaan::JENIS_TTD) {
            $dokumenData['nama_penandatangan'] = $data['nama_penandatangan'] ?? null;
            $dokumenData['jabatan'] = $data['jabatan'] ?? null;
        }

        return DokumenPerusahaan::create($dokumenData);
    }

    /**
     * Delete dokumen
     */
    public function delete($id)
    {
        $dokumen = DokumenPerusahaan::findOrFail($id);
        
        if ($dokumen->path && Storage::disk('public')->exists($dokumen->path)) {
            Storage::disk('public')->delete($dokumen->path);
        }
        
        $dokumen->delete();
        
        return true;
    }

    /**
     * Get dokumen base64 for PDF
     */
    public function getBase64($jenis)
    {
        $dokumen = $this->getByJenis($jenis);
        return $dokumen ? $dokumen->base64 : null;
    }

    /**
     * Get logo base64
     */
    public function getLogoBase64()
    {
        return $this->getBase64(DokumenPerusahaan::JENIS_LOGO);
    }

    /**
     * Get TTD base64
     */
    public function getTtdBase64()
    {
        return $this->getBase64(DokumenPerusahaan::JENIS_TTD);
    }

    /**
     * Get Cap base64
     */
    public function getCapBase64()
    {
        return $this->getBase64(DokumenPerusahaan::JENIS_CAP);
    }

    /**
     * Get TTD info (base64, nama, jabatan)
     */
    public function getTtdInfo()
    {
        $ttd = $this->getByJenis(DokumenPerusahaan::JENIS_TTD);
        if ($ttd) {
            return [
                'base64' => $ttd->base64,
                'nama' => $ttd->nama_penandatangan,
                'jabatan' => $ttd->jabatan,
            ];
        }
        return null;
    }

    /**
     * Validate upload request
     */
    public function validateUpload($request, $jenis)
    {
        $rules = [
            'file' => 'required|image|mimes:jpeg,png,jpg,svg', // Hapus max:2048
        ];

        if ($jenis == DokumenPerusahaan::JENIS_TTD) {
            $rules['nama_penandatangan'] = 'nullable|string|max:100';
            $rules['jabatan'] = 'nullable|string|max:100';
        }

        return Validator::make($request->all(), $rules);
    }
}