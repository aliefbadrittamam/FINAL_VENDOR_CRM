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

<!-- Rest of your main component code -->
<!-- ... -->
  <!-- Header -->
  <div class="mb-6">
      <div class="flex flex-wrap gap-4 items-center">
          <div class="flex-1">
              <input type="text" 
                  wire:model.live="search" 
                  placeholder="Search categories..." 
                  class="w-full px-4 py-2 rounded-lg border border-gray-300">
          </div>
          @if(auth()->check() && auth()->user()->role === 'Admin')
          <button wire:click="openModal" 
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
              Add New Category
          </button>
          @endif
      </div>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-lg shadow-sm overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
              <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
              @forelse($categories as $category)
                  <tr>
                      <td class="px-6 py-4 whitespace-nowrap">{{ $category->category }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">{{ $category->description}}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if(auth()->check() && auth()->user()->role === 'Admin')
                          <button wire:click="openModal({{ $category->id }})" class="text-blue-600 hover:text-blue-900">
                              <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                              </svg>
                              Edit
                          </button>
                          <span class="mx-2">|</span>
                          <!-- Tombol Delete di Daftar -->
                                            @if(auth()->check() && auth()->user()->role === 'Admin')

                          <button wire:click="deleteConfirm({{ $category->id }})" 
                              class="text-red-600 hover:text-red-900">
                              <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                              </svg>
                              Delete
                          </button>
@endif
                      </td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="5" class="px-6 py-4 text-center text-gray-500">No category found</td>
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
              <livewire:products.categories-form :category-id="$editingCategoryId" />
          </div>
      </div>
  </div>
@endif
<!-- Modal Konfirmasi Hapus -->
@if ($deleteConfirmation)
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50">
      <div class="flex items-center justify-center min-h-screen p-4">
          <div class="bg-white rounded-lg shadow-lg p-6">
              <p class="text-lg">Menghapus data kategori ini akan menghapus semua data produk terkait. Anda yakin?</p>
              <div class="mt-4 flex justify-end space-x-4">
                  <!-- Tombol Cancel -->
                  <button wire:click="closeDelete" 
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                      Cancel
                  </button>
                  <!-- Tombol Delete -->
                  <button wire:click="test" 
                      class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                      Delete
                  </button>
              </div>
          </div>
      </div>
  </div>
@endif

  <!-- Pagination -->
  <div class="mt-4">
      {{ $categories->links() }}
  </div>
</div>

<script>
  window.addEventListener('setTimeout', event => {
      setTimeout(() => {
          Livewire.dispatch(event.detail.callback, event.detail.message);
      }, event.detail.delay);
  });
</script>