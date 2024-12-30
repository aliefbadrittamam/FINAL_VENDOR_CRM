<section class="bg-gray-100  bg-opacity-50 h-screen">
      <div class="mx-auto container max-w-2xl md:w-3/4 shadow-md">
        <div class="bg-white space-y-6">
          <div class="md:inline-flex space-y-4 md:space-y-0 w-full p-4 text-gray-500 items-center">
            <h2 class="md:w-1/3 max-w-sm mx-auto">Campaign</h2>
            <div class="md:w-2/3 mx-auto max-w-sm space-y-5">
              <div>
                <label class="text-sm text-gray-400">Campaign Name</label>
                <div class="w-full inline-flex border">
                <div class="p-3w-11/12 focus:outline-none focus:text-gray-600 p-2">
                    {{$campaign->campaign_name}}
                </div>
                </div>
              </div>
              <div>
                <label class="text-sm text-gray-400">Massages</label>
                <div class="w-full inline-flex border">
                <div class="p-3w-11/12 focus:outline-none focus:text-gray-600 p-2">
                    {{$campaign->description}}
                </div>
                </div>
              </div>
            </div>
          </div>

          <hr />
          <div class="md:inline-flex  space-y-4 md:space-y-0  w-full p-4 text-gray-500 items-center">
            <h2 class="md:w-1/3 mx-auto max-w-sm">Schedule</h2>
            <div class="md:w-2/3 mx-auto max-w-sm space-y-5">
              <div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="true" class="sr-only peer" wire:model.live="scheduled">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="p-3 bg-white-100">Scheduled Campaign</span>
                </label>

                <form action="{{ route('send.whatsapp')}}" method="post">
                @csrf
                @if($scheduled)
                <div >
                    <label class="block text-sm font-medium text-gray-700" for="datetime">Choose Datetime to send</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" type="datetime-local" id="datetime" name="datetime">
                </div>        
                @endif
              </div>
            </div>
          </div>

          <hr />
          <div class="bg-white space-y-6">
          <div class="md:inline-flex space-y-4 md:space-y-0 w-full p-4 text-gray-500 items-center">
            <h2 class="md:w-1/3 max-w-sm mx-auto">Receiver</h2>
            @php
                $selectedCustomers = session('selected_customers', []); // Defaultnya adalah array kosong jika tidak ada data
            @endphp
            <div class="md:w-2/3 mx-auto max-w-sm space-y-5">
            @if(!empty($selectedCustomers))
            @foreach($selectedCustomers as $customerId)
                @php
                    $customer = \App\Models\Customer::find($customerId);
                @endphp
                @if($customer)
                <div>
                    <div class="w-full inline-flex border">
                    <div class="p-3w-11/12 focus:outline-none focus:text-gray-600 p-2">
                        {{ $customer->customer_name }} ({{ $customer->customer_phone }})
                    </div>
                    </div>
                </div>
                @endif
                @endforeach
            @else
                <p class="mb-3 font-ligt text-gray">No customer choosen yet.</p>
                @endif
            @if(session('selected_customers'))
                <button 
                    type="button" 
                    class="text-sm font-reguler px-2 py-2 rounded-md border border-blue-300" 
                    wire:click="openModal()">
                    Edit Customer
                </button>
            @else
                <button 
                    type="button" 
                    class="text-sm font-reguler px-2 py-2 rounded-md border border-blue-300" 
                    wire:click="openModal()">
                    Select Customer
                </button>
            @endif
            </div>
          </div>

          <hr />
          <div class="max-w-7xl py-2 mx-auto sm:px-6 lg:px-8">
        <!-- Button to Open Modal -->
        <div class="flex justify-end space-x-3">
            <button type="button" wire:click="cancel"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </button>
            <input type="hidden" value="{{ $campaign->campaign_id}}" name="idCampaign"/>
            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300" >
                Send
            </button>
        </form>    
        </div>
    </div>
    </div>
</div>
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <livewire:marketing.select-target-marketing />
                </div>
            </div>
        </div>
    @endif
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</section>

