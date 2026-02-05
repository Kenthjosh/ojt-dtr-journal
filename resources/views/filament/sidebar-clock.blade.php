<div class="px-6 py-4 mt-auto border-t border-gray-100 dark:border-gray-800">
    <div x-data="{ time: '' }"
        x-init="setInterval(() => { time = new Date().toLocaleTimeString() }, 1000)"
        class="flex items-center gap-x-3 text-gray-600 dark:text-gray-400">
        <x-heroicon-o-clock class="w-5 h-5 text-primary-500" />
        <span x-text="time" class="text-sm font-bold font-mono"></span>
    </div>
</div>