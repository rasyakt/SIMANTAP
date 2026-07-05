<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::getValue('nama_instansi', config('app.name', 'SIMANTAP')) }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900">
            <div class="w-full sm:max-w-md px-4 sm:px-0">
                <div class="flex flex-col items-center mb-6">
                    <a href="{{ url('/') }}" wire:navigate class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm ring-1 ring-white/20">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <span class="text-white font-semibold text-lg">{{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-2xl shadow-blue-900/20 px-6 py-8 sm:px-8 sm:py-10">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-blue-200/60">
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
