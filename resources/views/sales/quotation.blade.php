<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Price Quotation') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('sales.quotation') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700">
                    Price Quotation
                </a>
                <a href="{{ route('sales.orders') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Sales Order
                </a>
                <a href="{{ route('sales.shipping') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Shipping Status
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:sales.price-quotation />
            </div>
        </div>
    </div>
</x-app-layout>