<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Photo Albums</h1>
        <a href="{{ route('albums.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create New Album
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if($albums->isEmpty())
        <p class="text-gray-500">No albums created yet.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($albums as $album)
                <div class="bg-white shadow rounded overflow-hidden group relative">
                     {{-- Find the first photo for a thumbnail --}}
                     @php $thumbnail = $album->photos()->first(); @endphp

                     <a href="{{ route('albums.show', $album) }}" class="block">
                         @if($thumbnail && Storage::disk('public')->exists($thumbnail->file_path))
                             <img src="{{ asset('storage/' . $thumbnail->file_path) }}" alt="{{ $album->name }} thumbnail" class="w-full h-40 object-cover group-hover:opacity-80 transition-opacity">
                         @else
                             {{-- Placeholder if no photos or first image missing --}}
                             <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400 group-hover:opacity-80 transition-opacity">
                                 <span>No Photo</span>
                             </div>
                         @endif
                     </a>

                    <div class="p-4">
                        <h3 class="font-semibold text-lg truncate mb-1">
                            <a href="{{ route('albums.show', $album) }}" class="hover:text-blue-700">{{ $album->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-600 truncate mb-2">{{ $album->description ?? 'No description' }}</p>
                        <span class="text-xs text-gray-500">{{ $album->photos_count }} {{ Str::plural('photo', $album->photos_count) }}</span>
                    </div>

                     {{-- Delete Button --}}
                    <button
                        wire:click="deleteAlbum({{ $album->id }})"
                        wire:confirm="Are you sure you want to delete the album '{{ $album->name }}' AND ALL its photos?"
                        wire:loading.attr="disabled"
                        wire:target="deleteAlbum({{ $album->id }})"
                        class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10"
                        title="Delete album">
                        <span wire:loading wire:target="deleteAlbum({{ $album->id }})">...</span>
                        <span wire:loading.remove wire:target="deleteAlbum({{ $album->id }})">X</span>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>