<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Pengaturan Aplikasi</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola pengaturan sistem secara global</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/50 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @foreach ($settingsGrouped as $group => $groupSettings)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            @switch($group)
                                @case('general')
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Umum
                                    </div>
                                @break
                                @case('notifikasi')
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        Notifikasi
                                    </div>
                                @break
                                @case('aset')
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Aset
                                    </div>
                                @break
                                @default
                                    {{ ucfirst($group) }}
                            @endswitch
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($groupSettings as $setting)
                            <div class="px-6 py-4 flex items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        @switch($setting->key)
                                            @case('nama_instansi') Nama Instansi @break
                                            @case('alamat') Alamat @break
                                            @case('kota') Kota @break
                                            @case('provinsi') Provinsi @break
                                            @case('nomor_telp') Nomor Telepon @break
                                            @case('email') Email @break
                                            @case('website') Website @break
                                            @case('ambang_stok_default') Ambang Stok Default @break
                                            @case('hari_tenggang_perbaikan') Hari Tenggang Perbaikan @break
                                            @case('format_kode') Format Kode Aset @break
                                            @case('prefix_auto') Prefix Otomatis @break
                                            @default {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                        @endswitch
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-mono">{{ $setting->key }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if ($editing && $editKey === $setting->key)
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="text"
                                                wire:model.blur="editValue"
                                                class="w-48 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-1.5 text-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400"
                                                autofocus
                                                wire:keydown.enter="saveSetting"
                                                wire:keydown.escape="cancelEdit"
                                            >
                                            <button
                                                wire:click="saveSetting"
                                                class="p-1.5 text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-colors"
                                                title="Simpan"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                            <button
                                                wire:click="cancelEdit"
                                                class="p-1.5 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                                title="Batal"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $setting->value }}</span>
                                        @can('pengaturan.edit')
                                            <button
                                                wire:click="startEdit('{{ $setting->key }}', '{{ $setting->value }}')"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors"
                                                title="Edit"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if ($settingsGrouped->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada pengaturan</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Pengaturan akan muncul setelah ditambahkan oleh administrator.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
