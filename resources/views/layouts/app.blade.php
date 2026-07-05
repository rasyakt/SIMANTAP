<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? \App\Models\Setting::getValue('nama_instansi', config('app.name', 'SIMANTAP')) }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <livewire:layout.navigation />
        <div class="flex-1 flex flex-col" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'">
            <livewire:layout.header />
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
            <footer class="border-t border-gray-200 dark:border-gray-700 px-6 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}. All rights reserved.
            </footer>
        </div>
    </div>
    @livewireScripts
</body>
</html>
