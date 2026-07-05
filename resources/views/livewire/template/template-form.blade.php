<div>
    <div class="mb-6">
        <a href="{{ route('template.index') }}" wire:navigate
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    {{ $template ? 'Edit Template Barang' : 'Tambah Template Barang' }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ $template ? 'Ubah data template barang' : 'Buat template barang baru' }}
                </p>
            </div>

            <form wire:submit="save" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Template <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" wire:model="nama"
                               class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors
                                      {{ $errors->first('nama')
                                          ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                          : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="merk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Merk
                        </label>
                        <input type="text" id="merk" wire:model="merk"
                               class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors
                                      {{ $errors->first('merk')
                                          ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                          : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}">
                        @error('merk')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tipe_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tipe / Model
                        </label>
                        <input type="text" id="tipe_model" wire:model="tipe_model"
                               class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors
                                      {{ $errors->first('tipe_model')
                                          ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                          : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}">
                        @error('tipe_model')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori_id" wire:model="kategori_id"
                                class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors
                                       {{ $errors->first('kategori_id')
                                           ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                           : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="satuan" wire:model="satuan"
                                class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors
                                       {{ $errors->first('satuan')
                                           ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                           : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}">
                            @foreach ($satuanOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('satuan')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimasi_harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Estimasi Harga
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                            <input type="text" id="estimasi_harga" wire:model="estimasi_harga" x-data
                                   x-on:input="
                                       let value = $event.target.value.replace(/[^0-9]/g, '');
                                       if (value) {
                                           $event.target.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                       } else {
                                           $event.target.value = '';
                                       }
                                       $dispatch('input', value);
                                   "
                                   x-init="
                                       let val = $wire.estimasi_harga;
                                       if (val) {
                                           $el.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                                       }
                                   "
                                   placeholder="0"
                                   class="w-full pl-10 pr-3 py-2.5 border rounded-lg text-sm transition-colors
                                          {{ $errors->first('estimasi_harga')
                                              ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                              : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}">
                        </div>
                        @error('estimasi_harga')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="spesifikasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Spesifikasi
                        </label>
                        <textarea id="spesifikasi" wire:model="spesifikasi" rows="3"
                                  class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors
                                         {{ $errors->first('spesifikasi')
                                             ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/20 focus:ring-red-500 focus:border-red-500'
                                             : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500' }}"
                                  placeholder="Masukkan spesifikasi template..."></textarea>
                        @error('spesifikasi')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Gambar
                        </label>
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <input type="file" id="gambar" wire:model="gambar" accept="image/jpeg,image/png,image/jpg"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:py-2.5 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-blue-50 dark:file:bg-blue-900/30
                                              file:text-blue-700 dark:file:text-blue-300
                                              hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50
                                              cursor-pointer transition-colors">
                                @error('gambar')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-400">Format: JPEG, PNG, JPG. Maks: 2MB</p>
                            </div>
                        </div>

                        @if ($gambar)
                            <div class="mt-3 relative inline-block">
                                <img src="{{ $gambar->temporaryUrl() }}" class="w-24 h-24 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                <button type="button" wire:click="removeGambar"
                                        class="absolute -top-2 -right-2 p-0.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @elseif ($existingGambar)
                            <div class="mt-3 relative inline-block">
                                <img src="{{ Storage::url($existingGambar) }}" class="w-24 h-24 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                <button type="button" wire:click="removeGambar"
                                        class="absolute -top-2 -right-2 p-0.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" wire:model="has_serial_number" id="has_serial_number"
                                       class="sr-only peer">
                                <div class="w-10 h-6 bg-gray-200 dark:bg-gray-600 rounded-full peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-0.5 after:start-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Memiliki Nomor Seri
                            </span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" wire:model="is_active" id="is_active"
                                       class="sr-only peer">
                                <div class="w-10 h-6 bg-gray-200 dark:bg-gray-600 rounded-full peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-0.5 after:start-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Aktif
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('template.index') }}" wire:navigate
                       class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            {{ $template ? 'Simpan Perubahan' : 'Simpan' }}
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
