<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Daftar Stok Gudang</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola stok barang dan gudang</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('stok.masuk')
                        <a wire:navigate href="{{ route('stok.masuk') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Stok Masuk
                        </a>
                    @endcan
                    @can('stok.keluar')
                        <a wire:navigate href="{{ route('stok.keluar') }}"
                           class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"/>
                            </svg>
                            Stok Keluar
                        </a>
                    @endcan
                    @can('stok.create')
                        <a wire:navigate href="{{ route('stok.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Stok
                        </a>
                    @endcan
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 space-y-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama stok, vendor..."
                               class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select wire:model.live="filterKategori"
                                class="w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filterLokasi"
                                class="w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Lokasi</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                            @endforeach
                        </select>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                            <input type="checkbox" wire:model.live="filterLowStock"
                                   class="rounded border-gray-300 dark:border-gray-600 text-red-500 focus:ring-red-500 dark:bg-gray-700">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                Stok Menipis
                            </span>
                        </label>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 cursor-pointer select-none" wire:click="sortBy('nama')">
                                    <div class="flex items-center gap-1">
                                        Nama
                                        @if ($sortField === 'nama')
                                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="hidden md:table-cell px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Kategori</th>
                                <th class="hidden md:table-cell px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Lokasi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 cursor-pointer select-none" wire:click="sortBy('jumlah_stok')">
                                    <div class="flex items-center gap-1">
                                        Stok
                                        @if ($sortField === 'jumlah_stok')
                                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="hidden sm:table-cell px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 cursor-pointer select-none" wire:click="sortBy('harga_satuan')">
                                    <div class="flex items-center gap-1">
                                        Harga
                                        @if ($sortField === 'harga_satuan')
                                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($stoks as $stock)
                                @php $isLow = $stock->isLowStock(); @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $isLow ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $stock->nama }}</div>
                                            @if ($isLow)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300">
                                                    Menipis
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $stock->satuan }}
                                            @if ($stock->vendor)
                                                &middot; {{ $stock->vendor }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                            {{ $stock->kategori?->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="hidden md:table-cell px-4 py-3 text-gray-500 dark:text-gray-400">
                                        {{ $stock->lokasi?->nama ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold {{ $isLow ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                                {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                                            </span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                / {{ number_format($stock->ambang_batas_minimum, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        @if ($isLow)
                                            <div class="w-full max-w-[100px] h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mt-1 overflow-hidden">
                                                <div class="h-full bg-red-500 rounded-full" style="width: {{ min(($stock->jumlah_stok / max($stock->ambang_batas_minimum, 1)) * 100, 100) }}%"></div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="hidden sm:table-cell px-4 py-3 text-gray-500 dark:text-gray-400">
                                        {{ $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <x-action-dropdown>
                                            <button wire:click="viewMovements({{ $stock->id }})"
                                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                                Riwayat Mutasi
                                            </button>
                                            @can('stok.view')
                                                <a wire:navigate href="{{ route('stok.show', $stock->id) }}"
                                                   class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                            @endcan
                                            @can('stok.edit')
                                                <a wire:navigate href="{{ route('stok.edit', $stock->id) }}"
                                                   class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('stok.delete')
                                                <button wire:click="confirmDelete({{ $stock->id }})"
                                                        class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            @endcan
                                        </x-action-dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        @if ($search || $filterKategori || $filterLokasi || $filterLowStock)
                                            <p class="text-sm font-medium">Tidak ada stok yang cocok dengan filter</p>
                                            <p class="text-xs mt-1">Coba ubah kata kunci atau filter</p>
                                        @else
                                            <p class="text-sm font-medium">Belum ada stok</p>
                                            <p class="text-xs mt-1">Tambahkan stok baru untuk mulai mengelola.</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($stoks->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $stoks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($deleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus stok ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button wire:click="cancelDelete">Batal</x-secondary-button>
                    <x-danger-button wire:click="delete">Hapus</x-danger-button>
                </div>
            </div>
        </div>
    @endif

    @if ($showMovements && $movementStock)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" wire:click.self="closeMovements">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Mutasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $movementStock->nama }} ({{ $movementStock->satuan }})</p>
                    </div>
                    <button wire:click="closeMovements" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto p-4 flex-1">
                    @if (count($movements) > 0)
                        <div class="space-y-3">
                            @foreach ($movements as $mov)
                                <div class="flex items-start gap-3 p-3 rounded-lg {{ $mov->tipe === 'masuk' ? 'bg-green-50 dark:bg-green-900/10' : 'bg-red-50 dark:bg-red-900/10' }}">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $mov->tipe === 'masuk' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if ($mov->tipe === 'masuk')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold {{ $mov->tipe === 'masuk' ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                                {{ ucfirst($mov->tipe) }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $mov->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Jumlah: <strong>{{ number_format($mov->jumlah, 0, ',', '.') }}</strong>
                                            @if ($mov->harga_satuan)
                                                &middot; Rp {{ number_format($mov->harga_satuan, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        @if ($mov->referensi)
                                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">Ref: {{ $mov->referensi }}</div>
                                        @endif
                                        @if ($mov->keterangan)
                                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">{{ $mov->keterangan }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            oleh {{ $mov->creator?->name ?? 'Sistem' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto w-10 h-10 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm">Belum ada riwayat mutasi</p>
                        </div>
                    @endif
                </div>
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Stok Saat Ini:</span>
                        <span class="font-semibold text-gray-900 dark:text-white {{ $movementStock->isLowStock() ? 'text-red-600 dark:text-red-400' : '' }}">
                            {{ number_format($movementStock->jumlah_stok, 0, ',', '.') }} {{ $movementStock->satuan }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
