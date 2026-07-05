<div>
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('stok.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $movementId ? 'Edit Mutasi Stok' : 'Mutasi Stok' }}
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form wire:submit="save">
                    <div class="p-6 space-y-6">
                        @if (session('success'))
                            <div class="p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="p-4 text-sm text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/50 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="stock_id" value="Pilih Stok *" />
                                <select id="stock_id" wire:model.live="stock_id"
                                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Stok --</option>
                                    @foreach ($stocks as $stk)
                                        <option value="{{ $stk->id }}">
                                            {{ $stk->nama }} ({{ $stk->kategori?->nama ?? '-' }}) - {{ number_format($stk->jumlah_stok, 0, ',', '.') }} {{ $stk->satuan }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('stock_id')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="tipe" value="Tipe Mutasi *" />
                                <div class="flex gap-3 mt-1">
                                    <label class="relative flex-1 cursor-pointer">
                                        <input type="radio" wire:model.live="tipe" value="masuk" class="sr-only peer">
                                        <div class="flex items-center justify-center gap-2 p-3 rounded-lg border-2 {{ $tipe === 'masuk' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-300 dark:border-gray-600' }} peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-colors">
                                            <svg class="w-5 h-5 {{ $tipe === 'masuk' ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <span class="text-sm font-medium {{ $tipe === 'masuk' ? 'text-green-700 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}">Stok Masuk</span>
                                        </div>
                                    </label>
                                    <label class="relative flex-1 cursor-pointer">
                                        <input type="radio" wire:model.live="tipe" value="keluar" class="sr-only peer">
                                        <div class="flex items-center justify-center gap-2 p-3 rounded-lg border-2 {{ $tipe === 'keluar' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'border-gray-300 dark:border-gray-600' }} peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/20 transition-colors">
                                            <svg class="w-5 h-5 {{ $tipe === 'keluar' ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0l-4-4m4 4l-4 4"/>
                                            </svg>
                                            <span class="text-sm font-medium {{ $tipe === 'keluar' ? 'text-orange-700 dark:text-orange-300' : 'text-gray-600 dark:text-gray-400' }}">Stok Keluar</span>
                                        </div>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('tipe')" />
                            </div>
                        </div>

                        @if ($selectedStock)
                            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Stok Tersedia</span>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($selectedStock->jumlah_stok, 0, ',', '.') }} {{ $selectedStock->satuan }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Lokasi</span>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedStock->lokasi?->nama ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Kategori</span>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedStock->kategori?->nama ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Ambang Minimum</span>
                                        <p class="font-semibold {{ $selectedStock->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                            {{ number_format($selectedStock->ambang_batas_minimum, 0, ',', '.') }} {{ $selectedStock->satuan }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="jumlah" value="Jumlah *" />
                                <x-text-input id="jumlah" type="number" wire:model.blur="jumlah" class="w-full" min="1" />
                                <x-input-error :messages="$errors->get('jumlah')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="harga_satuan" value="Harga Satuan" />
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                                    <x-text-input id="harga_satuan" type="number" wire:model.blur="harga_satuan" class="w-full pl-10" min="0" step="0.01" placeholder="0" />
                                </div>
                                <x-input-error :messages="$errors->get('harga_satuan')" />
                            </div>
                        </div>

                        @if ($tipe === 'masuk')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="to_location_id" value="Lokasi Tujuan *" />
                                    <select id="to_location_id" wire:model.blur="to_location_id"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->nama }} ({{ $loc->kode_lokasi }})</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('to_location_id')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="from_location_id" value="Lokasi Asal" />
                                    <select id="from_location_id" wire:model.blur="from_location_id"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">-- Tidak Ada --</option>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->nama }} ({{ $loc->kode_lokasi }})</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kosongi jika berasal dari luar sistem</p>
                                    <x-input-error :messages="$errors->get('from_location_id')" />
                                </div>
                            </div>
                        @elseif ($tipe === 'keluar')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="from_location_id" value="Lokasi Asal *" />
                                    <select id="from_location_id" wire:model.blur="from_location_id"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->nama }} ({{ $loc->kode_lokasi }})</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('from_location_id')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="to_location_id" value="Lokasi Tujuan" />
                                    <select id="to_location_id" wire:model.blur="to_location_id"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">-- Tidak Ada --</option>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->nama }} ({{ $loc->kode_lokasi }})</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kosongi jika keluar dari sistem</p>
                                    <x-input-error :messages="$errors->get('to_location_id')" />
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="item_id" value="Item Terkait" />
                                <select id="item_id" wire:model.blur="item_id"
                                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Item (Opsional) --</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->kode_aset }} - {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('item_id')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="referensi" value="Referensi" />
                                <x-text-input id="referensi" type="text" wire:model.blur="referensi" class="w-full" placeholder="Contoh: PO-001, No. Faktur" maxlength="255" />
                                <x-input-error :messages="$errors->get('referensi')" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="keterangan" value="Keterangan" />
                            <textarea id="keterangan" wire:model.blur="keterangan" rows="3"
                                      class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      placeholder="Keterangan mutasi (opsional)" maxlength="1000"></textarea>
                            <x-input-error :messages="$errors->get('keterangan')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('stok.index') }}" wire:navigate>
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>
                            {{ $movementId ? 'Simpan Perubahan' : 'Simpan Mutasi' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
