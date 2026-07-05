<div>
    <div class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-16'">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-2" :class="sidebarOpen ? '' : 'lg:justify-center lg:w-full'">
                <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center bg-white border border-gray-100 shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span x-show="sidebarOpen" class="text-sm font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap">SIMANTAP</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 lg:block hidden">
                <svg class="w-5 h-5 transition-transform duration-300" :class="!sidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 4rem);">
            @php
                $menuItems = [
                    ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'permission' => 'dashboard.view'],
                    ['label' => 'Lokasi', 'route' => 'lokasi.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'permission' => 'lokasi.list'],
                    ['label' => 'Kategori', 'route' => 'kategori.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'permission' => 'kategori.list'],
                    ['label' => 'Template Barang', 'route' => 'template.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'permission' => 'template.list'],
                    ['label' => 'Barang / Aset', 'route' => 'barang.index', 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'permission' => 'barang.list'],
                    ['label' => 'Stok Gudang', 'route' => 'stok.index', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'permission' => 'stok.list'],
                    ['label' => 'Perbaikan', 'route' => 'perbaikan.index', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'permission' => 'perbaikan.list'],
                    ['label' => 'Laporan', 'route' => 'laporan.index', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'permission' => 'laporan.view'],
                    ['label' => 'Pengguna', 'route' => 'pengguna.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z', 'permission' => 'pengguna.list'],
                    ['label' => 'Log Aktivitas', 'route' => 'log-aktivitas.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'permission' => 'log-aktivitas.view'],
                    ['label' => 'Developer Tools', 'route' => 'developer.index', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'permission' => 'backup.view'],
                    ['label' => 'Pengaturan', 'route' => 'pengaturan.index', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'permission' => 'pengaturan.view'],
                ];
            @endphp

            @foreach($menuItems as $item)
                @can($item['permission'])
                    <a href="{{ route($item['route']) }}"
                       wire:navigate
                       @class([
                           'flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150',
                           'bg-blue-50 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' => request()->routeIs($item['route'] . '*'),
                           'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' => !request()->routeIs($item['route'] . '*'),
                       ])
                       x-data
                       :title="!sidebarOpen ? '{{ $item['label'] }}' : ''">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mx-auto lg:mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span x-show="sidebarOpen" class="truncate">{{ $item['label'] }}</span>
                    </a>
                @endcan
            @endforeach
        </nav>
    </div>
</div>
