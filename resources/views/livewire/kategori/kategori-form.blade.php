<div>
    <div class="mb-6">
        <a wire:navigate href="{{ route('kategori.index') }}"
           class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
            {{ $kategori?->exists ? 'Edit Kategori' : 'Tambah Kategori' }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            {{ $kategori?->exists ? 'Ubah data kategori yang sudah ada.' : 'Isi form untuk menambahkan kategori baru.' }}
        </p>

        @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300 rounded-lg">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" wire:model="nama" wire:blur="generateSlug"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                               placeholder="Masukkan nama kategori">
                        @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="slug" wire:model="slug"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                               placeholder="auto-generated">
                        @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" wire:model="deskripsi" rows="3"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi kategori (opsional)"></textarea>
                    @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kategori Induk
                        </label>
                        <select id="parent_id" wire:model="parent_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                            <option value="">-- Tidak Ada (Kategori Utama) --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Ikon
                        </label>
                        <input type="text" id="icon" wire:model="icon"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('icon') border-red-500 @enderror"
                               placeholder="contoh: 📦 atau fa-box">
                        @error('icon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_active"
                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</span>
                    </label>
                    @error('is_active') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ $kategori?->exists ? 'Simpan Perubahan' : 'Simpan' }}
                </button>
                <a wire:navigate href="{{ route('kategori.index') }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
