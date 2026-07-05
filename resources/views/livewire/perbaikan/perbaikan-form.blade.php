<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('perbaikan.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $isEditing ? 'Edit Perbaikan' : 'Tambah Perbaikan' }}
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

                        @if ($errors->any())
                            <div class="p-4 text-sm text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/50 rounded-lg">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Informasi Barang</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Pilih barang/aset yang akan diperbaiki</p>

                            <div class="space-y-2 relative">
                                <x-input-label for="item_search" value="Cari Barang *" />
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" id="item_search" wire:model.live="item_search" autocomplete="off"
                                           class="w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('item_id') border-red-500 @enderror"
                                           placeholder="Cari berdasarkan kode aset atau nama barang...">
                                    @if ($item_id)
                                        <button type="button" wire:click="clearItem" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <x-input-error :messages="$errors->get('item_id')" />

                                @if ($showItemDropdown && count($items) > 0)
                                    <div class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach ($items as $item)
                                            <button type="button" wire:click="selectItem({{ $item->id }})"
                                                    class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->kode_aset }}</span>
                                                <span class="text-gray-500 dark:text-gray-400 ml-2">{{ $item->nama }}</span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500 ml-2">{{ $item->kondisi }} / {{ $item->status_penggunaan }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($item_kode_aset && $item_nama)
                                    <div class="flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-sm text-blue-700 dark:text-blue-300">
                                            <span class="font-medium">{{ $item_kode_aset }}</span> - {{ $item_nama }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Detail Laporan</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Informasi pelaporan kerusakan</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="tanggal_laporan" value="Tanggal Laporan *" />
                                    <x-text-input id="tanggal_laporan" type="date" wire:model.blur="tanggal_laporan" class="w-full" />
                                    <x-input-error :messages="$errors->get('tanggal_laporan')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="tingkat_kerusakan" value="Tingkat Kerusakan *" />
                                    <select id="tingkat_kerusakan" wire:model.blur="tingkat_kerusakan"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('tingkat_kerusakan') border-red-500 @enderror">
                                        @foreach ($tingkatOptions as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('tingkat_kerusakan')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="dilaporkan_oleh" value="Dilaporkan Oleh" />
                                    <select id="dilaporkan_oleh" wire:model.blur="dilaporkan_oleh"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('dilaporkan_oleh') border-red-500 @enderror">
                                        <option value="">-- Pilih Pelapor --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('dilaporkan_oleh')" />
                                </div>
                            </div>

                            <div class="space-y-2 mt-6">
                                <x-input-label for="deskripsi_kerusakan" value="Deskripsi Kerusakan *" />
                                <textarea id="deskripsi_kerusakan" wire:model.blur="deskripsi_kerusakan" rows="4"
                                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('deskripsi_kerusakan') border-red-500 @enderror"
                                          placeholder="Jelaskan kerusakan yang terjadi..."></textarea>
                                <x-input-error :messages="$errors->get('deskripsi_kerusakan')" />
                            </div>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Tindakan Perbaikan</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Informasi penanganan perbaikan</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="tindakan" value="Tindakan" />
                                    <x-text-input id="tindakan" type="text" wire:model.blur="tindakan" class="w-full"
                                                  placeholder="Contoh: Perbaikan, Penggantian, Kalibrasi" />
                                    <x-input-error :messages="$errors->get('tindakan')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="ditangani_oleh" value="Ditangani Oleh" />
                                    <select id="ditangani_oleh" wire:model.blur="ditangani_oleh"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('ditangani_oleh') border-red-500 @enderror">
                                        <option value="">-- Pilih Petugas --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('ditangani_oleh')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="vendor_eksternal" value="Vendor Eksternal" />
                                    <x-text-input id="vendor_eksternal" type="text" wire:model.blur="vendor_eksternal" class="w-full"
                                                  placeholder="Nama vendor (jika ada)" />
                                    <x-input-error :messages="$errors->get('vendor_eksternal')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="biaya" value="Biaya" />
                                    <x-text-input id="biaya" type="number" step="0.01" min="0" wire:model.blur="biaya" class="w-full"
                                                  placeholder="0" />
                                    <x-input-error :messages="$errors->get('biaya')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="stock_id" value="Stok Terkait" />
                                    <select id="stock_id" wire:model.blur="stock_id"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('stock_id') border-red-500 @enderror">
                                        <option value="">-- Pilih Stok (opsional) --</option>
                                        @foreach ($stocks as $stock)
                                            <option value="{{ $stock->id }}">{{ $stock->nama }} ({{ $stock->jumlah_stok }} {{ $stock->satuan }})</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('stock_id')" />
                                </div>
                            </div>

                            <div class="space-y-2 mt-6">
                                <x-input-label for="tindakan_detail" value="Detail Tindakan" />
                                <textarea id="tindakan_detail" wire:model.blur="tindakan_detail" rows="3"
                                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('tindakan_detail') border-red-500 @enderror"
                                          placeholder="Jelaskan detail tindakan yang dilakukan..."></textarea>
                                <x-input-error :messages="$errors->get('tindakan_detail')" />
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Penyelesaian</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Isi jika perbaikan telah selesai</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="tanggal_selesai" value="Tanggal Selesai" />
                                    <x-text-input id="tanggal_selesai" type="date" wire:model.blur="tanggal_selesai" class="w-full" />
                                    <x-input-error :messages="$errors->get('tanggal_selesai')" />
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="status_akhir" value="Status Akhir" />
                                    <x-text-input id="status_akhir" type="text" wire:model.blur="status_akhir" class="w-full"
                                                  placeholder="Contoh: Berfungsi, Diganti, Tidak Dapat Diperbaiki" />
                                    <x-input-error :messages="$errors->get('status_akhir')" />
                                </div>
                            </div>

                            <div class="space-y-2 mt-6">
                                <x-input-label for="catatan" value="Catatan" />
                                <textarea id="catatan" wire:model.blur="catatan" rows="3"
                                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('catatan') border-red-500 @enderror"
                                          placeholder="Catatan tambahan..."></textarea>
                                <x-input-error :messages="$errors->get('catatan')" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('perbaikan.index') }}" wire:navigate>
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>
                            {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
