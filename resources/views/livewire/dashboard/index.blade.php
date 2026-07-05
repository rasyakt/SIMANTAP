<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan data aset dan inventaris</p>
                </div>
                @if ($isSuperAdmin)
                    <select wire:model.live="filterLokasi"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Lokasi</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ number_format($totalItems, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2.5 bg-blue-50/50 dark:bg-blue-900/20 rounded-full ring-1 ring-blue-100 dark:ring-blue-800/30">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stok</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ number_format($totalStocks, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2.5 bg-green-50/50 dark:bg-green-900/20 rounded-full ring-1 ring-green-100 dark:ring-green-800/30">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu Perhatian</p>
                            <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ number_format($itemsPerluPerhatian->count(), 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2.5 bg-amber-50/50 dark:bg-amber-900/20 rounded-full ring-1 ring-amber-100 dark:ring-amber-800/30">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Menipis</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($lowStockItems->count(), 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2.5 bg-red-50/50 dark:bg-red-900/20 rounded-full ring-1 ring-red-100 dark:ring-red-800/30">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:key="charts-{{ $filterLokasi }}"
                 x-data="{
                    kondisiLabels: {{ Js::from(array_keys($itemsByKondisi->toArray())) }},
                    kondisiValues: {{ Js::from(array_values($itemsByKondisi->toArray())) }},
                    kondisiColors: {
                        'Baik': '#22c55e',
                        'Rusak Ringan': '#eab308',
                        'Rusak Berat': '#f97316',
                        'Dalam Perbaikan': '#3b82f6',
                        'Sudah Diperbaiki': '#14b8a6',
                        'Afkir-Dihapuskan': '#ef4444'
                    },
                    lokasiLabels: {{ Js::from($itemsByLokasiCollection->pluck('nama')) }},
                    lokasiValues: {{ Js::from($itemsByLokasiCollection->pluck('total')) }},
                    chartKondisi: null,
                    chartLokasi: null,
                    init() {
                        this.$nextTick(() => {
                            this.initCharts();
                        });
                    },
                    initCharts() {
                        this.destroyCharts();

                        const kondisiColorsArr = this.kondisiLabels.map(l => this.kondisiColors[l] || '#6b7280');

                        if (this.$refs.kondisiCanvas) {
                            this.chartKondisi = new Chart(this.$refs.kondisiCanvas, {
                                type: 'doughnut',
                                data: {
                                    labels: this.kondisiLabels,
                                    datasets: [{
                                        data: this.kondisiValues,
                                        backgroundColor: kondisiColorsArr,
                                        borderColor: kondisiColorsArr,
                                        borderWidth: 1,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                padding: 16,
                                                usePointStyle: true,
                                                color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                                                font: { size: 11 },
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        const barColors = this.lokasiLabels.map(() => '#3b82f6');

                        if (this.$refs.lokasiCanvas) {
                            this.chartLokasi = new Chart(this.$refs.lokasiCanvas, {
                                type: 'bar',
                                data: {
                                    labels: this.lokasiLabels,
                                    datasets: [{
                                        label: 'Jumlah Barang',
                                        data: this.lokasiValues,
                                        backgroundColor: barColors,
                                        borderRadius: 4,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                precision: 0,
                                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                                            },
                                            grid: {
                                                color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb',
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                maxRotation: 45,
                                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                                            },
                                            grid: { display: false }
                                        }
                                    }
                                }
                            });
                        }
                    },
                    destroyCharts() {
                        if (this.chartKondisi) { this.chartKondisi.destroy(); this.chartKondisi = null; }
                        if (this.chartLokasi) { this.chartLokasi.destroy(); this.chartLokasi = null; }
                    }
                 }">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Kondisi Barang</h3>
                        <div class="relative h-48 sm:h-64">
                            <canvas x-ref="kondisiCanvas"></canvas>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Barang per Lokasi</h3>
                        <div class="relative h-48 sm:h-64">
                            <canvas x-ref="lokasiCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Barang Rusak Belum Diperbaiki</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($itemsPerluPerhatian as $item)
                            <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $item->nama }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->kode_aset }}
                                        @if ($item->lokasi)
                                            &middot; {{ $item->lokasi->nama }}
                                        @endif
                                    </p>
                                </div>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $item->kondisi === 'Rusak Berat' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                    {{ $item->kondisi }}
                                </span>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tidak ada barang rusak yang perlu perhatian
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Stok Menipis</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($lowStockItems as $stock)
                            <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $stock->nama }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Stok: {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                                        @if ($stock->lokasi)
                                            &middot; {{ $stock->lokasi->nama }}
                                        @endif
                                    </p>
                                </div>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">
                                    Min. {{ number_format($stock->ambang_batas_minimum, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Semua stok dalam batas aman
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Barang Terbaru</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($recentItems as $item)
                            <div class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $item->nama }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->kode_aset }}
                                            @if ($item->lokasi)
                                                &middot; {{ $item->lokasi->nama }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="ml-3 text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada barang
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Riwayat Perbaikan Terbaru</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($recentRepairs as $repair)
                            <div class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $repair->item?->nama ?? 'Item Dihapus' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $repair->tanggal_laporan?->isoFormat('D MMM Y') ?? '-' }}
                                            @if ($repair->item?->lokasi)
                                                &middot; {{ $repair->item->lokasi->nama }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="ml-3 text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                        {{ $repair->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada riwayat perbaikan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
