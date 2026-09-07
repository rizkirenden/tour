{{-- resources/views/dokumen/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dokumen Perusahaan - Arrum Tour')
@section('page-title', 'Dokumen Perusahaan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.dokumen.index') }}" class="text-gray-500 hover:text-yellow-600">Master</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Dokumen</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-sm font-semibold text-gray-700">Kelola Dokumen Perusahaan</h5>
                <p class="text-xs text-gray-400 mt-0.5">Upload Logo, TTD, dan Cap Perusahaan</p>
            </div>

            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- LOGO -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-3 text-center">
                            <i class="fas fa-image text-yellow-500 mr-2"></i> Logo Perusahaan
                        </h6>

                        <div
                            class="bg-gray-50 rounded-lg p-4 mb-3 text-center min-h-[120px] flex items-center justify-center">
                            @if ($logo)
                                <div>
                                    <img src="{{ $logo->url }}" alt="Logo"
                                        class="max-h-32 w-auto mx-auto object-contain">
                                    <p class="text-xs text-gray-400 mt-2">Upload:
                                        {{ $logo->created_at->format('d M Y H:i') }}</p>
                                </div>
                            @else
                                <div>
                                    <i class="fas fa-image text-4xl text-gray-300 block mb-2"></i>
                                    <p class="text-sm text-gray-400">Belum ada logo</p>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('master.dokumen.upload') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-3">
                            @csrf
                            <input type="hidden" name="jenis" value="logo">
                            <div>
                                <input type="file" name="file" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 cursor-pointer">
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                                <i class="fas fa-upload mr-2"></i> Upload Logo
                            </button>
                        </form>

                        @if ($logo)
                            <form action="{{ route('master.dokumen.delete', $logo->id_dokumen) }}" method="POST"
                                class="mt-2" onsubmit="return confirm('Yakin ingin menghapus logo ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i> Hapus Logo
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- TTD -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-3 text-center">
                            <i class="fas fa-pen-fancy text-blue-500 mr-2"></i> Tanda Tangan (TTD)
                        </h6>

                        <div
                            class="bg-gray-50 rounded-lg p-4 mb-3 text-center min-h-[120px] flex items-center justify-center">
                            @if ($ttd)
                                <div>
                                    <img src="{{ $ttd->url }}" alt="TTD"
                                        class="max-h-24 w-auto mx-auto object-contain">
                                    @if ($ttd->nama_penandatangan)
                                        <p class="text-sm font-medium text-gray-700 mt-2">{{ $ttd->nama_penandatangan }}</p>
                                    @endif
                                    @if ($ttd->jabatan)
                                        <p class="text-xs text-gray-500">{{ $ttd->jabatan }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">Upload:
                                        {{ $ttd->created_at->format('d M Y H:i') }}</p>
                                </div>
                            @else
                                <div>
                                    <i class="fas fa-pen-fancy text-4xl text-gray-300 block mb-2"></i>
                                    <p class="text-sm text-gray-400">Belum ada TTD</p>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('master.dokumen.upload') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-3">
                            @csrf
                            <input type="hidden" name="jenis" value="ttd">
                            <div>
                                <input type="file" name="file" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            </div>
                            <div>
                                <input type="text" name="nama_penandatangan" placeholder="Nama Penandatangan"
                                    value="{{ old('nama_penandatangan', $ttd->nama_penandatangan ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <input type="text" name="jabatan" placeholder="Jabatan"
                                    value="{{ old('jabatan', $ttd->jabatan ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                                <i class="fas fa-upload mr-2"></i> Upload TTD
                            </button>
                        </form>

                        @if ($ttd)
                            <form action="{{ route('master.dokumen.delete', $ttd->id_dokumen) }}" method="POST"
                                class="mt-2" onsubmit="return confirm('Yakin ingin menghapus TTD ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i> Hapus TTD
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- CAP -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-3 text-center">
                            <i class="fas fa-stamp text-green-500 mr-2"></i> Cap Perusahaan
                        </h6>

                        <div
                            class="bg-gray-50 rounded-lg p-4 mb-3 text-center min-h-[120px] flex items-center justify-center">
                            @if ($cap)
                                <div>
                                    <img src="{{ $cap->url }}" alt="Cap"
                                        class="max-h-32 w-auto mx-auto object-contain">
                                    <p class="text-xs text-gray-400 mt-2">Upload:
                                        {{ $cap->created_at->format('d M Y H:i') }}</p>
                                </div>
                            @else
                                <div>
                                    <i class="fas fa-stamp text-4xl text-gray-300 block mb-2"></i>
                                    <p class="text-sm text-gray-400">Belum ada cap</p>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('master.dokumen.upload') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-3">
                            @csrf
                            <input type="hidden" name="jenis" value="cap">
                            <div>
                                <input type="file" name="file" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                <i class="fas fa-upload mr-2"></i> Upload Cap
                            </button>
                        </form>

                        @if ($cap)
                            <form action="{{ route('master.dokumen.delete', $cap->id_dokumen) }}" method="POST"
                                class="mt-2" onsubmit="return confirm('Yakin ingin menghapus cap ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i> Hapus Cap
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4">Preview Dokumen</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-2">Logo</p>
                            @if ($logo)
                                <img src="{{ $logo->url }}" alt="Logo" class="max-h-20 mx-auto">
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-2">TTD</p>
                            @if ($ttd)
                                <img src="{{ $ttd->url }}" alt="TTD" class="max-h-16 mx-auto">
                                <p class="text-xs text-gray-600 mt-1">{{ $ttd->nama_penandatangan ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $ttd->jabatan ?? '-' }}</p>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-2">Cap</p>
                            @if ($cap)
                                <img src="{{ $cap->url }}" alt="Cap" class="max-h-16 mx-auto">
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
