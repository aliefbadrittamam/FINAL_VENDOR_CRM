<div>
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
                }, 3000);
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
                    <option value="qualified">Qualified</option>
                    <option value="potential">Potential</option>
                    <option value="follow up">Follow up</option>
                    <option value="new">New</option>
                </select>
            </div>
            <!-- update -->
            <div  class="relative inline-block text-left" wire:click="updateLeads">
                <button type="button" class="bg-green-500 text-white hover:bg-green-700 hover:text-white rounded px-4 py-2 inline-flex items-center">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.07,8A8,8,0,0,1,20,12"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.93,16A8,8,0,0,1,4,12"></path>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="5 3 5 8 10 8"></polyline>
                    <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="19 21 19 16 14 16"></polyline>
                </svg>
                Update
                </button>
             </div>
        </div>
    </div>
</div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Phone</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign Sent</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Delivered</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leads as $lead)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap w-32 truncate ">{{ $lead->customer->customer_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $lead->customer->customer_phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $lead->message_count}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">{{$lead->delivered}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <p class="
                                @if($lead->status == 'New')
                                    bg-gray-200 text-gray-800 rounded-full
                                @elseif($lead->status == 'follow up')
                                    bg-yellow-200 text-yellow-800 rounded-full
                                @elseif($lead->status == 'potential')
                                    bg-blue-200 text-blue-800 rounded-full
                                @elseif($lead->status == 'qualified')
                                    bg-green-200 text-green-800 rounded-full
                                @else
                                    bg-gray-100 text-gray-500 rounded-full
                                @endif
                                flex items-center justify-center py-1 px-3">
                                {{$lead->status}}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button  wire:click="openModal({{ $lead->id}})" 
                                class="text-blue-600 border border-blue-600 hover:bg-blue-100 hover:text-blue-800 rounded-md px-4 py-2 inline-flex items-center">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_iconCarrier">
                                        <title>i</title>
                                        <g id="Complete">
                                            <g id="bubble-circle">
                                                <path d="M7.6,20.4a9.5,9.5,0,1,0-4-4L2.5,21.5Z" fill="none" stroke="#0000FF" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                {{$lead->comments ? \Illuminate\Support\Str::limit($lead->comments, 15, '...') : 'Add Comment'}}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No leads found</td>
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
    @if($showModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <livewire:marketing.add-comment-modal :leadId="$editingLeadsId" />
            </div>
        </div>
    </div>
    @endif
</div>
    <!-- Konten lainnya -->
    <div class="mt-4">
        {{ $leads->links() }}
    </div>
</div>
</div>

</div>

</div>
