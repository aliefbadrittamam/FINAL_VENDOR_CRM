<div>
    <div class="container">
        <!-- section cover -->
        <div>
            <header class="text-center py-4 border-b">
                <p class="text-sm text-gray-600">
                    Sleman, Yogyakarta | kontruksi@kontruksi.ac.id | CRM-Vendor.com | (221) 023145
                </p>
            </header>

            <!-- Title Section -->
            <main class="text-center py-4 page-break-after">
                <h1 class="text-2xl font-bold text-gray-800 uppercase">Marketing Analysis Report</h1>
                <h2 class="text-lg font-medium text-gray-700 mt-2">Document</h2>

                <!-- Prepared By -->
                <div class="mt-8">
                    <p class="text-base text-gray-600">Prepared By:</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">Marketing Staff</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{$date}}</p>
                    <p class="text-sm text-gray-600">alfiatulfitria@gmail.com</p>
                </div>
            </main>

            <!-- Footer -->
            <footer class="text-center py-4 page-break-before">
                <div class="text-gray-400 space-y-1">
                    <p>•</p>
                    <p>•</p>
                    <p>•</p>
                </div>
            </footer>
        </div>

        <!-- Page Break -->
        <div class="page-break"></div>

        <!-- Campaign Table Section -->
        <div class="flex flex-col justify-center items-center min-h-screen bg-gray-100">
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-700 mb-6">Campaign Report</h1>

            <!-- Table -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl">
                <table>
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Campaign</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-center">Delivered</th>
                            <th class="px-4 py-2 text-center">Undelivered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-left font-medium">{{ $campaign->campaign->campaign_name }}</td>
                            <td class="px-4 py-2 text-left">{{ $campaign->campaign->description }}</td>
                            <td class="px-4 py-2 text-center">{{ $campaign->total_delivered }}</td>
                            <td class="px-4 py-2 text-center">{{ $campaign->total_sent }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Page Break -->
        <div class="page-break"></div>
                <!-- Campaign Table Section -->
        <div class="flex flex-col justify-center items-center min-h-screen bg-gray-100">
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-700 mb-6">Customers Report</h1>

            <!-- Table -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl">
                <table>
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-center">Delivered</th>
                            <th class="px-4 py-2 text-center">Undelivered</th>
                            <th class="px-4 py-2 text-center">Precentege</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-left font-medium">{{ $customer->customer_name }}</td>
                            <td class="px-4 py-2 text-center">{{ $customer->total_delivered }}</td>
                            <td class="px-4 py-2 text-center">{{ $customer->total_sent }}</td>
                            <td class="px-4 py-2 text-center">
                                @php
                                    $percentage = $customer->total_sent > 0 ? ($customer->total_delivered * 100) / ($customer->total_sent+$customer->total_delivered) : 0;
                                @endphp
                                {{ number_format($percentage, 2) }}%
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="px-4 py-2 text-left font-medium"></td>
                            <td class="px-4 py-2 text-center">{{ $total->total_delivered }}</td>
                            <td class="px-4 py-2 text-center">{{ $total->total_sent }}</td>
                            <td class="px-4 py-2 text-center">
                                @php
                                    $percentage = $total->total_sent > 0 ? ($total->total_delivered * 100) / ($total->total_sent+$total->total_delivered ) : 0;
                                @endphp
                                {{ number_format($percentage, 2) }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="flex flex-col justify-center items-center min-h-screen bg-gray-100">
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-700 mb-6">Sales Report</h1>

            <!-- Table -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl">
                <table>
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-center">Amount</th>
                            <th class="px-4 py-2 text-center">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-left font-medium">{{ $sale->customer->customer_name }}</td>
                            <td class="px-4 py-2 text-center">Rp {{ number_format($sale->fixed_amount, 2, ',', '.') }}                            </td>
                            <td class="px-4 py-2 text-center">{{ $sale->sale_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
