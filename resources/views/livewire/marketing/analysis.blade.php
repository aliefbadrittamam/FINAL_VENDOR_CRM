<div>
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
                        <path d="M10.3009 13.6949L20.102 3.89742M10.5795 14.1355L12.8019 18.5804C13.339 19.6545 13.6075 20.1916 13.9458 20.3356C14.2394 20.4606 14.575 20.4379 14.8492 20.2747C15.1651 20.0866 15.3591 19.5183 15.7472 18.3818L19.9463 6.08434C20.2845 5.09409 20.4535 4.59896 20.3378 4.27142C20.2371 3.98648 20.013 3.76234 19.7281 3.66167C19.4005 3.54595 18.9054 3.71502 17.9151 4.05315L5.61763 8.2523C4.48114 8.64037 3.91289 8.83441 3.72478 9.15032C3.56153 9.42447 3.53891 9.76007 3.66389 10.0536C3.80791 10.3919 4.34498 10.6605 5.41912 11.1975L9.86397 13.42C10.041 13.5085 10.1295 13.5527 10.2061 13.6118C10.2742 13.6643 10.3352 13.7253 10.3876 13.7933C10.4468 13.87 10.491 13.9585 10.5795 14.1355Z" stroke="#4caf50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
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
                <svg class="h-6 w-6 text-purple-600" fill="#b189d2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#b189d2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M2,21h8a1,1,0,0,0,0-2H3.071A7.011,7.011,0,0,1,10,13a5.044,5.044,0,1,0-3.377-1.337A9.01,9.01,0,0,0,1,20,1,1,0,0,0,2,21ZM10,5A3,3,0,1,1,7,8,3,3,0,0,1,10,5Zm13,8.5v5a.5.5,0,0,1-.5.5h-1v2L19,19H14.5a.5.5,0,0,1-.5-.5v-5a.5.5,0,0,1,.5-.5h8A.5.5,0,0,1,23,13.5Z">
                        </path>
                    </g>
                </svg>
                </div>
            </div>
        </div>

        <!-- Pending Projects -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Affectivity</p>
                    <h3 class="text-2xl font-bold">{{$effectivity}} %</h3>
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
                        @foreach($campaigns as $campaign)
                            <tr>                               
                               <td class="px-6 py-4 whitespace-nowrap">{{$campaign->campaign->campaign_name}}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{$campaign->campaign->description}}</td> 
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <!-- Recent Customer Interactions -->
       <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Order and Campaign</h3>
    <div class="h-[400px]" wire:ignore>
    <div width="600" height="400">
        <canvas id="myChart"></canvas>
    </div>
    </div>
</div>
  <!-- Project Time Line Charts  -->
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Campaign Status</h3>
    <div class="h-[400px]" wire:ignore>
        <div width="600" height="400">
            <canvas id="orderChart"></canvas>
        </div>
    </div>
</div>

    <!-- Revenue Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Precentage</h3>
        <div class="h-[400px]" wire:ignore>
            <canvas id="percentChart"></canvas>
        </div>
    </div>
</div>
    

@script
<script>
    const total_send = @json($total_send); 
    const total_delivered = @json($total_delivered); 
    const dates_label = @json($dates_label); 
    const sales_daily = @json($sales_daily); 
    const messages_daily = @json($message_daily); 
    const delivered_precentage = (total_delivered*100)/total_send
    const undelivered = 100-delivered_precentage
    const ctx = document.getElementById('myChart');
    const ctOrder = document.getElementById('orderChart');
    const ctPercent = document.getElementById('percentChart');
    

    new Chart(ctx, {
    type: "bar",
    data: {
        labels: ["Sent", "Delivered"],
        datasets: [{
        backgroundColor: ["blue", "green"],
        data: [total_send, total_delivered]
        }]
    },
    options: {
        legend: {display: true},
        title: {
        display: true,
        }
    }
    });

    new Chart(ctOrder, {
    type: "line",
    data: {
        labels: dates_label,
        datasets: [{ 
        data: sales_daily,
        labels: "Order",
        borderColor: "red",
        fill: false
        }, { 
        data: messages_daily,
        labels: "Messages Sent",
        borderColor: "green",
        fill: false
        }]
    },
    options: {
        legend: {display: true}
    },
    title: {
        display: true,
    }
    });

    new Chart(ctPercent, {
        type: "pie",
        data: {
            labels: ["Delivered", "Undelivered"],
            datasets: [{
            backgroundColor: [ "#2b5797", "#e8c3b9"],
            data: [delivered_precentage, undelivered]
            }]
        },
        options: {
            title: {
            display: true,
            text: "World Wide Wine Production 2018"
            }
        }
        });
</script>
@endscript
