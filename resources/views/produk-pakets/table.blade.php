<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Produk
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga Dasar
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Durasi
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Include
                    Tur</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                    Keberangkatan</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $item)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-mono">
                            {{ $item->kode_produk ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $item->nama_produk }}</p>
                        @if ($item->deskripsi)
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($item->deskripsi, 50) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->kategori ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        {{ $item->harga_dasar_formatted }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm text-gray-600">{{ $item->durasi_hari }} Hari</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span
                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $item->include_tur ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $item->include_tur ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <!-- Status Keberangkatan - Dropdown dengan Warna -->
                        @php
                            $warna = $item->statusKeberangkatan ? $item->statusKeberangkatan->warna : '#6B7280';
                        @endphp
                        <select onchange="updateStatusKeberangkatan({{ $item->id_produk }}, this.value)"
                            class="px-2 py-1 rounded-lg text-xs font-medium border-0 focus:ring-2 focus:ring-yellow-500 transition-all duration-200 cursor-pointer"
                            style="background-color: {{ $warna }}20; color: {{ $warna }}; border: 1px solid {{ $warna }}40;">
                            <option value="">-- Pilih Status --</option>
                            @foreach ($statusKeberangkatans as $status)
                                @php
                                    $warnaStatus = $status->warna ?? '#6B7280';
                                @endphp
                                <option value="{{ $status->id_status }}"
                                    {{ $item->status_keberangkatan_id == $status->id_status ? 'selected' : '' }}
                                    style="background-color: {{ $warnaStatus }}20; color: {{ $warnaStatus }};">
                                    {{ $status->nama_status }}
                                </option>
                            @endforeach
                        </select>
                        <form id="status-keberangkatan-form-{{ $item->id_produk }}"
                            action="{{ route('master.produk.update-status-keberangkatan', $item->id_produk) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_keberangkatan_id"
                                id="status_keberangkatan_value_{{ $item->id_produk }}" value="">
                        </form>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <!-- Tombol Status Aktif/Nonaktif -->
                        <button onclick="toggleStatus({{ $item->id_produk }})"
                            class="px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 {{ $item->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                        <form id="status-form-{{ $item->id_produk }}"
                            action="{{ route('master.produk.toggle-status', $item->id_produk) }}" method="POST"
                            class="hidden">
                            @csrf
                            @method('PATCH')
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('master.produk.show', $item->id_produk) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('master.produk.edit', $item->id_produk) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_produk }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_produk }}"
                                action="{{ route('master.produk.destroy', $item->id_produk) }}" method="POST"
                                class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-inbox text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data produk</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Produk" untuk menambahkan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 px-2">
    {{ $data->withQueryString()->links() }}
</div>

@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm('Yakin ingin menghapus produk ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function toggleStatus(id) {
            if (confirm('Ubah status produk ini?')) {
                document.getElementById('status-form-' + id).submit();
            }
        }

        function updateStatusKeberangkatan(id, value) {
            if (value) {
                document.getElementById('status_keberangkatan_value_' + id).value = value;
                document.getElementById('status-keberangkatan-form-' + id).submit();
            }
        }
    </script>
@endpush
