<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Daftar Pengguna</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data pengguna sistem</p>
                </div>
                @can('pengguna.create')
                    <x-primary-button
                        wire:navigate
                        href="{{ route('pengguna.create') }}"
                        class="inline-flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Tambah Pengguna
                    </x-primary-button>
                @endcan
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

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Cari nama, email, atau telepon..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400"
                            >
                        </div>
                        <select
                            wire:model.live="filterRole"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400"
                        >
                            <option value="">Semua Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <button wire:click="sortBy('name')" class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                        Nama
                                        @if ($sortField === 'name')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telepon</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                                @if ($user->id === auth()->id())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 ml-1">Anda</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-4 py-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</span>
                                    </td>
                                    <td class="hidden lg:table-cell px-4 py-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->phone ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($user->roles as $role)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @can('pengguna.edit')
                                            <button
                                                wire:click="toggleActive({{ $user->id }})"
                                                class="inline-flex items-center gap-1.5 text-xs font-medium"
                                            >
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium cursor-default">
                                        @endcan
                                            @if ($user->is_active)
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                <span class="text-green-700 dark:text-green-400">Aktif</span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                <span class="text-red-700 dark:text-red-400">Nonaktif</span>
                                            @endif
                                        @can('pengguna.edit')
                                            </button>
                                        @else
                                            </span>
                                        @endcan
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <x-action-dropdown>
                                            <a wire:navigate href="{{ route('pengguna.show', $user->id) }}"
                                               class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                            @can('pengguna.edit')
                                                <a wire:navigate href="{{ route('pengguna.edit', $user->id) }}"
                                                   class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('pengguna.delete')
                                                @if ($user->id !== auth()->id())
                                                    <button wire:click="confirmDelete({{ $user->id }})"
                                                            class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                @endif
                                            @endcan
                                        </x-action-dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            @if ($search || $filterRole)
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada pengguna yang cocok dengan pencarian</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba gunakan kata kunci atau filter lain</p>
                                            @else
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada pengguna</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan pengguna baru untuk mulai menggunakan sistem.</p>
                                                @can('pengguna.create')
                                                    <x-primary-button
                                                        wire:navigate
                                                        href="{{ route('pengguna.create') }}"
                                                        class="mt-4"
                                                    >
                                                        Tambah Pengguna
                                                    </x-primary-button>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($deleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data x-init="$el.addEventListener('click', function(e) { if (e.target === $el) $wire.cancelDelete() })">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus pengguna <strong>{{ $deleteName }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button wire:click="cancelDelete">Batal</x-secondary-button>
                    <x-danger-button wire:click="delete">Hapus</x-danger-button>
                </div>
            </div>
        </div>
    @endif
</div>
