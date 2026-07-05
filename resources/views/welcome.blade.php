<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ \App\Models\Setting::getValue('app.nama_instansi', 'SIMANTAP') }} - Sistem Informasi Manajemen Aset dan Perbaikan</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PHBhdGggZD0iTTM2IDM0djItSDI0di0yaDEyek0zNiAyNHYySDI0di0yaDEyeiIvPjwvZz48L2c+PC9zdmc+')] opacity-40"></div>

            <livewire:welcome.navigation />

            <div class="relative flex flex-col items-center justify-center min-h-screen px-6 pt-20 pb-16">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm mb-8 ring-1 ring-white/20">
                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight">
                        {{ \App\Models\Setting::getValue('app.nama_instansi', 'SIMANTAP') }}
                    </h1>
                    <p class="mt-4 text-lg sm:text-xl text-blue-100/80 max-w-2xl mx-auto leading-relaxed">
                        {{ \App\Models\Setting::getValue('app.deskripsi', 'Sistem Informasi Manajemen Aset dan Perbaikan Terintegrasi untuk pengelolaan barang inventaris, stok, dan perawatan aset secara efisien.') }}
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center px-8 py-3.5 rounded-xl bg-white text-blue-700 font-semibold text-sm shadow-lg shadow-blue-900/30 hover:bg-blue-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center px-8 py-3.5 rounded-xl bg-white text-blue-700 font-semibold text-sm shadow-lg shadow-blue-900/30 hover:bg-blue-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center px-8 py-3.5 rounded-xl bg-white/10 text-white font-semibold text-sm ring-1 ring-white/30 hover:bg-white/20 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Daftar
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="mt-20 w-full max-w-5xl mx-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 ring-1 ring-white/20">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Manajemen Aset</h3>
                            <p class="mt-2 text-blue-100/60 text-xs leading-relaxed">Catat, lacak, dan kelola seluruh aset inventaris dengan sistem terintegrasi.</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 ring-1 ring-white/20">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Manajemen Stok</h3>
                            <p class="mt-2 text-blue-100/60 text-xs leading-relaxed">Pantau ketersediaan stok barang dengan riwayat mutasi yang lengkap.</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 ring-1 ring-white/20">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-2.49 3.22a1 1 0 01-1.6 0l-2.49-3.22a4 4 0 015.58-5.58l.6.6.6-.6a4 4 0 015.58 5.58l-2.49 3.22a1 1 0 01-1.6 0l-2.49-3.22a4 4 0 00-5.58-5.58z" />
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Perawatan & Perbaikan</h3>
                            <p class="mt-2 text-blue-100/60 text-xs leading-relaxed">Kelola jadwal perawatan dan perbaikan aset secara terjadwal dan terpantau.</p>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="relative py-6 text-center">
                <p class="text-sm text-blue-200/60">
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('app.nama_instansi', 'SIMANTAP') }}. All rights reserved.
                </p>
            </footer>
        </div>
    </body>
</html>
