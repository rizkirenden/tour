@php
    $fmt = fn($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Keterangan</th>
                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Nominal</th>
                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($pengeluaran['items'] as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-3 py-2 text-gray-700">{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td class="px-3 py-2">
                        <a href="{{ route('transaksional.rekap-keuangan.show', $item->id_pengeluaran_lain) }}"
                            class="font-medium text-blue-600 hover:text-blue-800">
                            {{ $item->nama_pengeluaran }}
                        </a>
                    </td>
                    <td class="px-3 py-2">
                        @if ($item->kategori)
                            <span
                                class="inline-flex px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">{{ $item->kategori }}</span>
                        @else
                            <span class="text-xs text-gray-400 italic">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-gray-600 text-xs">
                        {{ \Illuminate\Support\Str::limit($item->keterangan ?? '-', 60) }}</td>
                    <td class="px-3 py-2 text-right font-bold text-red-600">{{ $item->nominal_formatted }}</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('transaksional.rekap-keuangan.show', $item->id_pengeluaran_lain) }}"
                                class="p-1 text-blue-500 hover:bg-blue-50 rounded" title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('transaksional.rekap-keuangan.edit', $item->id_pengeluaran_lain) }}"
                                class="p-1 text-amber-500 hover:bg-amber-50 rounded" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button onclick="confirmHapusPengeluaran({{ $item->id_pengeluaran_lain }})"
                                class="p-1 text-red-500 hover:bg-red-50 rounded" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            <form id="delete-pengeluaran-{{ $item->id_pengeluaran_lain }}"
                                action="{{ route('transaksional.rekap-keuangan.pengeluaran.destroy', $item->id_pengeluaran_lain) }}"
                                method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                        <p>Belum ada pengeluaran lain-lain</p>
                        <p class="text-xs text-gray-400 mt-1">
                            <a href="{{ route('transaksional.rekap-keuangan.create') }}"
                                class="text-yellow-600 hover:underline">
                                Klik di sini untuk menambah
                            </a>
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($pengeluaran['items']->count() > 0)
            <tfoot class="bg-red-50 border-t-2 border-red-300">
                <tr class="font-bold">
                    <td colspan="4" class="px-3 py-3 text-right text-gray-700">TOTAL PENGELUARAN LAIN-LAIN</td>
                    <td class="px-3 py-3 text-right text-red-700">{{ $fmt($pengeluaran['total']) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>

@push('scripts')
    <script>
        function confirmHapusPengeluaran(id) {
            if (confirm('Yakin ingin menghapus pengeluaran ini?')) {
                document.getElementById('delete-pengeluaran-' + id).submit();
            }
        }
    </script>
@endpush
