@props(['align' => 'right'])

<div class="relative inline-block text-left"
     x-data="{
         open: false,
         menuTop: 0,
         menuLeft: 0
     }"
     @click.outside="open = false">
     <button @click="
             open = !open;
             if (open) {
                 const rect = $refs.button.getBoundingClientRect();
                 const menuHeight = 160;
                 const spaceBelow = window.innerHeight - rect.bottom;
                 if (spaceBelow < menuHeight) {
                     menuTop = rect.top - menuHeight - 4;
                 } else {
                     menuTop = rect.bottom + 4;
                 }
                 @js($align) === 'right'
                     ? menuLeft = rect.right - 160
                     : menuLeft = rect.left;
             }
         "
         x-ref="button"
        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none border border-gray-200 dark:border-gray-600"
        title="Aksi">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="5" r="1.5"/>
            <circle cx="12" cy="12" r="1.5"/>
            <circle cx="12" cy="19" r="1.5"/>
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed z-[9999] w-40 rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10 focus:outline-none"
         :style="`top: ${menuTop}px; left: ${menuLeft}px`"
         @click="open = false"
         x-cloak>
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>
