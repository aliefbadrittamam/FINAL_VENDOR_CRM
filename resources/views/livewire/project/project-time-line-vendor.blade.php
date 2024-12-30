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
        {{-- <div>
            <select wire:model.live="vendorFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                @endforeach
            </select>
        </div> --}}
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
            @if(is_array($days) || is_object($days))
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
            @endif

        </div>
    </div>

    <!-- Project List Below Calendar -->
    <!-- Project List Section -->
<div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Project Details</h3>
    </div>
    <div class="border-t border-gray-200">
        <ul class="divide-y divide-gray-200">
            @foreach($projects as $project)
                @php
                    $startDate = \Carbon\Carbon::parse($project->project_duration_start);
                    $endDate = \Carbon\Carbon::parse($project->project_duration_end);
                    $now = \Carbon\Carbon::now();
                    
                    // Calculate progress
                    if ($now < $startDate) {
                        $progress = 0;
                    } elseif ($now > $endDate) {
                        $progress = 100;
                    } else {
                        $totalDays = $startDate->diffInDays($endDate) ?: 1;
                        $daysElapsed = $startDate->diffInDays($now);
                        $progress = min(100, round(($daysElapsed / $totalDays) * 100));
                    }
                    
                    $status = $this->calculateProjectStatus($project);
                @endphp

                <li class="px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-medium text-gray-900">{{ $project->project_header }}</h4>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                       ($status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-blue-100 text-blue-800') }}">
                                    {{ $status }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">{{ $project->project_detail }}</p>
                            
                            <!-- Project Info -->
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500">Customer</div>
                                    <div class="font-medium">{{ $project->customer->customer_name }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Duration</div>
                                    <div class="font-medium">
                                        {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Progress</span>
                                    <span class="text-sm font-medium text-gray-700">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $status === 'Selesai' ? 'bg-green-600' : 
                                         ($status === 'Pending' ? 'bg-yellow-600' : 'bg-blue-600') }}"
                                         style="width: {{ $progress }}%">
                                    </div>
                                </div>
                                
                                <!-- Time Remaining -->
                                <div class="mt-1 text-xs text-gray-500">
                                    @if($status === 'Pending')
                                        Starts in {{ $now->diffForHumans($startDate) }}
                                    @elseif($status === 'Selesai')
                                        Completed {{ $endDate->diffForHumans() }}
                                    @else
                                        {{ $now->diffForHumans($endDate, ['parts' => 1]) }} remaining
                                    @endif
                                </div>
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