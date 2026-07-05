<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('barang.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $item->nama }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kode Aset: {{ $item->kode_aset }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('barang.edit')
                        <a wire:navigate href="{{ route('barang.edit', $item) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit Barang
                        </a>
                        <a wire:navigate href="{{ route('barang.tandai-rusak', $item) }}"
                           class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Tandai Rusak
                        </a>
                    @endcan
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Barang</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Aset</dt>
                                    <dd class="mt-1 text-sm font-mono font-medium text-gray-900 dark:text-gray-100">{{ $item->kode_aset }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Barang</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->nama }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->kategori?->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->lokasi?->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Template</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->itemTemplate?->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Barang Induk</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if ($item->parent)
                                            <a wire:navigate href="{{ route('barang.show', $item->parent) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $item->parent->kode_aset }} - {{ $item->parent->nama }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nomor Seri</dt>
                                    <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $item->nomor_seri ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Pengadaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->tanggal_pengadaan?->isoFormat('D MMMM Y') ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vendor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->vendor ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sumber</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->sumber ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->harga ? 'Rp ' . number_format($item->harga, 2, ',', '.') : '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->jumlah ? $item->jumlah . ' ' . $item->satuan : '-' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kondisi</dt>
                                    <dd class="mt-1">
                                        @php
                                            $kondisiClass = match($item->kondisi) {
                                                'Baik' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                'Rusak Ringan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                'Rusak Berat' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                                'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                'Sudah Diperbaiki' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/50 dark:text-teal-300',
                                                'Afkir-Dihapuskan' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $kondisiClass }}">
                                            {{ $item->kondisi }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Penggunaan</dt>
                                    <dd class="mt-1">
                                        @php
                                            $statusClass = match($item->status_penggunaan) {
                                                'Digunakan' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                                'Idle' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                'Dipinjam' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                                'Dalam Perbaikan' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                                'Menunggu Pembuangan' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                            {{ $item->status_penggunaan }}
                                        </span>
                                    </dd>
                                </div>
                                @if ($item->deskripsi)
                                    <div class="md:col-span-2">
                                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->deskripsi }}</dd>
                                    </div>
                                @endif
                                @if ($item->catatan)
                                    <div class="md:col-span-2">
                                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->catatan }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->creator?->name ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diperbarui Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->updater?->name ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if ($item->children->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Komponen Barang</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->children_count }} komponen</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kode Aset</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kondisi</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($item->children as $child)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-4 py-3 font-mono text-gray-900 dark:text-gray-100">{{ $child->kode_aset }}</td>
                                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $child->nama }}</td>
                                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $child->kategori?->nama ?? '-' }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                        {{ $child->kondisi === 'Baik' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                                        {{ $child->kondisi }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <a wire:navigate href="{{ route('barang.show', $child) }}"
                                                       class="text-blue-600 dark:text-blue-400 hover:underline text-xs font-medium">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($item->components->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Komponen (Item Components)</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->components->count() }} komponen terdaftar</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kode Aset</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nama Komponen</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kuantitas</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($item->components as $component)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-4 py-3 font-mono text-gray-900 dark:text-gray-100">{{ $component->componentItem?->kode_aset ?? '-' }}</td>
                                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $component->componentItem?->nama ?? '-' }}</td>
                                                <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-100">{{ $component->kuantitas }}</td>
                                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $component->catatan ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($item->repairHistories->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Perbaikan</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->repairHistories->count() }} riwayat perbaikan</p>
                            </div>
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($item->repairHistories as $repair)
                                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                        {{ $repair->tingkat_kerusakan === 'Rusak Ringan' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' : ($repair->tingkat_kerusakan === 'Rusak Berat' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300') }}">
                                                        {{ $repair->tingkat_kerusakan }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $repair->tanggal_laporan?->isoFormat('D MMMM Y') }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $repair->deskripsi_kerusakan }}</p>
                                                @if ($repair->tindakan)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <span class="font-medium">Tindakan:</span> {{ $repair->tindakan }}
                                                    </p>
                                                @endif
                                                @if ($repair->catatan)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <span class="font-medium">Catatan:</span> {{ $repair->catatan }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">QR Code</h2>
                        </div>
                        <div class="p-6 flex flex-col items-center">
                            @if ($item->qr_code)
                                <img src="{{ Storage::url($item->qr_code) }}"
                                     alt="QR Code {{ $item->kode_aset }}"
                                     class="w-40 h-40">
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">{{ $item->kode_aset }}</p>
                            @else
                                <div class="w-40 h-40 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <span class="text-sm text-gray-400">Tidak tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($item->foto)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Foto</h2>
                            </div>
                            <div class="p-6 flex flex-col items-center">
                                <img src="{{ Storage::url($item->foto) }}"
                                     alt="Foto {{ $item->nama }}"
                                     class="w-full rounded-lg object-cover max-h-64">
                            </div>
                        </div>
                    @endif

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Status</h2>
                        </div>
                        <div class="p-4">
                            @forelse ($item->statusHistories->sortByDesc('created_at') as $history)
                                <div class="relative pl-6 pb-4 last:pb-0">
                                    <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full
                                        {{ $history->kondisi_baru === 'Baik' ? 'bg-green-500' : ($history->kondisi_baru === 'Rusak Ringan' ? 'bg-yellow-500' : ($history->kondisi_baru === 'Rusak Berat' ? 'bg-orange-500' : 'bg-red-500')) }}">
                                    </div>
                                    @if (!$loop->last)
                                        <div class="absolute left-1 top-4 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                                    @endif
                                    <div class="text-sm">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $history->kondisi_sebelumnya }} &rarr; {{ $history->kondisi_baru }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $history->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if ($history->keterangan)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $history->keterangan }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            oleh {{ $history->creator?->name ?? 'Sistem' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada riwayat perubahan status</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
