<!-- Modal Background -->
<div class="fixed flex inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center" 
     play: >
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="text-lg font-semibold">Add Comment</h3>

        <!-- Form to Add Comment -->
        <form wire:submit.prevent="storeComment">
            <div class="mt-4">
                <label for="comment" class="block text-sm font-medium text-gray-700">Your Comment</label>
                <textarea wire:model="comment" id="comment" rows="4" class="mt-1 p-2 w-full border rounded-md focus:ring-indigo-500 focus:border-indigo-500" required></textarea>

                <!-- Validation Error -->
                @error('comment') 
                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" class="mr-2 px-4 py-2 bg-gray-300 rounded" wire:click="closeModal">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save Comment</button>
            </div>
        </form>
    </div>
</div>
