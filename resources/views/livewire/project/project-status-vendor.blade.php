<div class="p-6">
    <!-- Header dan Metrics Cards -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Projects Card -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Projects</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Value</span>
                        <span class="font-medium">Rp {{ number_format($metrics['total_value'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Projects Progress -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">On Track</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['on_track'] }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $metrics['on_track_rate'] }}%"></div>
                    </div>
                    <div class="mt-1 text-xs text-gray-500">{{ number_format($metrics['on_track_rate'], 1) }}% On Track Rate</div>
                </div>
            </div>

            <!-- Projects In Progress -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['in_progress'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Delayed</span>
                        <span class="font-medium text-red-600">{{ $metrics['delayed'] }} Projects</span>
                    </div>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['completed'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $metrics['completion_rate'] }}%"></div>
                    </div>
                    <div class="mt-1 text-xs text-gray-500">{{ number_format($metrics['completion_rate'], 1) }}% Completion Rate</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" wire:model.live="search" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search projects...">
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
            <div>
                <select wire:model.live="statusFilter" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Dalam Pengerjaan">Dalam Pengerjaan</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <div>
                <input type="date" wire:model.live="dateRangeFilter"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Select date">
           </div>
       </div>
   </div>

   <!-- Projects Table -->
   <div class="bg-white rounded-lg shadow overflow-hidden">
       <table class="min-w-full divide-y divide-gray-200">
           <thead class="bg-gray-50">
               <tr>
                   <th wire:click="sortBy('project_header')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                       Project
                       @if($sortField === 'project_header')
                           @if($sortDirection === 'asc')
                               <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                           @else
                               <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                           @endif
                       @endif
                   </th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Info</th>
                   <th wire:click="sortBy('project_duration_start')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                       Timeline
                       @if($sortField === 'project_duration_start')
                           @if($sortDirection === 'asc')
                               <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                           @else
                               <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                           @endif
                       @endif
                   </th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
               </tr>
           </thead>
           <tbody class="bg-white divide-y divide-gray-200">
               @forelse($projects as $project)
                   @php
                       $status = $this->getProjectStatus($project);
                       $daysInfo = $this->getDaysRemaining($project);
                   @endphp
                   <tr class="hover:bg-gray-50">
                       <td class="px-6 py-4">
                           <div class="text-sm font-medium text-gray-900">{{ $project->project_header }}</div>
                           <div class="text-sm text-gray-500">{{ Str::limit($project->project_detail, 50) }}</div>
                       </td>
                       <td class="px-6 py-4">
                           <div class="text-sm text-gray-900">
                               <div class="font-medium">{{ $project->customer->customer_name }}</div>
                               <div class="text-gray-500">{{ $project->customer->customer_email }}</div>
                           </div>
                       </td>
                       <td class="px-6 py-4">
                           <div class="text-sm text-gray-900">
                               <div>{{ Carbon\Carbon::parse($project->project_duration_start)->format('d M Y') }}</div>
                               <div>{{ Carbon\Carbon::parse($project->project_duration_end)->format('d M Y') }}</div>
                               <div class="{{ $daysInfo['class'] }} text-xs mt-1">
                                   {{ $daysInfo['text'] }}
                               </div>
                           </div>
                       </td>
                       <td class="px-6 py-4">
                           <div>
                               <div class="flex items-center">
                                   <div class="w-full bg-gray-200 rounded-full h-2.5">
                                       <div class="bg-{{ $status['color'] }}-600 h-2.5 rounded-full transition-all duration-500" 
                                            style="width: {{ $status['progress'] }}%">
                                       </div>
                                   </div>
                                   <span class="ml-2 text-sm font-medium text-gray-900">
                                       {{ number_format($status['progress'], 0) }}%
                                   </span>
                               </div>
                               <div class="flex items-center mt-2">
                                   <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status['badge_color'] }}">
                                       {{ $status['status'] }}
                                   </span>
                               </div>
                           </div>
                       </td>
                       <td class="px-6 py-4 text-sm font-medium">
                           <button wire:click="showProjectDetail({{ $project->project_id }})" 
                               class="text-blue-600 hover:text-blue-900">
                               View Details
                           </button>
                       </td>
                   </tr>
               @empty
                   <tr>
                       <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                           No projects found
                       </td>
                   </tr>
               @endforelse
           </tbody>
       </table>
   </div>

   <!-- Pagination -->
   <div class="mt-4">
       {{ $projects->links() }}
   </div>

   <!-- Project Detail Modal -->
   @if($showDetailModal && $selectedProject)
       <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
           <div class="fixed inset-0 z-10 overflow-y-auto">
               <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                   <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                       <!-- Modal content -->
                       <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                           <!-- Project details similar to the main component but with more detail -->
                           <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Project Details</h3>
                           
                           <!-- Basic Info -->
                           <div class="mb-4">
                               <h4 class="font-medium text-gray-700">{{ $selectedProject->project_header }}</h4>
                               <p class="text-sm text-gray-600 mt-1">{{ $selectedProject->project_detail }}</p>
                           </div>

                           <!-- Timeline -->
                           <div class="mb-4">
                               <div class="grid grid-cols-2 gap-4">
                                   <div>
                                       <span class="text-sm font-medium text-gray-500">Start Date</span>
                                       <p>{{ Carbon\Carbon::parse($selectedProject->project_duration_start)->format('d M Y') }}</p>
                                   </div>
                                   <div>
                                       <span class="text-sm font-medium text-gray-500">End Date</span>
                                       <p>{{ Carbon\Carbon::parse($selectedProject->project_duration_end)->format('d M Y') }}</p>
                                   </div>
                               </div>
                           </div>

                           <!-- Customer Info -->
                           <div class="mb-4">
                               <h4 class="text-sm font-medium text-gray-700 mb-2">Customer Information</h4>
                               <div>
                                   <p class="font-medium">{{ $selectedProject->customer->customer_name }}</p>
                                   <p class="text-sm text-gray-600">{{ $selectedProject->customer->customer_email }}</p>
                                   <p class="text-sm text-gray-600">{{ $selectedProject->customer->customer_phone }}</p>
                               </div>
                           </div>

                           <!-- Progress -->
                           @php
                               $status = $this->getProjectStatus($selectedProject);
                           @endphp
                           <div>
                               <h4 class="text-sm font-medium text-gray-700 mb-2">Project Progress</h4>
                               <div class="flex items-center">
                                   <div class="w-full bg-gray-200 rounded-full h-2.5">
                                       <div class="bg-{{ $status['color'] }}-600 h-2.5 rounded-full" 
                                            style="width: {{ $status['progress'] }}%">
                                       </div>
                                   </div>
                                   <span class="ml-2 text-sm font-medium">
                                       {{ number_format($status['progress'], 0) }}%
                                   </span>
                               </div>
                               <div class="mt-2">
                                   <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status['badge_color'] }}">
                                       {{ $status['status'] }}
                                   </span>
                               </div>
                           </div>
                       </div>

                       <!-- Modal footer -->
                       <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                           <button type="button" wire:click="$set('showDetailModal', false)"
                               class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                               Close
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   @endif

   <!-- Loading State -->
   <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 z-50 flex items-center justify-center">
       <div class="bg-white p-4 rounded-lg shadow-lg">
           <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
           <p class="mt-2 text-gray-600">Loading...</p>
       </div>
   </div>
</div>