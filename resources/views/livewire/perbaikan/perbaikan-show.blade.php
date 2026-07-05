<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('perbaikan.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Perbaikan</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $repair->item?->kode_aset ?? '-' }} - {{ $repair->item?->nama ?? 'Item Dihapus' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('perbaikan.edit')
                        <a wire:navigate href="{{ route('perbaikan.edit', $repair) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit Perbaikan
                        </a>
                    @endcan
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Perbaikan</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Laporan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->tanggal_laporan?->isoFormat('D MMMM Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dilaporkan Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->pelapor?->name ?? auth()->user()?->name ?? '-' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Barang</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @if ($repair->item)
                                    <a wire:navigate href="{{ route('barang.show', $repair->item) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $repair->item->kode_aset }} - {{ $repair->item->nama }}
                                    </a>
                                @else
                                    Item telah dihapus
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tingkat Kerusakan</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ match($repair->tingkat_kerusakan) {
                                        'Ringan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                                        'Sedang' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300',
                                        'Berat' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                        'Kritis' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    } }}">
                                    {{ $repair->tingkat_kerusakan }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Akhir</dt>
                            <dd class="mt-1">
                                @if ($repair->status_akhir)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ match($repair->status_akhir) {
                                            'Selesai' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                            'Dalam Proses' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        } }}">
                                        {{ $repair->status_akhir }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Belum selesai</span>
                                @endif
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi Kerusakan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->deskripsi_kerusakan }}</dd>
                        </div>
                        @if ($repair->tindakan)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tindakan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->tindakan }}</dd>
                            </div>
                        @endif
                        @if ($repair->tindakan_detail)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail Tindakan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->tindakan_detail }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ditangani Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->penangan?->name ?? $repair->vendor_eksternal ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biaya</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->biaya ? 'Rp ' . number_format($repair->biaya, 2, ',', '.') : '-' }}</dd>
                        </div>
                        @if ($repair->tanggal_selesai)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->tanggal_selesai->isoFormat('D MMMM Y') }}</dd>
                            </div>
                        @endif
                        @if ($repair->stock)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Terkait</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->stock->nama }}</dd>
                            </div>
                        @endif
                        @if ($repair->catatan)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->catatan }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->creator?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat Pada</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $repair->created_at->isoFormat('D MMMM Y HH:mm') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
