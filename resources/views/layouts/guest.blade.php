<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::getValue('nama_instansi', config('app.name', 'SIMANTAP')) }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:flex-row">
            
            <!-- Left Side: Branding & Info (Hidden on mobile, takes 50% on tablet, 45% on desktop) -->
            <div class="hidden sm:flex sm:w-1/2 lg:w-[45%] bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 p-10 lg:p-16 flex-col justify-between relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
                    <svg class="absolute -top-32 -left-32 w-[30rem] h-[30rem] text-white transform rotate-45" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
                    <svg class="absolute -bottom-32 -right-32 w-[30rem] h-[30rem] text-blue-300 transform rotate-12" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
                    
                    <!-- Grid pattern overlay -->
                    <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px); background-size: 32px 32px;"></div>
                </div>
                
                <!-- Logo -->
                <div class="relative z-10">
                    <a href="{{ url('/') }}" wire:navigate class="inline-flex items-center gap-3 group">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white shadow-lg group-hover:bg-gray-50 transition-all duration-300 overflow-hidden">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="text-white font-bold text-2xl tracking-tight">{{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}</span>
                    </a>
                </div>

                <!-- Hero Text -->
                <div class="relative z-10 mt-16 mb-auto">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-600/50 backdrop-blur-sm border border-blue-400/30 text-blue-100 text-xs font-semibold uppercase tracking-wider mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-300 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                        </span>
                        Enterprise Asset Management
                    </div>
                    
                    <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-[1.15] mb-6 tracking-tight">
                        Sistem Informasi <br/>
                        <span class="text-blue-300">Manajemen Aset</span>,<br/>
                        iNventaris, dan <br/>
                        Tata Penyimpanan
                    </h1>
                    <p class="text-blue-100/90 text-lg leading-relaxed max-w-lg font-medium">
                        Kelola seluruh aset dan inventaris instansi Anda dengan mudah, cepat, dan akurat melalui platform terintegrasi.
                    </p>
                </div>

                <!-- Footer (Left Side) -->
                <div class="relative z-10 mt-12 flex items-center gap-4">
                    <p class="text-sm text-blue-200/70 font-medium">
                        &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}. All rights reserved.
                    </p>
                </div>
            </div>

            <!-- Right Side: Auth Form -->
            <div class="w-full sm:w-1/2 lg:w-[55%] bg-white sm:bg-gray-50 flex flex-col justify-center items-center p-6 sm:p-12 min-h-screen sm:min-h-0 relative">
                
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="sm:hidden flex flex-col items-center mb-10 w-full pt-8">
                    <a href="{{ url('/') }}" wire:navigate class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white shadow-lg overflow-hidden">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="text-gray-900 font-extrabold text-2xl tracking-tight">{{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}</span>
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full max-w-[420px] bg-white sm:rounded-2xl sm:shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:border border-gray-100 sm:px-10 sm:py-12 relative z-10">
                    {{ $slot }}
                </div>
                
                <!-- Mobile Footer -->
                <div class="sm:hidden mt-auto pt-12 pb-6">
                     <p class="text-xs text-gray-400 font-medium text-center">
                        &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}.
                    </p>
                </div>
            </div>
            
        </div>
    </body>
</html>
