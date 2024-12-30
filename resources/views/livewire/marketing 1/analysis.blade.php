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
            <button type="button" class="mt-7 bg-green-500 text-white hover:bg-green-700 hover:text-white rounded px-4 py-2 inline-flex items-center">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.07,8A8,8,0,0,1,20,12"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.93,16A8,8,0,0,1,4,12"></path>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5 3 5 8 10 8"></polyline>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="19 21 19 16 14 16"></polyline>
                </svg>
                Export
            </button>
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
                    <p class="text-gray-500 text-sm">Send Message</p>
                    <h3 class="text-2xl font-bold">{{$total_send}}</h3>
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
                    <p class="text-gray-500 text-sm">Delivered Message</p>
                    <h3 class="text-2xl font-bold">{{$total_delivered}}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                <svg class="h-6 w-6 text-green-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier"> 
                        <path d="M10.3009 13.6949L20.102 3.89742M10.5795 14.1355L12.8019 18.5804C13.339 19.6545 13.6075 20.1916 13.9458 20.3356C14.2394 20.4606 14.575 20.4379 14.8492 20.2747C15.1651 20.0866 15.3591 19.5183 15.7472 18.3818L19.9463 6.08434C20.2845 5.09409 20.4535 4.59896 20.3378 4.27142C20.2371 3.98648 20.013 3.76234 19.7281 3.66167C19.4005 3.54595 18.9054 3.71502 17.9151 4.05315L5.61763 8.2523C4.48114 8.64037 3.91289 8.83441 3.72478 9.15032C3.56153 9.42447 3.53891 9.76007 3.66389 10.0536C3.80791 10.3919 4.34498 10.6605 5.41912 11.1975L9.86397 13.42C10.041 13.5085 10.1295 13.5527 10.2061 13.6118C10.2742 13.6643 10.3352 13.7253 10.3876 13.7933C10.4468 13.87 10.491 13.9585 10.5795 14.1355Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg> -->
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Customer Sent</p>
                    <h3 class="text-2xl font-bold"> {{$total_send_customer}}</h3>
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
                    <p class="text-gray-500 text-sm">Affectivity</p>
                    <h3 class="text-2xl font-bold"></h3>
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
                <h3 class="text-lg font-semibold mb-4">Campaign Send</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"></td>
                                <td class="px-6 py-4 whitespace-nowrap"></td>
                            </tr>
                    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <!-- Recent Customer Interactions -->
       <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Order and Campaign</h3>
    <div class="h-[400px]" wire:ignore>
    <div>
        <canvas id="myChart"></canvas>
    </div>
    </div>
</div>
  <!-- Project Time Line Charts  -->
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Campaign Status</h3>
    <div class="h-[400px]" wire:ignore>
        <div id="timelineChart"></div>
    </div>
</div>

    <!-- Revenue Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Precentage</h3>
        <div class="h-[400px]" wire:ignore>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>
    
@script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endscript
@script
<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
    title: {
        display: true,
        text: 'Chart.js Bar Chart'
      }
  });
</script>
@endscript
