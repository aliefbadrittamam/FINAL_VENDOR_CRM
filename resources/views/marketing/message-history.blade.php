<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Whatsapp Campaign') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('marketing.whatsapp') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Campaign Data
                </a>
                <a href="{{ route('message.history') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700">
                    Message History
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:marketing.message-history />
        </div>
    </div>
</x-app-layout>