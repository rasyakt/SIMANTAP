<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Template Barang</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola template barang inventaris</p>
        </div>
        @can('template.create')
            <a href="{{ route('template.create') }}" wire:navigate
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Template
            </a>
        @endcan
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari template..."
                       class="w-full sm:w-96 pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer select-none" wire:click="sortBy('nama')">
                            <div class="flex items-center gap-1">
                                Nama
                                @if ($sortField === 'nama')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="hidden md:table-cell px-6 py-3">Kategori</th>
                        <th scope="col" class="hidden lg:table-cell px-6 py-3 cursor-pointer select-none" wire:click="sortBy('merk')">
                            <div class="flex items-center gap-1">
                                Merk / Model
                                @if ($sortField === 'merk')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="hidden md:table-cell px-6 py-3">Satuan</th>
                        <th scope="col" class="hidden lg:table-cell px-6 py-3 cursor-pointer select-none" wire:click="sortBy('estimasi_harga')">
                            <div class="flex items-center gap-1">
                                Estimasi Harga
                                @if ($sortField === 'estimasi_harga')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="hidden md:table-cell px-6 py-3">Status</th>
                        <th scope="col" class="hidden md:table-cell px-6 py-3 text-center">Serial</th>
                        @canany(['template.edit', 'template.delete'])
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($templates as $template)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($template->gambar)
                                        <img src="{{ Storage::url($template->gambar) }}"
                                             class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                                             alt="{{ $template->nama }}">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $template->nama }}</div>
                                        @if ($template->tipe_model)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $template->tipe_model }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                    {{ $template->kategori?->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $template->merk ?? '-' }}
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                <span class="uppercase text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $template->satuan }}</span>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 text-gray-700 dark:text-gray-300 font-medium">
                                {{ $template->estimasi_harga ? 'Rp ' . number_format($template->estimasi_harga, 0, ',', '.') : '-' }}
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                @if ($template->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 text-center">
                                @if ($template->has_serial_number)
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @endif
                            </td>
                            @canany(['template.edit', 'template.delete'])
                                <td class="px-6 py-4 text-right">
                                    <x-action-dropdown>
                                        <a href="{{ route('template.show', $template->id) }}" wire:navigate
                                           class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        @can('template.edit')
                                            <a href="{{ route('template.edit', $template->id) }}" wire:navigate
                                               class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        @endcan
                                        @can('template.delete')
                                            <button type="button" x-data
                                                    x-on:confirm="{
                                                        title: 'Hapus Template',
                                                        text: 'Apakah Anda yakin ingin menghapus template {{ $template->nama }}?',
                                                        icon: 'warning',
                                                        confirmButtonText: 'Ya, hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }"
                                                    wire:click="delete({{ $template->id }})"
                                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        @endcan
                                    </x-action-dropdown>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada template barang.</p>
                                @can('template.create')
                                    <a href="{{ route('template.create') }}" wire:navigate
                                       class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline mt-2">
                                        Tambah template baru
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($templates->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $templates->links() }}
            </div>
        @endif
    </div>

    @if (session()->has('alert'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 z-50">
            <div class="px-4 py-3 rounded-lg shadow-lg text-sm font-medium text-white
                        {{ session('alert')['type'] === 'success' ? 'bg-green-600' : 'bg-red-600' }}">
                {{ session('alert')['message'] }}
            </div>
        </div>
    @endif
</div>
