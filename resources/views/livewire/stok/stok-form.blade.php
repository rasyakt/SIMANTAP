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
                    {{ $stockId ? 'Edit Stok' : 'Tambah Stok' }}
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

                        <div class="space-y-2">
                            <x-input-label for="nama" value="Nama Stok *" />
                            <x-text-input id="nama" type="text" wire:model.blur="nama" class="w-full" placeholder="Contoh: Kertas A4 80gr" maxlength="255" />
                            <x-input-error :messages="$errors->get('nama')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="kategori_id" value="Kategori *" />
                                <select id="kategori_id" wire:model.live="kategori_id"
                                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('kategori_id')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="item_template_id" value="Template Barang" />
                                <select id="item_template_id" wire:model.blur="item_template_id"
                                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Template (Opsional) --</option>
                                    @foreach ($itemTemplates as $template)
                                        <option value="{{ $template->id }}">{{ $template->nama }} {{ $template->merk ? '(' . $template->merk . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Pilih kategori terlebih dahulu untuk melihat template</p>
                                <x-input-error :messages="$errors->get('item_template_id')" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="lokasi_id" value="Lokasi *" />
                            <select id="lokasi_id" wire:model.blur="lokasi_id"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->nama }} ({{ $loc->kode_lokasi }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('lokasi_id')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="jumlah_stok" value="Jumlah Stok *" />
                                <x-text-input id="jumlah_stok" type="number" wire:model.blur="jumlah_stok" class="w-full" min="0" />
                                <x-input-error :messages="$errors->get('jumlah_stok')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="ambang_batas_minimum" value="Ambang Batas Minimum *" />
                                <x-text-input id="ambang_batas_minimum" type="number" wire:model.blur="ambang_batas_minimum" class="w-full" min="0" />
                                <p class="text-xs text-gray-500 dark:text-gray-400">Peringatan stok menipis jika stok <= nilai ini</p>
                                <x-input-error :messages="$errors->get('ambang_batas_minimum')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="satuan" value="Satuan *" />
                                <x-text-input id="satuan" type="text" wire:model.blur="satuan" class="w-full" placeholder="Contoh: pcs, kg, liter" maxlength="50" />
                                <x-input-error :messages="$errors->get('satuan')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="harga_satuan" value="Harga Satuan" />
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                                    <x-text-input id="harga_satuan" type="number" wire:model.blur="harga_satuan" class="w-full pl-10" min="0" step="0.01" placeholder="0" />
                                </div>
                                <x-input-error :messages="$errors->get('harga_satuan')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="vendor" value="Vendor" />
                                <x-text-input id="vendor" type="text" wire:model.blur="vendor" class="w-full" placeholder="Nama vendor/pemasok" maxlength="255" />
                                <x-input-error :messages="$errors->get('vendor')" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="catatan" value="Catatan" />
                            <textarea id="catatan" wire:model.blur="catatan" rows="3"
                                      class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      placeholder="Catatan tambahan (opsional)" maxlength="1000"></textarea>
                            <x-input-error :messages="$errors->get('catatan')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('stok.index') }}" wire:navigate>
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>
                            {{ $stockId ? 'Simpan Perubahan' : 'Simpan' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
