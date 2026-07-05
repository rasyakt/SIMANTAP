<nav class="fixed top-0 inset-x-0 z-50 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" wire:navigate class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 backdrop-blur-sm ring-1 ring-white/20">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <span class="text-white font-semibold text-sm tracking-tight">{{ \App\Models\Setting::getValue('app.nama_instansi', 'SIMANTAP') }}</span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium backdrop-blur-sm ring-1 ring-white/20 hover:bg-white/20 transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium backdrop-blur-sm ring-1 ring-white/20 hover:bg-white/20 transition-all duration-200">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-lg bg-white text-blue-700 text-sm font-medium hover:bg-blue-50 transition-all duration-200">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
