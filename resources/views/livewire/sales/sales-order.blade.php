
<div>
    <!-- Header Section -->
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-medium text-gray-900">Sales Orders</h2>
                <p class="mt-1 text-sm text-gray-600">Manage and track all sales orders</p>
            </div>
            <button wire:click="openModal"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Order
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" wire:model.live="search"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search customer...">
            </div>
            <div>
                <select wire:model.live="statusFilter"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Processing">Processing</option>
                    <option value="Completed">Completed</option>
                    <option value="Converted">Converted to Project</option>
                </select>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ str_pad($sale->sale_id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->customer->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $sale->customer->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($sale->status === 'Completed') bg-green-100 text-green-800
                                    @elseif($sale->status === 'Processing') bg-blue-100 text-blue-800
                                    @elseif($sale->status === 'Converted') bg-purple-100 text-purple-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $sale->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($sale->fixed_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @if($sale->status !== 'Converted')
                                <button wire:click="openConvertModal({{ $sale->sale_id }})"
                                    class="text-purple-600 hover:text-purple-900">
                                    Convert to Project
                                </button>
                            @else
                                <span class="text-green-600">Converted</span>
                            @endif
                                <button wire:click="edit({{ $sale->sale_id }})"
                                    class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $sale->sale_id }})"
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this order?')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $sales->links() }}
        </div>

        <!-- Sales Order Form Modal -->
        @if($showModal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <form wire:submit="save">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="space-y-4">
                                        <!-- Customer Field -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Customer</label>
                                            <select wire:model="customer_id" class="mt-1 block w-full rounded-md border-gray-300">
                                                <option value="">Select Customer</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Sale Date Field -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Sale Date</label>
                                            <input type="date" wire:model="sale_date" class="mt-1 block w-full rounded-md border-gray-300">
                                            @error('sale_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Products Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Products</label>
                                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                                @foreach($products as $product)
                                                    <div class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded">
                                                        <input type="checkbox" 
                                                               wire:model.live="selectedProducts"
                                                               value="{{ $product->product_id }}"
                                                               class="rounded border-gray-300">
                                                        <label class="flex-1">
                                                            <span class="font-medium">{{ $product->product_name }}</span>
                                                            <span class="text-gray-500"> - Rp {{ number_format($product->product_price, 0, ',', '.') }}</span>
                                                        </label>
                                                        @if(in_array($product->product_id, $selectedProducts))
                                                            <input type="number" 
                                                                   wire:model.live="quantities.{{ $product->product_id }}"
                                                                   class="w-20 rounded-md border-gray-300"
                                                                   min="1"
                                                                   placeholder="Qty">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('selectedProducts') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Total Amount -->
                                        <div class="pt-4 border-t border-gray-200">
                                            <div class="text-lg font-bold text-gray-900">
                                                Total: Rp {{ number_format($total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit"
                                        class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">
                                        {{ $editMode ? 'Update Order' : 'Create Order' }}
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

        <!-- Convert to Project Modal -->
        
<!-- Modal Convert to Project -->
@if($showConvertModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
                    <form wire:submit="convertToProject">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                                Convert Sales Order to Project
                            </h3>

                            <!-- Project Header Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Project Header</label>
                                <input type="text" 
                                    wire:model="project_header" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    placeholder="Enter project header">
                                @error('project_header') 
                                    <span class="text-sm text-red-600">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ml-3">
                                Convert
                            </button>
                            <button type="button"
                                wire:click="closeConvertModal"
                                class="mt-3 inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

        <!-- Flash Messages -->
        <div x-data="{ show: false, message: '' }"
            x-on:order-saved.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
            x-on:order-deleted.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
            x-on:project-created.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 z-50">
            <div x-show="show" x-transition:enter="transform ease-out duration-300"
                x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="translate-y-2 opacity-0"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span x-text="message"></span>
            </div>
        </div>

        <!-- Error Messages -->
        @if (session()->has('error'))
            <div class="fixed bottom-4 right-4 z-50">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
```