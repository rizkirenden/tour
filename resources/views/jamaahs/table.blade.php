<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID
                    Keberangkatan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk Paket
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Keberangkatan
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">JK</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Passport
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tagihan
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                @php
                    $sumber = $item->sumber_data;
                    $passport = $item->status_passport;
                    $dokumen = $item->status_dokumen;
                    $bulanNama = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];

                    $detailDokumen = [
                        ['field' => 'file_ktp_kk', 'label' => 'KTP / KK', 'path' => $item->file_ktp_kk],
                        ['field' => 'file_vaksin', 'label' => 'Vaksin', 'path' => $item->file_vaksin],
                        ['field' => 'file_visa', 'label' => 'Visa', 'path' => $item->file_visa],
                        ['field' => 'file_paspor', 'label' => 'Passport', 'path' => $item->file_paspor],
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 text-gray-500">{{ $data->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-mono">
                            {{ $item->id_keberangkatan }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-800">{{ $item->nama_lengkap }}</p>
                            @if ($sumber['source'] == 'keluarga')
                                <a href="{{ $sumber['link'] }}" class="text-purple-500 hover:text-purple-700"
                                    title="Lihat Keluarga">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">{{ $item->nomor_paspor ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <div class="relative inline-block group">
                            {!! $sumber['badge'] !!}
                            @if ($sumber['source'] == 'keluarga')
                                <div
                                    class="absolute z-20 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg p-2 w-48 bottom-full left-1/2 -translate-x-1/2 mb-1 shadow-lg">
                                    <p class="font-medium mb-1">Dari Keluarga:</p>
                                    <p class="font-semibold">{{ $sumber['nama_keluarga'] }}</p>
                                    <p class="text-gray-300">{{ $sumber['kode_keluarga'] }}</p>
                                    <div
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->produk_paket }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($item->keberangkatan as $keb)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    <i class="fas fa-plane text-[10px]"></i>
                                    {{ $bulanNama[$keb->pivot->bulan_keberangkatan] ?? '' }}
                                    {{ $keb->pivot->tahun_keberangkatan }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400">-</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm text-gray-600">{{ $item->jenis_kelamin_label }}</span>
                    </td>

                    {{-- KOLOM PASSPORT --}}
                    <td class="px-4 py-3 text-center">
                        <div class="relative inline-block group">
                            {!! $passport['badge'] !!}
                            @if ($passport['status'] == 'incomplete')
                                <div
                                    class="absolute z-20 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg p-2 w-48 bottom-full left-1/2 -translate-x-1/2 mb-1 shadow-lg">
                                    <p class="font-medium mb-1">Data Passport belum lengkap:</p>
                                    <ul class="list-disc list-inside">
                                        @foreach ($passport['missing'] as $field)
                                            <li>{{ $field }}</li>
                                        @endforeach
                                    </ul>
                                    <div
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>

                    {{-- ============================================================ --}}
                    {{-- KOLOM DOKUMEN — MENGGUNAKAN JAVASCRIPT TOOLTIP (FIXED POSITION) --}}
                    {{-- ============================================================ --}}
                    <td class="px-4 py-3 text-center">
                        <div class="inline-block cursor-pointer" data-dokumen-trigger
                            data-dokumen-status="{{ $dokumen['status'] }}"
                            data-dokumen-list='@json($detailDokumen)'>
                            {!! $dokumen['badge'] !!}
                        </div>
                    </td>

                    <td class="px-4 py-3 text-center">
                        {!! $item->status_pembayaran_badge !!}
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        {{ $item->total_tagihan_setelah_diskon_formatted }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('transaksional.jamaah.show', $item->id_jamaah) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg" title="Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.jamaah.edit', $item->id_jamaah) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.jamaah.pembayaran', $item->id_jamaah) }}"
                                class="p-2 text-green-500 hover:bg-green-50 rounded-lg" title="Bayar">
                                <i class="fas fa-money-bill-wave text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_jamaah }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_jamaah }}"
                                action="{{ route('transaksional.jamaah.destroy', $item->id_jamaah) }}" method="POST"
                                class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-users text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data jamaah</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Jamaah" untuk menambahkan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ============================================================ --}}
{{-- TOOLTIP CONTAINER (FIXED POSITION - DI LUAR TABEL)            --}}
{{-- ============================================================ --}}
<div id="dokumen-tooltip"
    class="hidden fixed z-[9999] bg-gray-800 text-white text-xs rounded-lg p-3 w-64 shadow-2xl pointer-events-none">
    <p class="font-semibold mb-2 flex items-center gap-1">
        <i class="fas fa-folder-open"></i>
        Status Dokumen
    </p>
    <div id="dokumen-tooltip-content"></div>
</div>

<div class="mt-4 px-2">
    {{ $data->withQueryString()->links() }}
</div>

@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm('Yakin ingin menghapus jamaah ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        // ============================================================
        // TOOLTIP DOKUMEN — FIXED POSITION (tidak terpotong overflow)
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const tooltip = document.getElementById('dokumen-tooltip');
            const tooltipContent = document.getElementById('dokumen-tooltip-content');
            const triggers = document.querySelectorAll('[data-dokumen-trigger]');

            function buildTooltipHtml(status, list) {
                let html = '';

                if (status === 'complete') {
                    html += '<p class="text-green-300 flex items-center gap-1 mb-2">' +
                        '<i class="fas fa-check-circle"></i> Semua dokumen sudah lengkap</p>';
                } else {
                    html += '<p class="text-red-300 font-medium mb-1.5 flex items-center gap-1">' +
                        '<i class="fas fa-exclamation-circle"></i> Belum lengkap:</p>';
                }

                html += '<ul class="space-y-1.5">';
                list.forEach(function(doc) {
                    const hasFile = doc.path && doc.path !== '';
                    const icon = hasFile ?
                        '<i class="fas fa-check-circle text-green-400"></i>' :
                        '<i class="fas fa-times-circle text-red-400"></i>';
                    const labelClass = hasFile ? 'text-gray-200' : 'text-red-300 font-medium';
                    const right = hasFile ?
                        '<span class="text-blue-300 text-[10px]">✓ Ada</span>' :
                        '<span class="text-red-400 text-[10px] italic">Kosong</span>';

                    html += '<li class="flex items-center justify-between gap-2">' +
                        '<span class="flex items-center gap-1.5">' + icon +
                        '<span class="' + labelClass + '">' + doc.label + '</span></span>' +
                        right +
                        '</li>';
                });
                html += '</ul>';

                html += '<div class="mt-2 pt-2 border-t border-gray-600 text-[10px] text-gray-300">' +
                    '<i class="fas fa-info-circle mr-1"></i> Arahkan cursor untuk lihat detail</div>';

                return html;
            }

            triggers.forEach(function(trigger) {
                trigger.addEventListener('mouseenter', function(e) {
                    const status = this.dataset.dokumenStatus;
                    const list = JSON.parse(this.dataset.dokumenList || '[]');

                    tooltipContent.innerHTML = buildTooltipHtml(status, list);
                    tooltip.classList.remove('hidden');
                    tooltip.classList.add('block');

                    // Posisi tooltip di atas elemen
                    const rect = this.getBoundingClientRect();
                    const tooltipRect = tooltip.getBoundingClientRect();

                    let top = rect.top - tooltipRect.height - 10;
                    let left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);

                    // Cegah keluar layar
                    if (top < 10) top = rect.bottom + 10;
                    if (left < 10) left = 10;
                    if (left + tooltipRect.width > window.innerWidth - 10) {
                        left = window.innerWidth - tooltipRect.width - 10;
                    }

                    tooltip.style.top = top + 'px';
                    tooltip.style.left = left + 'px';
                });

                trigger.addEventListener('mouseleave', function() {
                    tooltip.classList.add('hidden');
                    tooltip.classList.remove('block');
                });
            });

            // Sembunyikan tooltip saat scroll
            window.addEventListener('scroll', function() {
                tooltip.classList.add('hidden');
                tooltip.classList.remove('block');
            }, true);
        });
    </script>
@endpush
