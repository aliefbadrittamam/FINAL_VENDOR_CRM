<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <livewire:products.catalog />
    </div>
</x-app-layout>