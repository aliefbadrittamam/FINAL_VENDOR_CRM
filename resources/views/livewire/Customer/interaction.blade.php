<div class="p-6">
    <!-- Header dengan Customer Selection -->
    <div class="mb-6">
        <div class="flex flex-col space-y-4">
            <!-- Customer Dropdown -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Customer</label>
                <select wire:model.live="selectedCustomerId" 
                    class="w-full rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 px-4 py-2">
                    <option value="">Pilih customer...</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customer_id }}">
                            {{ $customer->customer_name }} - {{ $customer->customer_email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Header dan Tombol Tambah -->
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">
                    @if($selectedCustomerId)
                        Interaksi Customer - {{ $selectedCustomerName }}
                    @else
                        Pilih customer untuk melihat interaksi
                    @endif
                </h2>
                @if($selectedCustomerId)
                    <button wire:click="openModal" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Interaksi
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($selectedCustomerId)
        <!-- Tabel Interaksi -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($interactions as $interaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($interaction->interaction_type === 'Call') bg-green-100 text-green-800
                                    @elseif($interaction->interaction_type === 'Email') bg-blue-100 text-blue-800
                                    @elseif($interaction->interaction_type === 'Meeting') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $interaction->interaction_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($interaction->vendor)->vendor_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $interaction->interaction_date->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 line-clamp-2">{{ $interaction->notes }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($interaction->user)->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openModal({{ $interaction->interaction_id }})" 
                                    class="text-blue-600"
                                    <button wire:click="openModal({{ $interaction->interaction_id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button wire:click="deleteInteraction({{ $interaction->interaction_id }})"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus interaksi ini?')"
                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada interaksi tercatat
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
    
                <!-- Pagination -->
                @if($interactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $interactions->hasPages())
                    <div class="mt-4 px-6 py-3 border-t border-gray-200">
                        {{ $interactions->links() }}
                    </div>
                @endif
            </div>
        @endif
    
        <!-- Modal Form -->
        @if($showModal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="bg-white rounded-lg overflow-hidden shadow-xl w-full max-w-lg">
                            <form wire:submit="save">
                                <div class="p-6">
                                    <!-- Vendor Selection -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                                        <select 
                                            wire:model="vendor_id"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('vendor_id') border-red-500 @enderror">
                                            <option value="">Pilih Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->vendor_id }}">
                                                    {{ $vendor->vendor_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
    
                                    <!-- Interaction Type -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Interaksi</label>
                                        <select 
                                            wire:model="interaction_type"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('interaction_type') border-red-500 @enderror">
                                            <option value="">Pilih Tipe</option>
                                            <option value="Call">Telepon</option>
                                            <option value="Email">Email</option>
                                            <option value="Meeting">Meeting</option>
                                            <option value="Other">Lainnya</option>
                                        </select>
                                        @error('interaction_type')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
    
                                    <!-- Interaction Date -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Interaksi</label>
                                        <input 
                                            type="datetime-local" 
                                            wire:model="interaction_date"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('interaction_date') border-red-500 @enderror">
                                        @error('interaction_date')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
    
                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                        <textarea 
                                            wire:model="notes"
                                            rows="4"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                                            placeholder="Masukkan detail interaksi (minimal 10 karakter)"></textarea>
                                        @error('notes')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
    
                                <!-- Form Actions -->
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-3">
                                    <button 
                                        type="button"
                                        wire:click="closeModal"
                                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        wire:loading.attr="disabled">
                                        Batal
                                    </button>
                                    <button 
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed">
                                        <span wire:loading.remove>
                                            {{ $editingInteractionId ? 'Update' : 'Simpan' }}
                                        </span>
                                        <span wire:loading wire:target="save" class="inline-flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    
        <!-- Loading States -->
        <div wire:loading.delay wire:target="save, deleteInteraction" 
            class="fixed inset-0 bg-black bg-opacity-25 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 shadow-xl">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Memproses...</p>
            </div>
        </div>
    </div>