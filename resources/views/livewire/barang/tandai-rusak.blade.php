<div>
    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('barang.show', $item) }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Tandai Rusak Barang</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->kode_aset }} - {{ $item->nama }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-orange-50 dark:bg-orange-900/10">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Informasi Barang</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Kondisi saat ini: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->kondisi }}</span> |
                                Status: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->status_penggunaan }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-6">
                        @if (session('error'))
                            <div class="p-4 text-sm text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/50 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="p-4 text-sm text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/50 rounded-lg">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <label for="tingkat_kerusakan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tingkat Kerusakan <span class="text-red-500">*</span>
                            </label>
                            <select id="tingkat_kerusakan" wire:model.blur="tingkat_kerusakan"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500 @error('tingkat_kerusakan') border-red-500 @enderror">
                                @foreach ($tingkatKerusakanOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                @php
                                    $info = match($tingkat_kerusakan) {
                                        'Ringan', 'Sedang' => ['text-yellow-700 dark:text-yellow-300', 'bg-yellow-50 dark:bg-yellow-900/20', 'Status akan diubah menjadi Idle'],
                                        'Berat', 'Kritis' => ['text-orange-700 dark:text-orange-300', 'bg-orange-50 dark:bg-orange-900/20', 'Status akan diubah menjadi Dalam Perbaikan'],
                                        default => null,
                                    };
                                @endphp
                                @if ($info)
                                    <div class="flex items-center gap-2 text-xs {{ $info[0] }} {{ $info[1] }} px-3 py-2 rounded-lg">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $info[2] }}
                                    </div>
                                @endif
                            </div>
                            @error('tingkat_kerusakan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="deskripsi_kerusakan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Deskripsi Kerusakan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi_kerusakan" wire:model.blur="deskripsi_kerusakan" rows="4"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500 @error('deskripsi_kerusakan') border-red-500 @enderror"
                                      placeholder="Jelaskan secara detail kerusakan yang terjadi..."></textarea>
                            @error('deskripsi_kerusakan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="tindakan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tindakan yang Dilakukan
                            </label>
                            <textarea id="tindakan" wire:model.blur="tindakan" rows="3"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500 @error('tindakan') border-red-500 @enderror"
                                      placeholder="Tindakan yang sudah atau akan dilakukan (opsional)"></textarea>
                            @error('tindakan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catatan Tambahan
                            </label>
                            <textarea id="catatan" wire:model.blur="catatan" rows="2"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500 @error('catatan') border-red-500 @enderror"
                                      placeholder="Catatan tambahan (opsional)"></textarea>
                            @error('catatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('barang.show', $item) }}" wire:navigate
                           class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
