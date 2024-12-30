<div class="bg-white rounded-lg shadow p-5">
    <div class="flex justify-between">
        <div>
            <p class="text-gray-500 text-sm">{{ $title }}</p>
            <h3 class="text-2xl font-bold">{{ $value }}</h3>
        </div>
        <div class="bg-{{ $color }}-100 rounded-full p-3">
            <x-heroicon-o-{{ $icon }} class="w-6 h-6 text-{{ $color }}-600"/>
        </div>
    </div>
</div>