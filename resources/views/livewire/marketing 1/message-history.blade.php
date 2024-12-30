<div>
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
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <!-- Search Input -->
        <div class="flex-1">
            <input type="text" 
                wire:model.live="search" 
                placeholder="Search ..." 
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <!-- Filter Section -->
        <div class="flex gap-4 items-center">
            <!-- Date From -->
            <div>
                <input type="date" 
                    wire:model.live="filters.date_from" 
                    class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <!-- Date To -->
            <div>
                <input type="date" 
                    wire:model.live="filters.date_to" 
                    class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <!-- Dropdown -->
            <div>
                <select 
                    id="status" 
                    name="status" 
                    class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    wire:model.live="status">
                    <option value="all" selected>All</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="sent">Sent</option>
                </select>
            </div>
            <div class="relative inline-block text-left">
            <button type="button" class="bg-red-600 text-white hover:bg-red-700 hover:text-white rounded px-4 py-2 inline-flex items-center" id="dropdownButton">
                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 01-1-1V5a1 1 0 011-1h8a1 1 0 011 1v1a1 1 0 01-1 1m-7 0h6" />
                </svg>
                Select
                <svg class="ml-2 w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden" id="dropdownMenu">
                <div class="py-1">
                <a href="#" wire:click="deleteAllCampaign('all')" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">All</a>
                <a href="#" wire:click="deleteAllCampaign('this_month')" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">This Month</a>
                    <div class="px-4 py-2 text-sm">
                        <label for="startDate" class="block text-gray-700">Start Date</label>
                        <input type="date" wire:model="startDate" id="startDate" class="w-full border px-2 py-1 mb-2 rounded-md">
                        <label for="endDate" class="block text-gray-700">End Date</label>
                        <input type="date" wire:model="endDate" id="endDate" class="w-full border px-2 py-1 rounded-md">
                        <button wire:click="deleteAllCampaign('choose_range')" class="mt-2 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                            Delete in Range
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Send ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Send Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap underline text-blue-900">
                                {{ $campaign->campaign_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap w-32 truncate ">{{ $campaign->send_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap w-32 truncate ">{{ $campaign->customer_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $campaign->customer_phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $campaign->scheduled_date ? $campaign->scheduled_date: '-'}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{$campaign->send_date}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{$campaign->status}}</td>
                        <td>
                            <button wire:click="deleteCampaign({{ $campaign->id }})" 
                            class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 01-1-1V5a1 1 0 011-1h8a1 1 0 011 1v1a1 1 0 01-1 1m-7 0h6" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No campaigns found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
    @if (session('error'))
        <div class="mb-4 p-4 text-red-600 bg-red-100 border border-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- Konten lainnya -->
    <div class="mt-4">
        {{ $campaigns->links() }}
    </div>
</div>
</div>
@script
<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });
</script>
@endscript
</div>
