<div class="p-6">
    <!-- Card Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Customers -->
        <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Customers</p>
                    <p class="text-2xl font-semibold">{{ $statistics['total_customers'] }}</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                Total pelanggan yang terdaftar dalam sistem
            </div>
        </div>

        <!-- Active Customers -->
        <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Active Customers</p>
                    <p class="text-2xl font-semibold">{{ $statistics['active_customers'] }}</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                Pelanggan aktif dalam 30 hari terakhir
            </div>
        </div>

        <!-- Total Interactions -->
        <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Interactions</p>
                    <p class="text-2xl font-semibold">{{ $statistics['total_interactions'] }}</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                Total interaksi dengan pelanggan
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-medium mb-4">Filter & Analisis</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Period Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Analisis</label>
                <select wire:model.live="dateRange" class="w-full rounded-md border-gray-300">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="90">90 Hari Terakhir</option>
                    <option value="all">Semua Waktu</option>
                </select>
            </div>

            <!-- Interaction Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Interaksi</label>
                <select wire:model.live="interactionType" class="w-full rounded-md border-gray-300">
                    <option value="all">Semua Tipe</option>
                    <option value="Call">Telepon</option>
                    <option value="Email">Email</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Other">Lainnya</option>
                </select>
            </div>

            <!-- Minimum Interactions Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Interaksi</label>
                <input type="number" wire:model.live="minimumInteractions" 
                    class="w-full rounded-md border-gray-300" min="0">
            </div>
        </div>
    </div>

    <!-- Customer List with Segments -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-lg font-medium">Customer Segments</h3>
            <button wire:click="exportSegmentation" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Data
            </button>
        </div>

        <!-- Customer Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Segment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interactions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projects</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Interaction</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        @php
                            $segment = $this->getSegmentLabel([
                                'interaction_count' => $customer->interaction_count,
                                'project_count' => $customer->project_count
                            ]);
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $customer->customer_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $customer->customer_email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $segment['color'] }}">
                                    {{ $segment['name'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $customer->interaction_count }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $customer->project_count }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                Rp {{ number_format($customer->total_sales, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ optional($customer->lastInteraction)->interaction_date?->diffForHumans() ?? 'Never' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data customer yang sesuai dengan filter
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading.flex class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Memproses data...</p>
        </div>
    </div>
</div>  