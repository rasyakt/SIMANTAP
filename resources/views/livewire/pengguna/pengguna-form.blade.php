<div>
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('pengguna.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $user?->exists ? 'Edit Pengguna' : 'Tambah Pengguna' }}
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
                                <x-input-label for="name" value="Nama Lengkap *" />
                                <x-text-input
                                    id="name"
                                    type="text"
                                    wire:model.blur="name"
                                    class="w-full"
                                    placeholder="Contoh: John Doe"
                                    maxlength="255"
                                />
                                <x-input-error :messages="$errors->get('name')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="email" value="Email *" />
                                <x-text-input
                                    id="email"
                                    type="email"
                                    wire:model.blur="email"
                                    class="w-full"
                                    placeholder="contoh@email.com"
                                    maxlength="255"
                                />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label for="phone" value="Nomor Telepon" />
                                <x-text-input
                                    id="phone"
                                    type="text"
                                    wire:model.blur="phone"
                                    class="w-full"
                                    placeholder="Contoh: 08123456789"
                                    maxlength="20"
                                />
                                <x-input-error :messages="$errors->get('phone')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="password" value="{{ $user?->exists ? 'Password (biarkan kosong jika tidak diubah)' : 'Password *' }}" />
                                <x-text-input
                                    id="password"
                                    type="password"
                                    wire:model.blur="password"
                                    class="w-full"
                                    placeholder="{{ $user?->exists ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                                />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
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

                        <div class="space-y-2">
                            <x-input-label value="Role *" />
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                                @forelse ($roles as $role)
                                    <label class="relative flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ in_array((string) $role->id, $selectedRoles) ? 'border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                        <input
                                            type="checkbox"
                                            value="{{ $role->id }}"
                                            wire:model.blur="selectedRoles"
                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        >
                                        <span class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">Belum ada role tersedia.</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('selectedRoles')" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label value="Akses Lokasi" />
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pilih lokasi yang dapat diakses oleh pengguna ini</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                                @forelse ($locations as $location)
                                    <label class="relative flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ in_array((string) $location->id, $selectedLocations) ? 'border-indigo-500 dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                        <input
                                            type="checkbox"
                                            value="{{ $location->id }}"
                                            wire:model.blur="selectedLocations"
                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        >
                                        <span class="ms-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $location->nama }}</span>
                                        <span class="ms-auto text-xs text-gray-400 dark:text-gray-500">{{ $location->kode_lokasi }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">Belum ada lokasi tersedia.</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('selectedLocations')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('pengguna.index') }}" wire:navigate>
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>
                            {{ $user?->exists ? 'Simpan Perubahan' : 'Simpan' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
