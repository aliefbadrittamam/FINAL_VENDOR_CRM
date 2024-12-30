<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Shipping Status</h2>
            <p class="mt-1 text-sm text-gray-600">Track and manage shipping status for orders</p>
        </div>
        <button wire:click="openModal" type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Shipping
        </button>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" wire:model.live="search" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Search customer or vendor...">
        </div>
        <div>
            <select wire:model.live="statusFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <div>
            <select wire:model.live="projectFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->project_id }}">{{ $project->project_header }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="vendorFilter" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Shipping Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($shippings as $shipping)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $shipping->Number_receipt }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $shipping->project->project_header }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $shipping->vendor->vendor_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $shipping->customer->customer_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select wire:change="updateStatus({{ $shipping->shipping_id }}, $event.target.value)"
                                class="block text-sm rounded-md border-gray-300 
                                {{ $shipping->shipping_status === 'Completed' ? 'text-green-800 bg-green-100' : 
                                   ($shipping->shipping_status === 'Cancelled' ? 'text-red-800 bg-red-100' : 
                                   'text-yellow-800 bg-yellow-100') }}">
                                <option value="Pending" {{ $shipping->shipping_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Completed" {{ $shipping->shipping_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ $shipping->shipping_status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $shipping->shipping_id }})" 
                                class="text-blue-600 hover:text-blue-900">Edit</button>
                            <button wire:click="delete({{ $shipping->shipping_id }})" 
                                wire:confirm="Are you sure you want to delete this shipping record?"
                                class="ml-3 text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No shipping records found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $shippings->links() }}
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
                        <form wire:submit="save">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                                    {{ $editMode ? 'Edit Shipping Record' : 'Create New Shipping Record' }}
                                </h3>

                                <!-- Purchase Detail Field -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Purchase Detail</label>
                                    <select wire:model="purchase_detail_id" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Select Purchase Detail</option>
                                        @foreach($purchaseDetails as $detail)
                                            <option value="{{ $detail->purchase_detail_id }}">
                                                Purchase #{{ $detail->purchase_detail_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('purchase_detail_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <!-- Project Field -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Project</label>
                                    <select wire:model="project_id" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->project_id }}">{{ $project->project_header }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <!-- Vendor Field -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Vendor</label>
                                    <select wire:model="vendor_id" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <!-- Customer Field -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Customer</label>
                                    <select wire:model="customer_id" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <!-- Receipt Number Field -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Receipt Number</label>
                                    <input type="number" wire:model="number_receipt" 
                                        class="mt-1 block w-full rounded-md border-gray-300"
                                        placeholder="Enter receipt number">
                                    @error('number_receipt') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status Field -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select wire:model="shipping_status" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                    @error('shipping_status') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editMode ? 'Update Shipping' : 'Create Shipping' }}
                                </button>
                                <button type="button" wire:click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notification -->
    <div x-data="{ show: false, message: '' }"
         x-on:shipping-saved.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         x-on:shipping-deleted.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         class="fixed bottom-0 right-0 m-6">
        <div x-show="show" 
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             class="max-w-sm w-full bg-green-100 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p x-text="message" class="text-sm font-medium text-green-900"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="rounded-md inline-flex text-green-500 hover:text-green-600 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading States -->
    <div wire:loading wire:target="save, delete, updateStatus" class="fixed inset-0 bg-black bg-opacity-25 z-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-2 text-gray-600">Processing...</p>
        </div>
    </div>

    <!-- Error Message -->
    @if (session()->has('error'))
        <div class="fixed bottom-0 right-0 m-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif
</div>