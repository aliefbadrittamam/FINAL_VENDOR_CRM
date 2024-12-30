<div class="p-6 space-y-8">
    <!-- Search and Filter Section -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model.live="search"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search projects...">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="statusFilter"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="backlog">Not Started</option>
                    <option value="in_progress">In Progress</option>
                    <option value="review">In Review</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" wire:model.live="dateFilter"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Sort Options -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select wire:model.live="sortField"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="project_duration_start">Start Date</option>
                    <option value="project_duration_end">End Date</option>
                    <option value="project_value">Project Value</option>
                    <option value="created_at">Creation Date</option>
                </select>
            </div>
        </div>
    </div>

    @if (
        $projectGroups['in_progress']->isEmpty() &&
            $projectGroups['completed']->isEmpty() &&
            $projectGroups['cancelled']->isEmpty() &&
            $projectGroups['backlog']->isEmpty() &&
            $projectGroups['review']->isEmpty())
        <div class="text-center py-8">
            <div class="text-gray-400 text-lg">No projects found matching your search criteria</div>
        </div>
    @else
        <!-- In Progress Projects -->
        @if (!$projectGroups['in_progress']->isEmpty())
            <div>
                <h2 class="text-xl font-bold mb-4 flex items-center text-blue-600">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    In Progress Projects
                </h2>
                <div class="overflow-x-auto pb-6">
                    <div class="flex space-x-6" style="min-width: max-content;">
                        @foreach ($projectGroups['in_progress'] as $project)
                            <div class="w-[400px] flex-shrink-0">
                                <div
                                    class="h-full bg-blue-50 border border-blue-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-blue-800 mb-2">{{ $project->project_header }}
                                        </h3>
                                        <div class="text-sm text-gray-600 mb-4">
                                            @if ($project->vendor)
                                                {{ $project->vendor->vendor_name }}
                                            @else
                                                <span class="text-yellow-600">No Vendor Assigned</span>
                                            @endif
                                        </div>


                                        <!-- Timeline Progress -->
                                        <div class="mb-4 p-4 bg-white bg-opacity-60 rounded-lg">
                                            <div class="text-sm mb-2">Progress</div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full"
                                                    style="width: {{ $project->progress }}%"></div>
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-600 mb-4">{{ $project->project_detail }}</div>
                                        <div class="text-lg font-bold text-blue-800 mb-4">
                                            Rp {{ number_format($project->project_value, 0, ',', '.') }}
                                        </div>

                                        <!-- Di dalam card project, sebelum tombol View Details -->
                                        <div class="mb-4">
                                            @php
                                                $purchase = $this->checkPurchaseStatus($project);
                                            @endphp
                                            @if($purchase)
                                                <div class="text-sm mb-2">
                                                    <span class="font-medium">Purchase Status:</span>
                                                    <span class="ml-2 px-2 py-1 rounded-full text-xs
                                                        @if($purchase->status === 'Completed') bg-green-100 text-green-800
                                                        @elseif($purchase->status === 'Pending') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ $purchase->status }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Last updated: {{ $purchase->updated_at->format('d M Y H:i') }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">
                                                    @if($project->vendor)
                                                        No purchase record
                                                    @else
                                                        Please assign a vendor first
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex space-x-2">
                                            <button wire:click="viewDetail({{ $project->project_id }})"
                                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                View Details
                                            </button>
                                            @if ($project->vendor && !$purchase)
                                                <button wire:click="createPurchase({{ $project->project_id }})"
                                                    wire:confirm="Are you sure you want to create a purchase for this project?"
                                                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                                    Create Purchase
                                                </button>
                                            @elseif(!$project->vendor)
                                                <button disabled
                                                    class="flex-1 px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                                    title="Cannot create purchase without vendor">
                                                    Create Purchase
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Completed Projects -->
        @if (!$projectGroups['completed']->isEmpty())
            <div>
                <h2 class="text-xl font-bold mb-4 flex items-center text-green-600">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Completed Projects
                </h2>
                <div class="overflow-x-auto pb-6">
                    <div class="flex space-x-6" style="min-width: max-content;">
                        @foreach ($projectGroups['completed'] as $project)
                            <div class="w-[400px] flex-shrink-0">
                                <div
                                    class="h-full bg-green-50 border border-green-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-green-800 mb-2">{{ $project->project_header }}
                                        </h3>
                                        <div class="text-sm text-gray-600 mb-4">{{ $project->vendor->vendor_name }}
                                        </div>

                                        <div class="mb-4 bg-white bg-opacity-60 rounded-lg p-4">
                                            <div class="text-sm text-gray-600">Completed on</div>
                                            <div class="font-medium">
                                                {{ Carbon\Carbon::parse($project->project_duration_end)->format('d M Y') }}
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-600 mb-4">{{ $project->project_detail }}</div>
                                        <div class="text-lg font-bold text-green-800 mb-4">
                                            Rp {{ number_format($project->project_value, 0, ',', '.') }}
                                        </div>

                                        <button wire:click="viewDetail({{ $project->project_id }})"
                                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <!-- Cancelled Projects -->
        @if (!$projectGroups['cancelled']->isEmpty())
            <div>
                <h2 class="text-xl font-bold mb-4 flex items-center text-red-600">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelled Projects
                </h2>
                <div class="overflow-x-auto pb-6">
                    <div class="flex space-x-6" style="min-width: max-content;">
                        @foreach ($projectGroups['cancelled'] as $project)
                            <div class="w-[400px] flex-shrink-0">
                                <div
                                    class="h-full bg-red-50 border border-red-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-red-800 mb-2">{{ $project->project_header }}
                                        </h3>
                                        <div class="text-sm text-gray-600 mb-4">{{ $project->vendor->vendor_name }}
                                        </div>

                                        <div class="mb-4 bg-white bg-opacity-60 rounded-lg p-4">
                                            <div class="text-sm text-gray-600">Cancelled on</div>
                                            <div class="font-medium">
                                                {{ Carbon\Carbon::parse($project->updated_at)->format('d M Y') }}
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-600 mb-4">{{ $project->project_detail }}</div>
                                        <div class="text-lg font-bold text-red-800 mb-4">
                                            Rp {{ number_format($project->project_value, 0, ',', '.') }}
                                        </div>

                                        <button wire:click="viewDetail({{ $project->project_id }})"
                                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Backlog/Not Started Projects -->
        @if (!$projectGroups['backlog']->isEmpty())
            <div>
                <h2 class="text-xl font-bold mb-4 flex items-center text-yellow-600">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Not Started Projects
                </h2>
                <div class="overflow-x-auto pb-6">
                    <div class="flex space-x-6" style="min-width: max-content;">
                        @foreach ($projectGroups['backlog'] as $project)
                            <div class="w-[400px] flex-shrink-0">
                                <div
                                    class="h-full bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-yellow-800 mb-2">
                                            {{ $project->project_header }}</h3>
                                        <div class="text-sm text-gray-600 mb-4">{{ $project->vendor->vendor_name }}
                                        </div>

                                        <div class="mb-4 bg-white bg-opacity-60 rounded-lg p-4">
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <div class="text-sm text-gray-600">Start Date</div>
                                                    <div class="font-medium">
                                                        {{ Carbon\Carbon::parse($project->project_duration_start)->format('d M Y') }}
                                                    </div>
                                                    <div class="text-sm text-yellow-600 mt-1">
                                                        Starts in
                                                        {{ Carbon\Carbon::parse($project->project_duration_start)->diffForHumans() }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm text-gray-600">End Date</div>
                                                    <div class="font-medium">
                                                        {{ Carbon\Carbon::parse($project->project_duration_end)->format('d M Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-600 mb-4">{{ $project->project_detail }}</div>
                                        <div class="text-lg font-bold text-yellow-800 mb-4">
                                            Rp {{ number_format($project->project_value, 0, ',', '.') }}
                                        </div>

                                        <button wire:click="viewDetail({{ $project->project_id }})"
                                            class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Detail Modal -->
    @if ($showDetailModal && $selectedProject)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="p-6 border-b">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $selectedProject->project_header }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Project ID: #{{ $selectedProject->project_id }}</p>
                        </div>
                        <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Project Status & Value -->
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span
                                class="px-3 py-1 text-sm rounded-full
                @if ($selectedProject->status === 'completed') bg-green-100 text-green-800
                @elseif($selectedProject->status === 'in_progress') bg-blue-100 text-blue-800
                @elseif($selectedProject->status === 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($selectedProject->status) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Project Value</div>
                            <div class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($selectedProject->project_value, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Information -->
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-gray-900 mb-4">Project Timeline</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Start Date</div>
                                <div class="font-medium">
                                    {{ Carbon\Carbon::parse($selectedProject->project_duration_start)->format('d M Y') }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">End Date</div>
                                <div class="font-medium">
                                    {{ Carbon\Carbon::parse($selectedProject->project_duration_end)->format('d M Y') }}
                                </div>
                            </div>
                            @if ($selectedProject->status === 'in_progress')
                                <div class="col-span-2">
                                    <div class="text-sm text-gray-500 mb-2">Progress</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                            style="width: {{ $selectedProject->progress }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Stakeholders Information -->
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Vendor Information -->
                        <div class="bg-white p-4 rounded-lg border">
                            <h4 class="font-medium text-gray-900 mb-4">Vendor Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="text-sm text-gray-500">Vendor Name</div>
                                    <div class="font-medium">{{ $selectedProject->vendor->vendor_name ?? "Tidak Ada Vendor" }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Email</div>
                                    <div class="font-medium">{{ $selectedProject->vendor->vendor_email ?? "Tidak Ada Vendor" }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Phone</div>
                                    <div class="font-medium">{{ $selectedProject->vendor->vendor_phone ?? "Tidak Ada Vendor" }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Address</div>
                                    <div class="font-medium">{{ $selectedProject->vendor->vendor_address?? "Tidak Ada Vendor" }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="bg-white p-4 rounded-lg border">
                            <h4 class="font-medium text-gray-900 mb-4">Customer Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="text-sm text-gray-500">Customer Name</div>
                                    <div class="font-medium">{{ $selectedProject->customer->customer_name }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Email</div>
                                    <div class="font-medium">{{ $selectedProject->customer->customer_email }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Phone</div>
                                    <div class="font-medium">{{ $selectedProject->customer->customer_phone }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Address</div>
                                    <div class="font-medium">{{ $selectedProject->customer->customer_address }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Details -->
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-gray-900 mb-4">Project Details</h4>
                        <p class="text-gray-700">{{ $selectedProject->project_detail }}</p>
                    </div>

                    <!-- Products Information -->
                    @if ($selectedProject->products->count() > 0)
                        <div class="bg-white p-4 rounded-lg border">
                            <h4 class="font-medium text-gray-900 mb-4">Products</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Product
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Category
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Price
                                            </th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                Quantity
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($selectedProject->products as $product)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $product->product_name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $product->product_category }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                                    Rp {{ number_format($product->pivot->price_at_time, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                    {{ $product->pivot->quantity }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 font-medium">
                                                    Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                                Total
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                                Rp
                                                {{ number_format($selectedProject->products->sum('pivot.subtotal'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif
                    <!-- Project Timeline -->
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-gray-900 mb-4">Project Timeline</h4>
                        <div class="space-y-4">
                            <!-- Start Date -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-4 w-4 rounded-full bg-green-500"></div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Project Started</div>
                                    <div class="text-sm text-gray-500">
                                        {{ Carbon\Carbon::parse($selectedProject->project_duration_start)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Line -->
                            <div class="ml-2 border-l-2 border-gray-200 h-8"></div>

                            <!-- Current Status -->
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-4 w-4 rounded-full 
                @if ($selectedProject->status === 'completed') bg-blue-500
                @elseif($selectedProject->status === 'in_progress') bg-yellow-500
                @else bg-gray-500 @endif">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Current Status</div>
                                    <div class="text-sm text-gray-500">
                                        {{ ucfirst($selectedProject->status) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Line -->
                            <div class="ml-2 border-l-2 border-gray-200 h-8"></div>

                            <!-- End Date -->
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-4 w-4 rounded-full 
                @if ($selectedProject->status === 'completed') bg-green-500 @else bg-gray-300 @endif">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Expected Completion</div>
                                    <div class="text-sm text-gray-500">
                                        {{ Carbon\Carbon::parse($selectedProject->project_duration_end)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Last updated: {{ $selectedProject->updated_at->format('d M Y H:i') }}
                        </div>
                        <div class="flex space-x-3">
                            <button wire:click="closeDetail"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Close
                            </button>
                            @if ($selectedProject->status === 'in_progress')
                                <button wire:click="viewProgress({{ $selectedProject->project_id }})"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    View Progress
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- <!-- Loading Indicator -->
<div wire:loading class="fixed inset-0 bg-black bg-opacity-25 z-50 flex items-center justify-center">
<div class="bg-white p-6 rounded-lg shadow-xl">
<div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
<p class="mt-4 text-gray-600">Loading...</p>
</div>
</div> --}}

    <!-- Success/Error Messages -->
    <div x-data="{ show: false, message: '' }"
        x-on:flash-message.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
        class="fixed bottom-4 right-4 z-50">
        <div x-show="show" x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-2 opacity-0"
            class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>

    <!-- Custom Scrollbar Styling -->
    <style>
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.8);
        }
    </style>
</div>
