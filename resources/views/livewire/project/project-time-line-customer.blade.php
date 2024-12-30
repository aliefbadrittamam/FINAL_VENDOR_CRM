<div class="p-6">
    <!-- Header Section with Month Navigation -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Project Timeline</h2>
            <p class="mt-1 text-sm text-gray-600">Track your project schedules</p>
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
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <input type="text" wire:model.live="search" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Search projects...">
        </div>
        <div>
            <select wire:model.live="statusFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="all">Semua Status</option>
                <option value="Pending">Pending</option>
                <option value="Dalam Pengerjaan">Dalam Pengerjaan</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>
    </div>

    <!-- Calendar Section -->
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
                $firstDayOfMonth = Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
                $daysInMonth = $firstDayOfMonth->daysInMonth;
                $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;
            @endphp

            @for ($i = 0; $i < $firstDayOfWeek; $i++)
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
                            $status = $this->calculateProjectStatus($project);
                        @endphp

                        @if($currentDate->between($startDate, $endDate))
                            <div class="mb-1 text-xs rounded px-1 py-0.5 truncate
                                {{ $status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                   ($status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-blue-100 text-blue-800') }}"
                                title="{{ $project->project_header }}">
                                {{ $project->project_header }}
                                <div class="text-xs text-gray-500">{{ $project->vendor->vendor_name ?? 'No Vendor' }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <!-- Project List Below Calendar -->
    <di<!-- Project List Below Calendar -->
        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Current Projects
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @foreach($projects as $project)
                        @php
                            $status = $this->calculateProjectStatus($project);
                            $startDate = Carbon\Carbon::parse($project->project_duration_start);
                            $endDate = Carbon\Carbon::parse($project->project_duration_end);
                            $today = now();
                            
                            // Calculate progress
                            if ($today < $startDate) {
                                $progress = 0;
                            } elseif ($today > $endDate) {
                                $progress = 100;
                            } else {
                                $totalDays = $startDate->diffInDays($endDate) ?: 1;
                                $daysElapsed = $startDate->diffInDays($today);
                                $progress = min(100, round(($daysElapsed / $totalDays) * 100));
                            }
                        @endphp
                        <li class="px-4 py-4">
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $project->project_header }}</h4>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                                   ($status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-blue-100 text-blue-800') }}">
                                                {{ $status }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm text-gray-500">Vendor: {{ $project->vendor->vendor_name ?? 'No Vendor' }}</p>
                                            <div class="flex space-x-4">
                                                <p class="text-sm text-gray-500">
                                                    Start: {{ $startDate->format('d M Y') }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    End: {{ $endDate->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Progress Bar Section -->
                                <div class="w-full">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">Progress</span>
                                        <span class="text-sm font-medium text-gray-700">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full transition-all duration-500
                                            {{ $status === 'Selesai' ? 'bg-green-600' : 
                                               ($status === 'Pending' ? 'bg-yellow-600' : 
                                               'bg-blue-600') }}"
                                            style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                    <!-- Time Remaining -->
                                    <div class="mt-1 text-xs">
                                        @if($status === 'Pending')
                                            <span class="text-yellow-600">Starts in {{ $today->diffForHumans($startDate) }}</span>
                                        @elseif($status === 'Selesai')
                                            <span class="text-green-600">Completed {{ $endDate->diffForHumans() }}</span>
                                        @else
                                            <span class="text-blue-600">{{ $endDate->diffForHumans(null, true) }} remaining</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    <!-- Loading States -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 z-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-2 text-gray-600">Loading timeline...</p>
        </div>
    </div>
</div>