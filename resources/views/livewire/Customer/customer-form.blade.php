<div class="relative" 
    x-data="{ 
        showPassword: false, 
        showConfirmPassword: false,
        showSuccess: @entangle('showSuccess'),
        isLoading: @entangle('isLoading')
    }">
    <!-- Form Container -->
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ $customerId ? 'Edit Customer' : 'Add New Customer' }}
        </h2>

        <form wire:submit.prevent="confirmSave" class="mt-6">
            <div class="space-y-4">
                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="customer_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('customer_name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="customer_email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('customer_email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                @if (!$customerId)
    <!-- Password Field -->
    <div class="relative space-y-4">
        <!-- Main Password Field -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative" x-data="{ show: false }">
                <input 
                    :type="show ? 'text' : 'password'" 
                    wire:model.defer="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button 
                    type="button" 
                    @click.prevent="show = !show" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none">
                    <!-- Icon untuk Show Password -->
                    <svg 
                        x-cloak 
                        x-show="!show" 
                        class="h-5 w-5 text-gray-400" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Icon untuk Hide Password -->
                    <svg 
                        x-cloak 
                        x-show="show" 
                        class="h-5 w-5 text-gray-400" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password Field -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <div class="relative" x-data="{ showConfirm: false }">
                <input 
                    :type="showConfirm ? 'text' : 'password'" 
                    wire:model.defer="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button 
                    type="button" 
                    @click.prevent="showConfirm = !showConfirm" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none">
                    <!-- Icon untuk Show Password -->
                    <svg 
                        x-cloak 
                        x-show="!showConfirm" 
                        class="h-5 w-5 text-gray-400" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Icon untuk Hide Password -->
                    <svg 
                        x-cloak 
                        x-show="showConfirm" 
                        class="h-5 w-5 text-gray-400" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password_confirmation') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>
    </div>
@endif

                <!-- Phone Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" wire:model="customer_phone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('customer_phone') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Address Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea wire:model="customer_address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('customer_address') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="closeModal"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    {{ $customerId ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmation)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        
                        <!-- Loading Overlay -->
                        <div x-show="isLoading" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="absolute inset-0 bg-white bg-opacity-50 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        </div>

                        <!-- Success Overlay -->
                        <div x-show="showSuccess" 
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute inset-0 bg-white bg-opacity-50 flex items-center justify-center">
                            <div class="transform transition-all ease-out duration-300">
                                <div class="bg-green-100 rounded-full p-4">
                                    <svg class="h-12 w-12 text-green-600 animate-check" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div class="p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">
                                        Confirm Customer Details
                                    </h3>
                                    <div class="mt-4 space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-500">Name:</span>
                                            <span class="text-gray-900">{{ $customer_name }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-500">Email:</span>
                                            <span class="text-gray-900">{{ $customer_email }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-500">Phone:</span>
                                            <span class="text-gray-900">{{ $customer_phone }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-500">Address:</span>
                                            <span class="text-gray-900 mt-1">{{ $customer_address }}</span>
                                        </div>
                                        @if(!$customerId)
                                            <div class="mt-4 bg-yellow-50 p-3 rounded-md">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h3 class="text-sm font-medium text-yellow-800">
                                                            Important Note
                                                        </h3>
                                                        <div class="mt-2 text-sm text-yellow-700">
                                                            <p>
                                                                A new user account will be created with this email address. The customer will be able to login using these credentials.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer with Enhanced Buttons -->
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                                <button type="button" 
                                    wire:click="save"
                                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto transition-all duration-150 ease-in-out"
                                    :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                    :disabled="isLoading">
                                    <span x-show="!isLoading">Confirm</span>
                                    <span x-show="isLoading" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                                <button type="button" 
                                    wire:click="cancelConfirmation"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all duration-150 ease-in-out"
                                    :disabled="isLoading">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Animation Styles -->
    <style>
        @keyframes check {
            0% { transform: scale(0) rotate(45deg); }
            35% { transform: scale(0) rotate(45deg); }
            40% { transform: scale(1.15) rotate(45deg); }
            100% { transform: scale(1) rotate(45deg); }
        }
        .animate-check {
            animation: check 0.5s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
    </style>
</div>