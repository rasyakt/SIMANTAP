<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('kategori.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $kategori->nama }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kategori->slug }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('kategori.edit')
                        <a wire:navigate href="{{ route('kategori.edit', $kategori->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit Kategori
                        </a>
                    @endcan
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Kategori</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $kategori->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $kategori->slug }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori Induk</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kategori->parent?->nama ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</dt>
                            <dd class="mt-1">
                                @if ($kategori->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Nonaktif</span>
                                @endif
                            </dd>
                        </div>
                        @if ($kategori->deskripsi)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kategori->deskripsi }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if ($kategori->children->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Sub Kategori</h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($kategori->children as $child)
                            <div class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $child->nama }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($kategori->items->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Barang dalam Kategori Ini</h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($kategori->items as $item)
                            <div class="px-6 py-3 flex items-center justify-between text-sm">
                                <span class="text-gray-900 dark:text-gray-100">{{ $item->kode_aset }} - {{ $item->nama }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $item->lokasi?->nama ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
