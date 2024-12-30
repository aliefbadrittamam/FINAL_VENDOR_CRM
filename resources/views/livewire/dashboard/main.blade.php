<div>
    <!-- Filter Section -->
   <!-- Filter Section -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
            <input 
                type="date" 
                id="startDate"
                wire:model.live="startDate" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            >
        </div>
        <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
            <input 
                type="date" 
                id="endDate"
                wire:model.live="endDate" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            >
        </div>
        <div>
            <label for="vendor" class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
            <select wire:model.live="selectedVendor" class="w-full rounded-md border-gray-300">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendorName)
                    <option value="{{ $vendorName }}">{{ $vendorName }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Optional: Loading indicator -->
    <div wire:loading class="mt-4 text-sm text-gray-500">
        Loading...
    </div>
</div> 
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Projects Card -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Projects</p>
                    <h3 class="text-2xl font-bold">{{ $totalProjects }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <!-- Project Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Vendors Card -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Vendors</p>
                    <h3 class="text-2xl font-bold">{{ $totalVendors }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Revenue</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($revenue) }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Projects -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Projects</p>
                    <h3 class="text-2xl font-bold">{{ $pendingProjects }}</h3>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Projects</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentProjects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->project_header }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->vendor->vendor_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($project->project_value) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <!-- Recent Customer Interactions -->
<div class="bg-white rounded-lg shadow">
   <div class="p-6">
       <h3 class="text-lg font-semibold mb-4">Recent Customer Interactions</h3>
       <div class="divide-y divide-gray-200">
           @forelse($recentInteractions as $interaction)
           <div class="py-4">
               <div class="flex items-start space-x-4">
                   <!-- Icon berdasarkan tipe interaksi -->
                   <div class="flex-shrink-0">
                       @switch($interaction->interaction_type)
                           @case('Call')
                               <div class="bg-blue-100 p-2 rounded-full">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                       <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                   </svg>
                               </div>
                               @break
                           @case('Email')
                               <div class="bg-green-100 p-2 rounded-full">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                       <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                       <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                   </svg>
                               </div>
                               @break
                           @case('Meeting')
                               <div class="bg-purple-100 p-2 rounded-full">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                                       <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                   </svg>
                               </div>
                               @break
                           @default
                               <div class="bg-gray-100 p-2 rounded-full">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                       <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                   </svg>
                               </div>
                       @endswitch
                   </div>
                   <!-- Konten interaksi -->
                   <div class="flex-1 min-w-0">
                       <div class="flex justify-between">
                           <p class="text-sm font-medium text-gray-900 truncate">
                               {{ $interaction->customer->customer_name ?? 'N/A' }}
                           </p>
                           <p class="text-sm text-gray-500">
                               {{ Carbon\Carbon::parse($interaction->interaction_date)->diffForHumans() }}
                           </p>
                       </div>
                       @if($interaction->notes)
                           <p class="mt-1 text-sm text-gray-500">
                               {{ Str::limit($interaction->notes, 100) }}
                           </p>
                       @endif
                       <div class="mt-2 flex items-center text-sm text-gray-500">
                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                               @switch($interaction->interaction_type)
                                   @case('Call') bg-blue-100 text-blue-800 @break
                                   @case('Email') bg-green-100 text-green-800 @break
                                   @case('Meeting') bg-purple-100 text-purple-800 @break
                                   @default bg-gray-100 text-gray-800
                               @endswitch">
                               {{ $interaction->interaction_type }}
                           </span>
                           @if($interaction->vendor)
                               <span class="ml-2 text-gray-400">•</span>
                               <span class="ml-2">{{ $interaction->vendor->vendor_name }}</span>
                           @endif
                       </div>
                   </div>
               </div>
           </div>
           @empty
           <div class="py-4 text-center text-gray-500">
               No recent interactions found
           </div>
           @endforelse
       </div>
   </div>
</div>
  <!-- Project Time Line Charts  -->
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Project Timeline</h3>
    <div class="h-[400px]" wire:ignore>
        <div id="timelineChart"></div>
    </div>
</div>

    <!-- Revenue Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Revenue Overview</h3>
        <div class="h-[400px]" wire:ignore>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>
    
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
ddocument.addEventListener('livewire:load', function() {
    const timelineOptions = {
        series: [{
            data: @json($chartData['timelineData'])
        }],
        chart: {
            height: 350,
            type: 'rangeBar',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '80%',
                rangeBarGroupRows: true
            }
        },
        xaxis: {
            type: 'datetime',
            labels: {
                format: 'dd MMM yyyy'
            }
        },
        tooltip: {
            custom: function({series, seriesIndex, dataPointIndex, w}) {
                const project = w.config.series[seriesIndex].data[dataPointIndex];
                return `
                    <div class="px-3 py-2">
                        <div class="font-medium">${project.x}</div>
                        <div class="text-sm">
                            Start: ${new Date(project.y[0]).toLocaleDateString()}<br>
                            End: ${new Date(project.y[1]).toLocaleDateString()}
                        </div>
                    </div>`;
            }
        }
    };

    const timelineChart = new ApexCharts(document.getElementById("timelineChart"), timelineOptions);
    timelineChart.render();

    Livewire.on('refreshCharts', (data) => {
        timelineChart.updateSeries([{
            data: data.timelineData
        }]);
    });
});
</script>
@endpush
        
  

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        const projectChart = new Chart(document.getElementById('projectChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['projectLabels']),
                datasets: [{
                    label: 'Projects',
                    data: @json($chartData['projectData']),
                    backgroundColor: '#3B82F6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Project Distribution'
                    }
                }
            }
        });

        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($chartData['revenueLabels']),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartData['revenueData']),
                    borderColor: '#10B981',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Revenue Trend'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        Livewire.on('refreshCharts', () => {
            projectChart.data.labels = @json($chartData['projectLabels']);
            projectChart.data.datasets[0].data = @json($chartData['projectData']);
            projectChart.update();

            revenueChart.data.labels = @json($chartData['revenueLabels']);
            revenueChart.data.datasets[0].data = @json($chartData['revenueData']);
            revenueChart.update();
        });
    });
</script>
@endpush