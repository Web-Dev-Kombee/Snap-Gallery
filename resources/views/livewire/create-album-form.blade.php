<div class="p-8 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Create New Album</h1>

    <form wire:submit.prevent="saveAlbum" class="space-y-4 bg-white p-6 rounded shadow">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Album Name</label>
            <input type="text" id="name" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
            <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-between items-center pt-4">
             <a href="{{ route('albums.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading wire:target="saveAlbum">Saving...</span>
                <span wire:loading.remove wire:target="saveAlbum">Create Album</span>
            </button>
        </div>
    </form>
</div>