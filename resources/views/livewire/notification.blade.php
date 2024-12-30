<!-- resources/views/livewire/notification.blade.php -->
<div
    x-data="{ show: @entangle('show') }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300"
    x-transition:enter-start="translate-x-64 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform ease-in duration-200"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-64 opacity-0"
    @class([
        'fixed right-4 top-4 z-50',
        'pointer-events-none'
    ])
    x-init="setTimeout(() => { show = false }, 3000)"
>
    <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p>{{ $message }}</p>
    </div>
</div>