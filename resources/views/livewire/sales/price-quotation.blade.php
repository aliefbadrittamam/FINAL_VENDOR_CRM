
<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Price Quotations</h2>
            <p class="mt-1 text-sm text-gray-600">Manage price quotations for all projects</p>
        </div>
        <button wire:click="create"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Quotation
        </button>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" wire:model.live="search"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Search project or vendor...">
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
        <div>
            <button wire:click="resetFilters"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Quotations Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($quotations as $quotation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $quotation->project->project_header }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Project Value: Rp {{ number_format($quotation->project->project_value, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $quotation->vendor->vendor_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($quotation->amount, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $quotation->created_at->format('d M Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $quotation->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <button wire:click="acceptQuotation({{ $quotation->price_quotation_id }})"
                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                onclick="return confirm('Are you sure? This will update the project value.')">
                                ACC
                            </button>
                            <button wire:click="edit({{ $quotation->price_quotation_id }})"
                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                Edit
                            </button>
                            <button wire:click="delete({{ $quotation->price_quotation_id }})"
                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200"
                                onclick="return confirm('Are you sure you want to delete this quotation?')">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No quotations found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $quotations->links() }}
    </div>

    <!-- Form Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form wire:submit="save">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                                    {{ $editMode ? 'Edit Price Quotation' : 'Create New Price Quotation' }}
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Project Field -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Project</label>
                                        <select wire:model="project_id" class="mt-1 block w-full rounded-md border-gray-300">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->project_id }}">{{ $project->project_header }}</option>
                                            @endforeach
                                        </select>
                                        @error('project_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Vendor Field -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Vendor</label>
                                        <select wire:model="vendor_id" class="mt-1 block w-full rounded-md border-gray-300">
                                            <option value="">Select Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Amount Field -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" wire:model="amount"
                                                class="mt-1 block w-full pl-12 rounded-md border-gray-300"
                                                placeholder="0">
                                        </div>
                                        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">
                                    {{ $editMode ? 'Update' : 'Create' }}
                                </button>
                                <button type="button" wire:click="$set('showModal', false)"
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

    <!-- Flash Messages -->
    <div x-data="{ show: false, message: '', type: 'success' }"
        x-on:quotation-saved.window="show = true; message = $event.detail; type = 'success'; setTimeout(() => show = false, 3000)"
        x-on:quotation-deleted.window="show = true; message = $event.detail; type = 'success'; setTimeout(() => show = false, 3000)"
        x-on:quotation-accepted.window="show = true; message = $event.detail; type = 'success'; setTimeout(() => show = false, 3000)"
        class="fixed bottom-4 right-4 z-50">
        <div x-show="show" 
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transform ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-2 opacity-0"
            :class="type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'"
            class="px-4 py-3 rounded-lg border shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0" x-show="type === 'success'">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm" x-text="message"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
