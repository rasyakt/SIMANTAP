<div>
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('lokasi.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $locationId ? 'Edit Lokasi' : 'Tambah Lokasi' }}
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
                                <x-input-label for="kode_lokasi" value="Kode Lokasi *" />
                                <x-text-input
                                    id="kode_lokasi"
                                    type="text"
                                    wire:model.blur="kode_lokasi"
                                    class="w-full"
                                    placeholder="Contoh: LAB-01"
                                    maxlength="20"
                                />
                                <x-input-error :messages="$errors->get('kode_lokasi')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="tipe_lokasi" value="Tipe Lokasi *" />
                                <select
                                    id="tipe_lokasi"
                                    wire:model.blur="tipe_lokasi"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach ($tipeOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipe_lokasi')" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="nama" value="Nama Lokasi *" />
                            <x-text-input
                                id="nama"
                                type="text"
                                wire:model.blur="nama"
                                class="w-full"
                                placeholder="Contoh: Laboratorium Komputer 1"
                                maxlength="255"
                            />
                            <x-input-error :messages="$errors->get('nama')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="parent_id" value="Lokasi Induk" />
                                <select
                                    id="parent_id"
                                    wire:model.blur="parent_id"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="">-- Tidak Ada (Lokasi Utama) --</option>
                                    @foreach ($parentLocations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->kode_lokasi }} - {{ $loc->nama }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Pilih lokasi induk jika ini adalah sub-lokasi</p>
                                <x-input-error :messages="$errors->get('parent_id')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="penanggung_jawab_id" value="Penanggung Jawab" />
                                <select
                                    id="penanggung_jawab_id"
                                    wire:model.blur="penanggung_jawab_id"
                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="">-- Pilih Penanggung Jawab --</option>
                                    @foreach ($penanggungJawabs as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('penanggung_jawab_id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="kapasitas" value="Kapasitas" />
                                <x-text-input
                                    id="kapasitas"
                                    type="number"
                                    wire:model.blur="kapasitas"
                                    class="w-full"
                                    placeholder="Contoh: 40"
                                    min="0"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ada</p>
                                <x-input-error :messages="$errors->get('kapasitas')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="is_active" value="Status" />
                                <label class="relative inline-flex items-center cursor-pointer mt-2">
                                    <input
                                        id="is_active"
                                        type="checkbox"
                                        wire:model="is_active"
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </label>
                                <x-input-error :messages="$errors->get('is_active')" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="deskripsi" value="Deskripsi" />
                            <textarea
                                id="deskripsi"
                                wire:model.blur="deskripsi"
                                rows="3"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                placeholder="Deskripsi lokasi (opsional)"
                                maxlength="1000"
                            ></textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('lokasi.index') }}" wire:navigate>
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>
                            {{ $locationId ? 'Simpan Perubahan' : 'Simpan' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
