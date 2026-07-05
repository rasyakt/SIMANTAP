<div>
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('pengguna.index') }}" wire:navigate class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @can('pengguna.edit')
                        <a wire:navigate href="{{ route('pengguna.edit', $user->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Edit Pengguna
                        </a>
                    @endcan
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Pengguna</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</dt>
                            <dd class="mt-1">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">Nonaktif</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</dt>
                            <dd class="mt-1">
                                @foreach ($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 mr-1">{{ $role->name }}</span>
                                @endforeach
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Verifikasi Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}</dd>
                        </div>
                        @if ($user->locations->count() > 0)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lokasi yang Dikelola</dt>
                                <dd class="mt-1">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($user->locations as $loc)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">{{ $loc->nama }}</span>
                                        @endforeach
                                    </div>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
