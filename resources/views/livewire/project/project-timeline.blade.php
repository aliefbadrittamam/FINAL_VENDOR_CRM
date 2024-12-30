<div class="p-6">
    <!-- Header Section with Month Navigation -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Project Timeline</h2>
            <p class="mt-1 text-sm text-gray-600">View and track project schedules</p>
        </div>
        <div class="flex items-center space-x-4">
            <button wire:click="prevMonth" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <div class="flex flex-col items-center">
                <span class="text-lg font-medium">{{ $currentMonth }}</span>
                <button wire:click="resetTimeline" class="text-sm text-blue-600 hover:text-blue-800">
                    Today
                </button>
            </div>
            <button wire:click="nextMonth" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" wire:model.live="search" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Search projects...">
        </div>
        <div>
            <select wire:model.live="vendorFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="customerFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Customers</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Calendar Header -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                <div class="bg-gray-50 py-2 text-center">
                    <span class="text-sm font-medium text-gray-900">{{ $dayName }}</span>
                </div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            @php
                $firstDay = Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
                $daysInMonth = $firstDay->daysInMonth;
                $dayOfWeek = $firstDay->dayOfWeek;
            @endphp

            @for ($i = 0; $i < $dayOfWeek; $i++)
                <div class="bg-gray-50 p-2 min-h-[100px]"></div>
            @endfor

            @foreach($days as $day)
                <div class="bg-white p-2 min-h-[100px] relative {{ $day['isToday'] ? 'bg-blue-50' : '' }} 
                     {{ $day['isWeekend'] ? 'bg-gray-50' : '' }}">
                    <div class="text-right mb-2">
                        <span class="{{ $day['isToday'] ? 'bg-blue-600 text-white rounded-full w-6 h-6 inline-flex items-center justify-center' : 'text-gray-700' }}">
                            {{ $day['date'] }}
                        </span>
                    </div>

                    @foreach($projects as $project)
                        @php
                            $startDate = Carbon\Carbon::parse($project->project_duration_start);
                            $endDate = Carbon\Carbon::parse($project->project_duration_end);
                            $currentDate = Carbon\Carbon::parse($day['fullDate']);
                        @endphp

                        @if($currentDate->between($startDate, $endDate))
                            <div class="mb-1 text-xs bg-blue-100 text-blue-800 rounded px-1 py-0.5 truncate"
                                 title="{{ $project->project_header }}">
                                {{ $project->project_header }}
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <!-- Project List Below Calendar -->
    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Project Details
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($projects as $project)
                    @php
                        $status = $this->calculateProjectStatus($project);
                        $progress = is_array($status) ? $status['progress'] : null;
                        $statusText = is_array($status) ? $status['status'] : $status;
                    @endphp
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-blue-600 truncate">
                                        {{ $project->project_header }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $statusText === 'Completed' ? 'bg-green-100 text-green-800' :
                                               ($statusText === 'Not Started' ? 'bg-gray-100 text-gray-800' : 
                                                'bg-blue-100 text-blue-800') }}">
                                            {{ $statusText }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-between">
                                    <div class="sm:flex">
                                        <div class="mr-6">
                                            <p class="text-sm text-gray-500">
                                                Vendor: {{ $project->vendor->vendor_name ?? 'Tidak Ada Vendor' }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Customer: {{ $project->customer->customer_name }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                Start: {{ Carbon\Carbon::parse($project->project_duration_start)->format('d M Y') }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                End: {{ Carbon\Carbon::parse($project->project_duration_end)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($progress !== null)
                                        <div class="w-32">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" 
                                                         style="width: {{ $progress }}%">
                                                    </div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-500">
                                                    {{ number_format($progress, 0) }}%
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 z-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-2 text-gray-600">Loading timeline...</p>
        </div>
    </div>
</div>