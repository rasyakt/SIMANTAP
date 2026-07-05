<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('barang.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $item?->exists ? 'Edit Barang' : 'Tambah Barang' }}
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <form wire:submit="save" enctype="multipart/form-data">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="kode_aset" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kode Aset <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="kode_aset" wire:model.blur="kode_aset"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('kode_aset') border-red-500 @enderror"
                                       placeholder="Otomatis dari lokasi & kategori"
                                       {{ $item?->exists ? 'readonly' : '' }}>
                                @if (!$item?->exists && empty($kode_aset))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Pilih lokasi dan kategori untuk menghasilkan kode aset otomatis</p>
                                @endif
                                @error('kode_aset') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama Barang <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama" wire:model.blur="nama"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                                       placeholder="Masukkan nama barang">
                                @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" wire:model.blur="deskripsi" rows="3"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                                      placeholder="Deskripsi barang (opsional)"></textarea>
                            @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kategori
                                </label>
                                <select id="kategori_id" wire:model.blur="kategori_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('kategori_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="lokasi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Lokasi
                                </label>
                                <select id="lokasi_id" wire:model.blur="lokasi_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('lokasi_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach ($lokasis as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->kode_lokasi }} - {{ $loc->nama }}</option>
                                    @endforeach
                                </select>
                                @error('lokasi_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="item_template_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Template
                                </label>
                                <select id="item_template_id" wire:model.blur="item_template_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('item_template_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Template --</option>
                                    @foreach ($templates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->nama }} {{ $tpl->merk ? '(' . $tpl->merk . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                @error('item_template_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Barang Induk
                                </label>
                                <select id="parent_id" wire:model.blur="parent_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                                    <option value="">-- Tidak Ada (Barang Utama) --</option>
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->kode_aset }} - {{ $parent->nama }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Pilih barang induk jika ini adalah sub-komponen</p>
                                @error('parent_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="nomor_seri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nomor Seri
                                </label>
                                <input type="text" id="nomor_seri" wire:model.blur="nomor_seri"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('nomor_seri') border-red-500 @enderror"
                                       placeholder="Nomor seri (opsional)">
                                @error('nomor_seri') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="space-y-2">
                                <label for="tanggal_pengadaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tgl. Pengadaan
                                </label>
                                <input type="date" id="tanggal_pengadaan" wire:model.blur="tanggal_pengadaan"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal_pengadaan') border-red-500 @enderror">
                                @error('tanggal_pengadaan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Vendor
                                </label>
                                <input type="text" id="vendor" wire:model.blur="vendor"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('vendor') border-red-500 @enderror"
                                       placeholder="Nama vendor">
                                @error('vendor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="sumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Sumber
                                </label>
                                <select id="sumber" wire:model.blur="sumber"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('sumber') border-red-500 @enderror">
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach ($sumberOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @error('sumber') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Harga
                                </label>
                                <input type="number" id="harga" wire:model.blur="harga" step="0.01" min="0"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('harga') border-red-500 @enderror"
                                       placeholder="0">
                                @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label for="kondisi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kondisi <span class="text-red-500">*</span>
                                </label>
                                <select id="kondisi" wire:model.blur="kondisi"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('kondisi') border-red-500 @enderror">
                                    @foreach ($kondisiOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @error('kondisi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="status_penggunaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Status Penggunaan <span class="text-red-500">*</span>
                                </label>
                                <select id="status_penggunaan" wire:model.blur="status_penggunaan"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('status_penggunaan') border-red-500 @enderror">
                                    @foreach ($statusOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @error('status_penggunaan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="satuan" wire:model.blur="satuan"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('satuan') border-red-500 @enderror"
                                       placeholder="unit, buah, dll">
                                @error('satuan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Jumlah
                                </label>
                                <input type="number" id="jumlah" wire:model.blur="jumlah" min="1"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('jumlah') border-red-500 @enderror"
                                       placeholder="1">
                                @error('jumlah') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Foto Barang
                                </label>
                                <input type="file" id="foto" wire:model="foto" accept="image/*"
                                       class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/50 dark:file:text-blue-300">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Maksimal 2MB. Format: JPG, PNG, GIF</p>
                                @error('foto') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                @if ($foto)
                                    <div class="mt-2">
                                        <img src="{{ $foto->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                    </div>
                                @elseif ($foto_existing)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($foto_existing) }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catatan
                            </label>
                            <textarea id="catatan" wire:model.blur="catatan" rows="2"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('catatan') border-red-500 @enderror"
                                      placeholder="Catatan tambahan (opsional)"></textarea>
                            @error('catatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('barang.index') }}" wire:navigate
                           class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            {{ $item?->exists ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
