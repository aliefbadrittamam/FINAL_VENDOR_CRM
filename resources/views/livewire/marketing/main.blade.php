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
                    placeholder="Search campaign..." 
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
                Add New Campaign
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started at</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End at</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaign as $campaigns)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap underline text-blue-900"> <a  href="{{route('marketing.detail',  ['campaignId' => $campaigns->campaign_id])}}">{{ $campaigns->campaign_name }}</a></td>
                        <td class="px-6 py-4 whitespace-nowrap w-32 truncate ">
                            {{ \Illuminate\Support\Str::limit($campaigns->description, 50, '...') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $campaigns->start_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $campaigns->end_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="openModal({{ $campaigns->campaign_id }})" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <span class="mx-2">|</span>
                            <button class="text-green-600 hover:text-green-900">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <a href="{{ route('marketing.send', ['campaignId' => $campaigns->campaign_id]) }}">Send</a>
                            </button>
                            <span class="mx-2">|</span>
                            <button wire:click="deleteCampaign({{ $campaigns->campaign_id }})" 
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No campaign found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

   
<!-- Modal -->
<!-- resources/views/livewire/customer/main.blade.php -->
@if($showModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <livewire:marketing.campaign-form :campaign-id="$editingCampaignId" />
            </div>
        </div>
    </div>
@endif

<div>
    @if (session('error'))
        <div class="mb-4 p-4 text-red-600 bg-red-100 border border-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- Konten lainnya -->
    <div class="mt-4">
        {{ $campaign->links() }}
    </div>
</div>
</div>

<script>
    window.addEventListener('setTimeout', event => {
        setTimeout(() => {
            Livewire.dispatch(event.detail.callback, event.detail.message);
        }, event.detail.delay);
    });
</script>