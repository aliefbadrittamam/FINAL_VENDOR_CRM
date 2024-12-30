<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="flex-1 mb-4">
            <input type="text" 
                wire:model.live="search" 
                placeholder="Search customer..." 
                class="w-full px-4 py-2 rounded-lg border border-gray-300">
        </div>
        <form method="post" action="{{ route('saveSelectedCustomers') }}">
            @csrf
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" value="" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <input 
                                type="checkbox" 
                                name="selected_customers[]" 
                                class="select-item h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                value="{{ $customer->customer_id }}" 
                                @if(session('selected_customers') && in_array($customer->customer_id, session('selected_customers'))) 
                                    checked 
                                @endif
                            >
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->customer_phone }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No customers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $customers->links() }}
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="closeModal" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const isChecked = e.target.checked;
            document.querySelectorAll('.select-item').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        document.querySelectorAll('.select-item').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (document.querySelectorAll('.select-item:checked').length !== document.querySelectorAll('.select-item').length) {
                    document.getElementById('select-all').checked = false; 
                } else {
                    document.getElementById('select-all').checked = true; 
                }
            });
        });
    </script>
</div>
