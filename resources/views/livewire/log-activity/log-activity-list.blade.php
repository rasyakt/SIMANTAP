<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Log Aktivitas</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Riwayat aktivitas seluruh pengguna sistem</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="resetFilters" class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Reset Filter
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari aktivitas atau pengguna..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm">
                        </div>
                        <select wire:model.live="filterLogName" class="border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua Modul</option>
                            @foreach ($logNameOptions as $opt)
                                <option value="{{ $opt }}">{{ $this->getLogNameLabel($opt) }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filterEvent" class="border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua Aksi</option>
                            @foreach ($eventOptions as $opt)
                                <option value="{{ $opt }}">{{ $this->getEventLabel($opt) }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model.live.debounce.300ms="filterCauser" placeholder="Filter nama pelaku..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Dari Tanggal</label>
                            <input type="date" wire:model.live="filterTanggalDari" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                            <input type="date" wire:model.live="filterTanggalSampai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button wire:click="sortBy('created_at')" class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                        Waktu
                                        @if ($sortField === 'created_at')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelaku</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Modul</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($activities as $activity)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 dark:text-gray-400" title="{{ $activity->created_at->translatedFormat('l, d F Y H:i:s') }}">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </span>
                                        <br>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $activity->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($activity->causer)
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-medium text-blue-700 dark:text-blue-300">{{ strtoupper(substr($activity->causer->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->causer->name }}</span>
                                                    <br>
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $activity->causer->email }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500 italic">Sistem</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $this->getLogNameLabel($activity->log_name) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getEventBadgeClass($activity->event) }}">
                                            {{ $this->getEventLabel($activity->event) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $activity->description }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button wire:click="viewDetail({{ $activity->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            @if ($search || $filterLogName || $filterEvent || $filterTanggalDari || $filterTanggalSampai || $filterCauser)
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada log yang cocok dengan filter</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba gunakan kata kunci atau filter lain</p>
                                            @else
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada aktivitas</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Log aktivitas akan muncul saat pengguna melakukan tindakan di sistem.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($activities->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>

            <div class="mt-4 text-xs text-gray-400 dark:text-gray-500 text-right">
                Menampilkan {{ $activities->firstItem() ?? 0 }} - {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} log
            </div>
        </div>
    </div>

    @if ($showDetail && $detailActivity)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data x-init="$el.addEventListener('click', function(e) { if (e.target === $el) $wire.closeDetail() })">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detail Log Aktivitas</h3>
                    <button wire:click="closeDetail" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $detailActivity->created_at->translatedFormat('l, d F Y H:i:s') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Modul</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $this->getLogNameLabel($detailActivity->log_name) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</span>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getEventBadgeClass($detailActivity->event) }}">
                                    {{ $this->getEventLabel($detailActivity->event) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelaku</span>
                            @if ($detailActivity->causer)
                                <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $detailActivity->causer->name }} ({{ $detailActivity->causer->email }})</p>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic mt-1">Sistem</p>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</span>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $detailActivity->description }}</p>
                    </div>

                    @if ($detailActivity->subject)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subjek</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                {{ class_basename($detailActivity->subject_type) }} #{{ $detailActivity->subject_id }}
                                @if(method_exists($detailActivity->subject, 'getAttribute'))
                                    @if($detailActivity->subject->getAttribute('nama'))
                                        - {{ $detailActivity->subject->nama }}
                                    @elseif($detailActivity->subject->getAttribute('name'))
                                        - {{ $detailActivity->subject->name }}
                                    @endif
                                @endif
                            </p>
                        </div>
                    @endif

                    @if ($detailActivity->properties && $detailActivity->properties->count() > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data Perubahan</span>
                            <div class="mt-2 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 overflow-x-auto">
                                <pre class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ json_encode($detailActivity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif

                    @if ($detailActivity->attribute_changes)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attribute Changes</span>
                            <div class="mt-2 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 overflow-x-auto">
                                <pre class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ json_encode($detailActivity->attribute_changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Log Name</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $detailActivity->log_name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject Type</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ class_basename($detailActivity->subject_type) ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject ID</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $detailActivity->subject_id ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Event</span>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $detailActivity->event ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end p-6 border-t border-gray-200 dark:border-gray-700">
                    <x-secondary-button wire:click="closeDetail">Tutup</x-secondary-button>
                </div>
            </div>
        </div>
    @endif
</div>
