@extends('layouts.app')

@section('title', 'Detail Agent - Arrum Tour')
@section('page-title', 'Detail Agent')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.agent.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.agent.index') }}" class="text-gray-500 hover:text-yellow-600">Agent</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Detail</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Detail Agent</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap agent</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.agent.edit', $agent->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.agent.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $agent->name }}</h2>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-phone mr-1"></i> {{ $agent->phone ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-yellow-600">{{ $agent->jamaahs_count }}</p>
                            <p class="text-sm text-gray-500">Total Jamaah</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Agent
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Nama</dt>
                                <dd class="font-medium text-gray-700">{{ $agent->name }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">No. Telepon</dt>
                                <dd class="font-medium text-gray-700">{{ $agent->phone ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Dibuat pada</dt>
                                <dd class="font-medium text-gray-700">{{ $agent->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir diupdate</dt>
                                <dd class="font-medium text-gray-700">{{ $agent->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-users text-yellow-500 mr-2"></i> Jamaah Terbaru (maks. 10)
                        </h6>
                        @if ($jamaahs->count())
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Nama</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Produk</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                Fee Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($jamaahs as $j)
                                            <tr>
                                                <td class="px-3 py-2">
                                                    <a href="{{ route('transaksional.jamaah.show', $j->id_jamaah) }}"
                                                        class="text-gray-700 hover:text-yellow-600 font-medium">
                                                        {{ $j->nama_lengkap }}
                                                    </a>
                                                </td>
                                                <td class="px-3 py-2 text-gray-600">{{ $j->produk_paket ?? '-' }}</td>
                                                <td class="px-3 py-2 text-right text-gray-700">
                                                    {{ $j->fee_agent_formatted }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-400">Belum ada jamaah dari agent ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
