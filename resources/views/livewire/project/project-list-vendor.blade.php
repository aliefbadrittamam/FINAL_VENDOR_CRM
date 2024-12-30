<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @if (isset($noVendorAccess))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        You don't have vendor access. Please contact administrator.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Metrics Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Project Value -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Project Value</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($totalValue, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Projects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeProjects }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Completed Projects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $completedProjects }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-full">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Projects -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-l-4 border-yellow-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Projects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $projects->total() }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-full">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" wire:model.live="search"
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Search by project name...">
                        <!-- Loading indicator for search -->
                        <div wire:loading.delay wire:target="search" class="absolute right-3 top-9">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-500"></div>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                        <select wire:model.live="customerFilter"
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Customers</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                        <!-- Loading indicator for customer filter -->
                        <div wire:loading.delay wire:target="customerFilter" class="absolute right-3 top-9">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-500"></div>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" wire:model.live="dateFilter"
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <!-- Loading indicator for date filter -->
                        <div wire:loading.delay wire:target="dateFilter" class="absolute right-3 top-9">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-500"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sort('project_header')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Project
                            @if ($sortField === 'project_header')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th wire:click="sort('customer_id')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Customer
                            @if ($sortField === 'customer_id')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th wire:click="sort('project_duration_start')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Timeline
                            @if ($sortField === 'project_duration_start')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th wire:click="sort('project_value')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Value
                            @if ($sortField === 'project_value')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        @php
                            $status = $this->getProjectStatus($project);
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $project->project_header }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($project->project_detail, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $project->customer->customer_name }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $project->customer->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">Start:
                                    {{ Carbon\Carbon::parse($project->project_duration_start)->format('d M Y') }}</div>
                                <div class="text-sm text-gray-900">End:
                                    {{ Carbon\Carbon::parse($project->project_duration_end)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                                    {{ $status['status'] }}
                                </span>
                                <div class="mt-1">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-{{ $status['color'] }}-600 h-2 rounded-full"
                                            style="width: {{ $status['progress'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $status['days_remaining'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($project->project_value, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button wire:click="viewDetail({{ $project->project_id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-2">
                                    View
                                </button>
                                <button wire:click="openUpdateModal({{ $project->project_id }})"
                                    class="text-green-600 hover:text-green-900">
                                    Update
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No projects found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($projects->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>

        <!-- Update Modal -->
        @if ($showUpdateModal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <form wire:submit="updateProject">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Project</h3>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Project Header</label>
                                        <input type="text" wire:model="project_header"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                        @error('project_header')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" wire:model="project_duration_start"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                        @error('project_duration_start')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" wire:model="project_duration_end"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                        @error('project_duration_end')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Project Detail</label>
                                        <textarea wire:model="project_detail" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"></textarea>
                                        @error('project_detail')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Update Notes</label>
                                        <textarea wire:model="notes" rows="2"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                            placeholder="Add notes about this update..."></textarea>
                                        @error('notes')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit"
                                        class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                                        Update Project
                                    </button>
                                    <button type="button" wire:click="closeModal"
                                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Detail Modal -->
        @if ($showDetailModal && $selectedProject)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full">
                            <!-- Modal Header -->
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Project Details
                                    </h3>
                                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Content -->
                            <div class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Project Header</h4>
                                        <p class="mt-1 text-sm text-gray-900">{{ $selectedProject->project_header }}
                                        </p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Project Value</h4>
                                        <p class="mt-1 text-sm text-gray-900">Rp
                                            {{ number_format($selectedProject->project_value, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">Project Detail</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $selectedProject->project_detail }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Customer Information</h4>
                                        <div class="mt-1 text-sm">
                                            <p class="font-medium text-gray-900">
                                                {{ $selectedProject->customer->customer_name }}</p>
                                            <p class="text-gray-500">{{ $selectedProject->customer->customer_email }}
                                            </p>
                                            <p class="text-gray-500">{{ $selectedProject->customer->customer_phone }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Project Timeline</h4>
                                        <div class="mt-1 text-sm">
                                            <p><span class="text-gray-500">Start:</span>
                                                {{ Carbon\Carbon::parse($selectedProject->project_duration_start)->format('d M Y') }}
                                            </p>
                                            <p><span class="text-gray-500">End:</span>
                                                {{ Carbon\Carbon::parse($selectedProject->project_duration_end)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Products Section -->
                                @if ($selectedProject->products->count() > 0)
                                    <div class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-500 mb-2">Products & Materials</h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                            Product</th>
                                                        <th
                                                            class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                            Price</th>
                                                        <th
                                                            class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                            Quantity</th>
                                                        <th
                                                            class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                            Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach ($selectedProject->products as $product)
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm text-gray-900">
                                                                {{ $product->product_name }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-900 text-right">
                                                                Rp
                                                                {{ number_format($product->pivot->price_at_time, 0, ',', '.') }}
                                                            </td>
                                                            <td class="px-4 py-2 text-sm text-gray-900 text-center">
                                                                {{ $product->pivot->quantity }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-900 text-right">
                                                                Rp
                                                                {{ number_format($product->pivot->subtotal, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3"
                                                            class="px-4 py-2 text-sm font-medium text-gray-900 text-right">
                                                            Total</td>
                                                        <td
                                                            class="px-4 py-2 text-sm font-medium text-gray-900 text-right">
                                                            Rp
                                                            {{ number_format($selectedProject->products->sum('pivot.subtotal'), 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if ($selectedProject)
                                    <!-- Purchase Information -->
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-500">Purchase Information</h4>
                                        @php
                                            $purchase = $this->checkPurchaseStatus($selectedProject);
                                        @endphp
                                        @if ($purchase)
                                            <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                                <div class="flex justify-between items-center mb-4">
                                                    <div>
                                                        <span class="text-sm font-medium">Purchase
                                                            #{{ $purchase->purchase_id }}</span>
                                                        <span
                                                            class="ml-2 px-2 py-1 text-xs rounded-full
                            @if ($purchase->status === 'Completed') bg-green-100 text-green-800
                            @elseif($purchase->status === 'Pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                                                            {{ $purchase->status }}
                                                        </span>
                                                    </div>
                                                    <button
                                                        wire:click="viewPurchase({{ $selectedProject->project_id }})"
                                                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                                        Manage Purchase
                                                    </button>
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    <p>Purchase Date: {{ $purchase->purchase_date ? Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') : '-' }}
                                                    </p>
                                                    <p>Total Amount: Rp
                                                        {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                                                <p class="text-sm text-gray-500">No purchase record found for this
                                                    project.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                                <button wire:click="closeModal"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tambahkan modal purchase -->
        @if ($showPurchaseModal && $selectedPurchase)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                            <div class="px-6 py-4 border-b">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium">Manage Purchase
                                        #{{ $selectedPurchase->purchase_id }}</h3>
                                    <button wire:click="$set('showPurchaseModal', false)"
                                        class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="px-6 py-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Purchase Status</label>
                                    <select wire:model="purchaseStatus"
                                        class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>

                                <!-- Purchase Details -->
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Purchase Details</h4>
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                    Product</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">
                                                    Quantity</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">
                                                    Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($selectedPurchase->purchaseDetails as $detail)
                                                <tr>
                                                    <td class="px-4 py-2">{{ $detail->product->product_name }}</td>
                                                    <td class="px-4 py-2 text-right">{{ $detail->quantity }}</td>
                                                    <td class="px-4 py-2 text-right">
                                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="px-4 py-2 text-right font-medium">Total:
                                                </td>
                                                <td class="px-4 py-2 text-right font-medium">
                                                    Rp
                                                    {{ number_format($selectedPurchase->total_amount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                                <button wire:click="updatePurchaseStatus"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Update Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Flash Messages -->
        <div x-data="{ show: false, message: '', type: 'success' }"
            x-on:project-updated.window="show = true; message = $event.detail; type = 'success'; setTimeout(() => show = false, 3000)"
            x-on:error.window="show = true; message = $event.detail; type = 'error'; setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 z-50">
            <div x-show="show" x-transition:enter="transform ease-out duration-300"
                x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transform ease-in duration-200"
                x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-2 opacity-0"
                :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
                class="text-white px-4 py-3 rounded-lg shadow-lg">
                <p x-text="message"></p>
            </div>
        </div>

        <!-- Global Loading State -->
        <div wire:loading.delay wire:target="viewDetail, openUpdateModal, updateProject"
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 transition-opacity">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-4 rounded-lg shadow-xl">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
                    <p class="mt-4 text-center text-gray-700">Loading...</p>
                </div>
            </div>
        </div>

    @endif
</div>
