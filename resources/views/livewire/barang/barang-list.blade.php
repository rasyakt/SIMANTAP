<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Daftar Barang</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data barang dan aset</p>
                </div>
                @can('barang.create')
                    <a wire:navigate href="{{ route('barang.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Barang
                    </a>
                @endcan
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/50 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 space-y-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode aset, nama, nomor seri, atau vendor..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400">
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
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

                        <select wire:model.live="filterKategori"
                                class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filterLokasi"
                                class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Lokasi</option>
                            @foreach ($lokasis as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                            @endforeach
                        </select>

                        <input type="date" wire:model.live="filterTanggalDari"
                               class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Tgl. Dari">

                        <input type="date" wire:model.live="filterTanggalSampai"
                               class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Tgl. Sampai">
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>Total: <strong class="text-gray-900 dark:text-gray-100">{{ $items->total() }}</strong> barang</span>
                        @if ($search || $filterKondisi || $filterStatus || $filterKategori || $filterLokasi || $filterTanggalDari || $filterTanggalSampai)
                            <button wire:click="resetFilters"
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                Reset Filter
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button wire:click="sortBy('kode_aset')" class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                        Kode Aset
                                        @if ($sortField === 'kode_aset')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button wire:click="sortBy('nama')" class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                        Nama Barang
                                        @if ($sortField === 'nama')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</th>
                                <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kondisi</th>
                                <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($items as $barang)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-mono font-medium text-gray-900 dark:text-gray-100">{{ $barang->kode_aset }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $barang->nama }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $barang->children_count > 0 ? $barang->children_count . ' komponen' : '' }}
                                            {{ $barang->repair_histories_count > 0 ? '| ' . $barang->repair_histories_count . ' perbaikan' : '' }}
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $barang->kategori?->nama ?? '-' }}
                                    </td>
                                    <td class="hidden md:table-cell px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $barang->lokasi?->nama ?? '-' }}
                                    </td>
                                    <td class="hidden lg:table-cell px-4 py-3 text-center">
                                        @php
                                            $kondisiClass = match($barang->kondisi) {
                                                'Baik' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                'Rusak Ringan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                'Rusak Berat' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                                'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                'Sudah Diperbaiki' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/50 dark:text-teal-300',
                                                'Afkir-Dihapuskan' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kondisiClass }}">
                                            {{ $barang->kondisi }}
                                        </span>
                                    </td>
                                    <td class="hidden lg:table-cell px-4 py-3 text-center">
                                        @php
                                            $statusClass = match($barang->status_penggunaan) {
                                                'Digunakan' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                'Idle' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                'Dipinjam' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                                'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                'Menunggu Pembuangan' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $barang->status_penggunaan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <x-action-dropdown>
                                            <a wire:navigate href="{{ route('barang.show', $barang) }}"
                                               class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                            @can('barang.edit')
                                                <a wire:navigate href="{{ route('barang.edit', $barang) }}"
                                                   class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('barang.delete')
                                                <button wire:click="confirmDelete({{ $barang->id }})"
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
                                    <td colspan="7" class="px-4 py-12">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            @if ($search)
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada barang yang cocok dengan pencarian "{{ $search }}"</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba gunakan kata kunci lain</p>
                                            @else
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada barang</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan barang baru untuk mulai mengelola aset.</p>
                                                @can('barang.create')
                                                    <a wire:navigate href="{{ route('barang.create') }}"
                                                       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                        Tambah Barang
                                                    </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($items->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($deleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data x-init="$el.addEventListener('click', function(e) { if (e.target === $el) $wire.cancelDelete() })">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus barang ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button wire:click="delete"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
