<div>
    <!-- Notification -->
    <div
        x-data="{ 
            show: @entangle('notification.show'),
            message: @entangle('notification.message')
        }"
        x-show="show"
        x-init="$watch('show', value => {
            if (value) {
                setTimeout(() => {
                    show = false;
                }, 3000)
            }
        })"
        x-transition:enter="transform ease-out duration-300"
        x-transition:enter-start="translate-x-64 opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-64 opacity-0"
        class="fixed top-4 right-4 z-50"
        style="display: none;"
    >
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span x-text="message"></span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1">
                <input type="text" 
                    wire:model.live="search" 
                    placeholder="Search vendors..." 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300">
            </div>
            <div class="flex gap-2">
                <input type="date" 
                    wire:model.live="filters.date_from" 
                    class="px-4 py-2 rounded-lg border border-gray-300">
                <input type="date" 
                    wire:model.live="filters.date_to" 
                    class="px-4 py-2 rounded-lg border border-gray-300">
            </div>
            <button wire:click="openModal" 
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Add New Vendor
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($vendors as $vendor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vendor->vendor_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vendor->vendor_email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vendor->vendor_phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vendor->vendor_address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="openModal({{ $vendor->vendor_id }})" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <span class="mx-2">|</span>
                            <button wire:click="deleteVendor({{ $vendor->vendor_id }})" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No vendors found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">
                            {{ $vendorId ? 'Edit Vendor' : 'Add New Vendor' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            <!-- Vendor Details Section -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Vendor Details</h4>
                                
                                <!-- Name -->
                                <div>
                                    <label for="vendor_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" wire:model="vendor_name" id="vendor_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('vendor_name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <!-- Email -->
                                <div>
                                    <label for="vendor_email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="vendor_email" id="vendor_email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('vendor_email')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <!-- Phone -->
                                <div>
                                    <label for="vendor_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" wire:model="vendor_phone" id="vendor_phone"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('vendor_phone')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <!-- Address -->
                                <div>
                                    <label for="vendor_address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea wire:model="vendor_address" id="vendor_address" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('vendor_address')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                @if(!$vendorId)
                                <!-- Password fields only for new vendor -->
<div class="mt-4 pt-4 border-t">
    <h4 class="text-sm font-medium text-gray-900 mb-3">Account Password</h4>
    
    <!-- Password -->
    <div class="relative">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <div class="relative">
            <input 
                type="{{ $showPassword ? 'text' : 'password' }}" 
                wire:model="password" 
                id="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10"
            >
            <button 
                type="button" 
                wire:click="$toggle('showPassword')"
                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1"
            >
                @if($showPassword)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @endif
            </button>
        </div>
        @error('password')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="relative mt-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <div class="relative">
            <input 
                type="{{ $showConfirmPassword ? 'text' : 'password' }}" 
                wire:model="password_confirmation" 
                id="password_confirmation"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10"
            >
            <button 
                type="button" 
                wire:click="$toggle('showConfirmPassword')"
                class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1"
            >
                @if($showConfirmPassword)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @endif
            </button>
        </div>
    </div>
</div>
                                @endif
                            </div>
                        </div>
                    
                        <!-- Form buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ $vendorId ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Pagination -->
<div class="mt-4">
    {{ $vendors->links() }}
</div>
</div>