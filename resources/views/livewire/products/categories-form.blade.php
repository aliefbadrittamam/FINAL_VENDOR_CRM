<div class="relative" >
    <!-- Form Container -->
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ $categoryId ? 'Edit Category' : 'Add New Category' }}
        </h2>

        <form wire:submit.prevent="confirmSave" class="mt-6">
            <div class="space-y-4">
                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" wire:model="category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('category') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
    </div>

    <div class="mt-6 flex justify-end space-x-3">
        <button type="button" wire:click="closeModal"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Cancel
        </button>
        <button type="submit"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            {{ $categoryId ? 'Update' : 'Save' }}
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
                                    Confirm Category Details
                                </h3>
                                <div class="mt-4 space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-500">Name:</span>
                                        <span class="text-gray-900">{{ $category }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-500">Description:</span>
                                        <span class="text-gray-900 mt-1">{{ $description }}</span>
                                    </div>
                                    @if(!$categoryId)
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
                                                            Category is added to the list.
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