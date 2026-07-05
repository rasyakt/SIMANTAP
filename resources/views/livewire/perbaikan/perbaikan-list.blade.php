<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Perbaikan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data perbaikan barang/aset</p>
        </div>
        @can('perbaikan.create')
            <a wire:navigate href="{{ route('perbaikan.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Perbaikan
            </a>
        @endcan
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
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode aset, nama barang, deskripsi kerusakan..."
                       class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select wire:model.live="statusFilter"
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Proses Perbaikan</option>
                    <option value="done">Selesai</option>
                </select>
                <input type="date" wire:model.live="dateFrom"
                       class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="date" wire:model.live="dateTo"
                       class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 cursor-pointer select-none" wire:click="sortBy('item_id')">
                            <div class="flex items-center gap-1">
                                Barang
                                @if ($sortField === 'item_id')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 cursor-pointer select-none" wire:click="sortBy('tanggal_laporan')">
                            <div class="flex items-center gap-1">
                                Tgl. Laporan
                                @if ($sortField === 'tanggal_laporan')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Kerusakan</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600 dark:text-gray-300">Tingkat</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 cursor-pointer select-none" wire:click="sortBy('tanggal_selesai')">
                            <div class="flex items-center gap-1">
                                Selesai
                                @if ($sortField === 'tanggal_selesai')
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
                    @forelse ($repairs as $repair)
                        @php
                            $isPending = empty($repair->tanggal_selesai) && empty($repair->status_akhir);
                            $hariTertunda = $isPending ? now()->diffInDays($repair->tanggal_laporan) : 0;
                            $isOverdue = $isPending && $hariTertunda > $batasHari;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $repair->trashed() ? 'opacity-60' : '' }}">
                            <td class="px-4 py-3">
                                <div class="text-gray-900 dark:text-white font-medium text-sm">
                                    {{ $repair->item?->nama ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $repair->item?->kode_aset ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ $repair->tanggal_laporan?->isoFormat('DD MMM YYYY') }}
                                @if ($isOverdue)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 ml-1">
                                        {{ $hariTertunda }} hr
                                    </span>
                                @elseif ($isPending && $hariTertunda > 0)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 ml-1">
                                        {{ $hariTertunda }} hr
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <div class="text-gray-700 dark:text-gray-300 text-sm truncate" title="{{ $repair->deskripsi_kerusakan }}">
                                    {{ $repair->deskripsi_kerusakan }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Dilapor: {{ $repair->pelapor?->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $repair->tingkat_kerusakan === 'Ringan' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' : '' }}
                                    {{ $repair->tingkat_kerusakan === 'Sedang' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' : '' }}
                                    {{ $repair->tingkat_kerusakan === 'Berat' ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300' : '' }}
                                    {{ $repair->tingkat_kerusakan === 'Kritis' ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' : '' }}">
                                    {{ $repair->tingkat_kerusakan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($repair->trashed())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300">Dihapus</span>
                                @elseif ($isPending)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">Proses</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300">
                                        {{ $repair->status_akhir }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600 dark:text-gray-400 text-sm">
                                {{ $repair->tanggal_selesai?->isoFormat('DD MMM YYYY') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <x-action-dropdown>
                                    @if ($repair->trashed())
                                        @can('perbaikan.delete')
                                            <button wire:click="restore({{ $repair->id }})"
                                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                                                </svg>
                                                Pulihkan
                                            </button>
                                            <button wire:click="$dispatch('confirm-delete', { id: {{ $repair->id }})"
                                                    wire:confirm="Hapus permanen perbaikan ini?"
                                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus Permanen
                                            </button>
                                        @endcan
                                    @else
                                        @can('perbaikan.view')
                                            <a wire:navigate href="{{ route('perbaikan.show', $repair->id) }}"
                                               class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                        @endcan
                                        @can('perbaikan.edit')
                                            <a wire:navigate href="{{ route('perbaikan.edit', $repair->id) }}"
                                               class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        @endcan
                                        @can('perbaikan.delete')
                                            <button wire:click="confirmDelete({{ $repair->id }})"
                                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        @endcan
                                    @endif
                                </x-action-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                @if ($search || $statusFilter || $dateFrom || $dateTo)
                                    <p class="text-sm font-medium">Tidak ada data perbaikan yang cocok</p>
                                    <p class="text-xs mt-1">Coba ubah kata kunci atau filter pencarian</p>
                                @else
                                    <p class="text-sm font-medium">Belum ada riwayat perbaikan</p>
                                    <p class="text-xs mt-1">Tambahkan perbaikan baru untuk mulai mencatat.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($repairs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $repairs->links() }}
            </div>
        @endif
    </div>

    @if ($deleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             x-data
             x-init="$el.addEventListener('click', function(e) { if (e.target === $el) $wire.cancelDelete() })">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus riwayat perbaikan ini?</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button wire:click="cancelDelete">Batal</x-secondary-button>
                    <x-danger-button wire:click="delete">Hapus</x-danger-button>
                </div>
            </div>
        </div>
    @endif
</div>
