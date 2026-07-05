<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('stok.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stock->nama }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Detail stok gudang</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('stok.edit')
                        <a wire:navigate href="/stok/{{ $this->stock->id }}/edit"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit Stok
                        </a>
                    @endcan
                    @can('stok.masuk')
                        <a wire:navigate href="{{ route('stok.mutasi', ['stock_id' => $this->stock->id, 'tipe' => 'masuk']) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Stok Masuk
                        </a>
                    @endcan
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Stok</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Stok</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $stock->nama }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->kategori?->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->lokasi?->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Template</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->itemTemplate?->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Satuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->satuan ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vendor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->vendor ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Satuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan, 2, ',', '.') : '-' }}</dd>
                                </div>
                                @if ($stock->catatan)
                                    <div class="md:col-span-2">
                                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stock->catatan }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if ($stock->movements->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Mutasi</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stock->movements->count() }} mutasi</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tipe</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Referensi</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($stock->movements as $movement)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                    {{ $movement->created_at->isoFormat('D MMM Y HH:mm') }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if ($movement->tipe === 'masuk')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300">Masuk</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300">Keluar</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $movement->tipe === 'masuk' ? '+' : '-' }}{{ number_format($movement->jumlah, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $movement->referensi ?: '-' }}</td>
                                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $movement->keterangan ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ringkasan Stok</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Stok</p>
                                <p class="text-3xl font-bold {{ $stock->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $stock->satuan }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ambang Batas Minimum</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stock->ambang_batas_minimum, 0, ',', '.') }} {{ $stock->satuan }}</p>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</p>
                                @if ($stock->isLowStock())
                                    <div class="mt-1 flex items-center gap-2 text-sm font-medium text-red-600 dark:text-red-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        Stok Menipis
                                    </div>
                                @else
                                    <div class="mt-1 flex items-center gap-2 text-sm font-medium text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Stok Aman
                                    </div>
                                @endif
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Mutasi</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $stock->movements->count() }}x mutasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
