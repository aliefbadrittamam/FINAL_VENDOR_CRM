<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects Status') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('projects.index') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Project List
                </a>
                <a href="{{ route('projects.timeline') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                    Timeline
                </a>
                <a href="{{ route('projects.status') }}"
                    class="px-3 py-2 rounded-md text-sm font-medium bg-blue-100 text-blue-700">
                    Status
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->role === 'Customers')
                <livewire:project.project-status-customer />
            @elseif(Auth::user()->role === 'Vendor')
                <livewire:project.project-status-vendor />
            @else
                <livewire:project.project-status />
            @endif
        </div>
    </div>

    <!-- Scripts -->
    @push('scripts')
    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('showAlert', message => {
                You can implement your own alert system here
                alert(message);
            });
        });
    </script>
    @endpush

    <!-- Styles -->
    @push('styles')
    <style>
        /* Custom scrollbar styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.8);
        }

        /* Progress bar animation */
        .progress-bar-animate {
            transition: width 0.5s ease-in-out;
        }

        /* Card hover effects */
        .metric-card {
            transition: transform 0.2s ease-in-out;
        }

        .metric-card:hover {
            transform: translateY(-2px);
        }
    </style>
    @endpush
</x-app-layout>