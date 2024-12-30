<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects Timeline') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('projects.index') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Project List
                </a>
                <a href="{{ route('projects.timeline') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700">
                    Timeline
                </a>
                <a href="{{ route('projects.status') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Status
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->role === 'Customers')
                <livewire:project.project-timeline-customer />
            @elseif(Auth::user()->role === 'Vendor')
                <livewire:project.project-timeline-vendor />
            @else
                <livewire:project.project-timeline />
            @endif
        </div>
    </div>

    <!-- Global Styles for Timeline -->
    <style>
        .calendar-day {
            min-height: 100px;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .calendar-day:last-child {
            border-right: none;
        }
        .project-bar {
            transition: all 0.3s ease;
        }
        .project-bar:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .loading-overlay {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(2px);
        }
    </style>

    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('scrollToToday', function () {
                const todayElement = document.querySelector('.is-today');
                if (todayElement) {
                    todayElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        });
    </script>
</x-app-layout>