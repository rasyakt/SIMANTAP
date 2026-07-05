<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Laporan</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cetak laporan barang, stok, dan riwayat perbaikan</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('laporan.export')
                        <button wire:click="exportExcel"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Excel
                        </button>
                        <button wire:click="exportPdf"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Export PDF
                        </button>
                    @endcan
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <select wire:model.live="jenisLaporan"
                                class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($jenisLaporanOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filterLokasi"
                                class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Lokasi</option>
                            @foreach ($lokasiOptions as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filterKategori"
                                class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriOptions as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="date" wire:model.live="filterTanggalDari"
                                   class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Dari Tanggal">
                            <input type="date" wire:model.live="filterTanggalSampai"
                                   class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Sampai Tanggal">
                        </div>
                    </div>

                    @if (in_array($jenisLaporan, ['barang-lokasi', 'barang-rusak']))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <select wire:model.live="filterKondisi"
                                    class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Kondisi</option>
                                @foreach ($kondisiOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live="filterStatus"
                                    class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                @foreach ($statusOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>
                            Jenis Laporan: <strong class="text-gray-900 dark:text-gray-100">{{ $jenisLaporanOptions[$jenisLaporan] ?? $jenisLaporan }}</strong>
                        </span>
                        @if ($filterLokasi || $filterKategori || $filterKondisi || $filterStatus || $filterTanggalDari || $filterTanggalSampai)
                            <button wire:click="resetFilters"
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                Reset Filter
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if ($jenisLaporan === 'barang-lokasi')
                        <table class="w-full text-sm" wire:key="table-barang-lokasi">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Aset</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Barang</th>
                                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kondisi</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($reportRows as $row)
                                    @if ($row['type'] === 'header')
                                        <tr class="bg-gray-100 dark:bg-gray-700/30" wire:key="{{ $row['key'] }}">
                                            <td colspan="6" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $row['lokasi']->nama }}
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $row['count'] }} barang)</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @php $barang = $row['barang']; @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:key="{{ $row['key'] }}">
                                            <td class="px-4 py-2.5 text-xs text-gray-500 dark:text-gray-400">{{ $barang->lokasi?->nama ?? '-' }}</td>
                                            <td class="px-4 py-2.5 font-mono font-medium text-gray-900 dark:text-gray-100">{{ $barang->kode_aset }}</td>
                                            <td class="px-4 py-2.5 text-gray-900 dark:text-gray-100">{{ $barang->nama }}</td>
                                            <td class="hidden md:table-cell px-4 py-2.5 text-gray-600 dark:text-gray-400">{{ $barang->kategori?->nama ?? '-' }}</td>
                                            <td class="hidden lg:table-cell px-4 py-2.5 text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    {{ match($barang->kondisi) {
                                                        'Baik' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                        'Rusak Ringan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                        'Rusak Berat' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                                        'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                        'Sudah Diperbaiki' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/50 dark:text-teal-300',
                                                        'Afkir-Dihapuskan' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                    } }}">
                                                    {{ $barang->kondisi }}
                                                </span>
                                            </td>
                                            <td class="hidden lg:table-cell px-4 py-2.5 text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    {{ match($barang->status_penggunaan) {
                                                        'Digunakan' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                        'Idle' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                        'Dipinjam' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                                        'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                        'Menunggu Pembuangan' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                    } }}">
                                                    {{ $barang->status_penggunaan }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr wire:key="empty-barang-lokasi">
                                        <td colspan="6" class="px-4 py-12">
                                            <div class="flex flex-col items-center justify-center text-center">
                                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada data barang</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Sesuaikan filter untuk menampilkan data.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif ($jenisLaporan === 'barang-rusak')
                        <table class="w-full text-sm" wire:key="table-barang-rusak">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Aset</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Barang</th>
                                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kondisi</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"> Riwayat Perbaikan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($items as $barang)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:key="barang-rusak-row-{{ $barang->id }}">
                                        <td class="px-4 py-3 font-mono font-medium text-gray-900 dark:text-gray-100">{{ $barang->kode_aset }}</td>
                                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $barang->nama }}</td>
                                        <td class="hidden md:table-cell px-4 py-3 text-gray-600 dark:text-gray-400">{{ $barang->lokasi?->nama ?? '-' }}</td>
                                        <td class="hidden md:table-cell px-4 py-3 text-gray-600 dark:text-gray-400">{{ $barang->kategori?->nama ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ match($barang->kondisi) {
                                                    'Rusak Ringan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                    'Rusak Berat' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                                    'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                } }}">
                                                {{ $barang->kondisi }}
                                            </span>
                                        </td>
                                        <td class="hidden lg:table-cell px-4 py-3 text-center text-gray-600 dark:text-gray-400">
                                            {{ $barang->repairHistories->count() }}x perbaikan
                                        </td>
                                    </tr>
                                @empty
                                    <tr wire:key="empty-barang-rusak">
                                        <td colspan="6" class="px-4 py-12">
                                            <div class="flex flex-col items-center justify-center text-center">
                                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01"/>
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada barang rusak</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Semua barang dalam kondisi baik.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif ($jenisLaporan === 'stok-gudang')
                        <table class="w-full text-sm" wire:key="table-stok-gudang">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Stok</th>
                                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Stok</th>
                                    <th class="hidden sm:table-cell px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ambang Batas</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($stoks as $stock)
                                    @php $isLow = $stock->isLowStock(); @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $isLow ? 'bg-red-50 dark:bg-red-900/10' : '' }}" wire:key="stok-row-{{ $stock->id }}">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $stock->nama }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $stock->satuan }}</div>
                                        </td>
                                        <td class="hidden md:table-cell px-4 py-3 text-gray-600 dark:text-gray-400">{{ $stock->kategori?->nama ?? '-' }}</td>
                                        <td class="hidden md:table-cell px-4 py-3 text-gray-600 dark:text-gray-400">{{ $stock->lokasi?->nama ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-semibold {{ $isLow ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                                            {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 text-right text-gray-500 dark:text-gray-400">
                                            {{ number_format($stock->ambang_batas_minimum, 0, ',', '.') }}
                                        </td>
                                        <td class="hidden lg:table-cell px-4 py-3 text-right text-gray-600 dark:text-gray-400">
                                            {{ $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="hidden lg:table-cell px-4 py-3 text-center">
                                            @if ($isLow)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">
                                                    Menipis
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300">
                                                    Aman
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr wire:key="empty-stok-gudang">
                                        <td colspan="7" class="px-4 py-12">
                                            <div class="flex flex-col items-center justify-center text-center">
                                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada data stok</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Sesuaikan filter untuk menampilkan data.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif ($jenisLaporan === 'riwayat-perbaikan')
                        <table class="w-full text-sm" wire:key="table-riwayat-perbaikan">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Barang</th>
                                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kerusakan</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tingkat</th>
                                    <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($riwayats as $rh)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:key="riwayat-row-{{ $rh->id }}">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $rh->tanggal_laporan?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $rh->item?->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $rh->item?->kode_aset ?? '' }}</div>
                                        </td>
                                        <td class="hidden md:table-cell px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $rh->item?->lokasi?->nama ?? '-' }}
                                        </td>
                                        <td class="hidden lg:table-cell px-4 py-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                            {{ $rh->deskripsi_kerusakan }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ match($rh->tingkat_kerusakan) {
                                                    'Ringan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                    'Sedang' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                                    'Berat' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                } }}">
                                                {{ $rh->tingkat_kerusakan }}
                                            </span>
                                        </td>
                                        <td class="hidden lg:table-cell px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ match($rh->status_akhir) {
                                                    'Selesai' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                    'Dalam Proses' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                    'Menunggu Part' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                                    'Tidak Dapat Diperbaiki' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                } }}">
                                                {{ $rh->status_akhir ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr wire:key="empty-riwayat-perbaikan">
                                        <td colspan="6" class="px-4 py-12">
                                            <div class="flex flex-col items-center justify-center text-center">
                                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada riwayat perbaikan</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Sesuaikan filter untuk menampilkan data.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>

                @if (isset($stoks) && method_exists($stoks, 'hasPages') && $stoks->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $stoks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
