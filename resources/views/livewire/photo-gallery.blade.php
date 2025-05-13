<div class="p-8 bg-gray-50">
    {{-- Album Header with Navigation --}}
    <div class="mb-8 pb-4 border-b border-gray-200">
        <a href="{{ route('albums.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Albums
        </a>
        <h1 class="text-3xl font-bold mt-3 text-gray-800">{{ $album->name }}</h1>
        @if($album->description)
            <p class="text-gray-600 mt-2 max-w-3xl">{{ $album->description }}</p>
        @endif
    </div>

    {{-- Include SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 shadow-sm transition-opacity animate-fadeIn">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    {{-- Photo Upload Form --}}
    <div class="mb-12 bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Upload New Photos</h2>
        </div>
        
        <form wire:submit.prevent="save" class="p-6 space-y-6" enctype="multipart/form-data">
            <div
                x-data="{ uploading: false, progress: 0 }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false; progress = 0"
                x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
            >
                {{-- File Input --}}
                <div class="mb-4">
                    <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">Select Photos</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-7">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                    Select photos to upload
                                </p>
                            </div>
                            <input type="file" id="photos" multiple wire:model="photos" class="opacity-0" />
                        </label>
                    </div>
                    @error('photos.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Previews and Metadata Inputs --}}
                @if ($photos)
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-medium text-gray-700 mb-3">Photo Details</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($photos as $index => $photo)
                                <div class="border rounded-lg bg-white shadow-sm overflow-hidden">
                                    @if ($photo)
                                        <div class="h-32 bg-gray-100 overflow-hidden">
                                            <img class="w-full h-full object-cover" src="{{ $photo->temporaryUrl() }}" alt="Preview">
                                        </div>
                                    @endif
                                    <div class="p-3">
                                        <input type="text" wire:model="titles.{{ $index }}" placeholder="Title" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mb-2">
                                        @error('titles.' . $index) <span class="text-red-500 text-xs block -mt-1 mb-1">{{ $message }}</span> @enderror
                                        
                                        <textarea wire:model="descriptions.{{ $index }}" placeholder="Description" rows="2" class="w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                        @error('descriptions.' . $index) <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Progress Bar --}}
                <div x-show="uploading" class="mt-6" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Progress</label>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-out"
                             x-bind:style="'width: ' + progress + '%'"></div>
                    </div>
                    <div class="text-xs text-center text-gray-600 mt-2">
                        <span x-text="progress + '%'"></span> Uploading...
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition disabled:opacity-50" wire:loading.attr="disabled" wire:target="save, photos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                    <span wire:loading wire:target="save, photos">Uploading...</span>
                    <span wire:loading.remove wire:target="save, photos">Upload Photos</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Gallery Section --}}
    <div x-data="{ open: false, image: '' }" x-cloak>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gallery</h2>
            <span class="text-sm text-gray-500 italic">Drag photos to reorder</span>
        </div>

        {{-- Alpine Component for SortableJS --}}
        <div
            x-data="{
                initSortable() {
                    let sortableList = this.$refs.sortableList;
                    if (sortableList.sortableInstance) return;
                    
                    sortableList.sortableInstance = new Sortable(sortableList, {
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        handle: '.drag-handle',
                        onEnd: (evt) => {
                            let items = Array.from(evt.target.children);
                            let orderedIds = items
                                .filter(item => item.hasAttribute('data-id'))
                                .map(item => parseInt(item.getAttribute('data-id'), 10))
                                .filter(id => !isNaN(id));
                                
                            if (orderedIds.length > 0) {
                                @this.call('updatePhotoOrder', orderedIds);
                            }
                        }
                    });
                }
            }"
            x-init="initSortable()"
           
        >
            {{-- The Grid Container for Sortable Items --}}
            <div x-ref="sortableList" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @forelse ($allPhotos as $photo)
                    <div wire:key="photo-item-{{ $photo->id }}" data-id="{{ $photo->id }}" class="relative group bg-white rounded-lg shadow-md overflow-hidden transform transition hover:shadow-lg hover:-translate-y-1">
                        {{-- Drag Handle --}}
                        <div class="drag-handle absolute top-2 left-2 bg-gray-800 bg-opacity-70 text-white p-1 rounded-full cursor-move opacity-0 group-hover:opacity-100 transition-opacity z-10" title="Drag to reorder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                            </svg>
                        </div>

                        
                        {{-- Delete Button --}}
                        <button
    wire:click="deletePhoto({{ $photo->id }})"
    wire:confirm="Are you sure you want to delete this photo?"
    wire:loading.attr="disabled"
    wire:target="deletePhoto({{ $photo->id }})"
    class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-1 rounded opacity-50 group-hover:opacity-100 transition-opacity z-10"
    title="Delete photo">
    <span wire:loading wire:target="deletePhoto({{ $photo->id }})">...</span>
    <span wire:loading.remove wire:target="deletePhoto({{ $photo->id }})">X</span>
</button>

                        {{-- View Fullscreen Button --}}
                        <button
                            @click="open = true; image = '{{ asset('storage/' . $photo->file_path) }}'"
                            class="absolute bottom-16 right-1/2 transform translate-x-1/2 bg-blue-600 text-white text-xs px-3 py-1 rounded-full opacity-0 group-hover:opacity-90 hover:opacity-100 transition-opacity z-10 flex items-center"
                            title="View full size">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </button>

                        {{-- Image Container with Hover Overlay --}}
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            @if(Storage::disk('public')->exists($photo->file_path))
                                <img src="{{ asset('storage/' . $photo->file_path) }}"
                                    alt="{{ $photo->title ?? 'Gallery image' }}"
                                    class="w-full h-full object-cover pointer-events-none transition-all duration-500 group-hover:scale-105"
                                    loading="lazy"
                                    decoding="async"
                                    width="300"
                                    height="300">
                                <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-sm text-red-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Image missing
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Title & Description --}}
                        <div class="p-3">
                            <h3 class="font-medium text-sm truncate" title="{{ $photo->title }}">{{ $photo->title ?? 'Untitled' }}</h3>
                            <p class="text-xs text-gray-500 truncate mt-1" title="{{ $photo->description }}">{{ $photo->description ?? 'No description' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white rounded-lg border-2 border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No photos in this album yet</p>
                        <p class="text-gray-400 text-sm mt-1">Upload some photos to get started</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Fullscreen Modal --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center"
            @click.self="open = false"
        >
            <div class="relative max-w-screen-lg max-h-screen p-4">
                <img :src="image" class="max-w-full max-h-[85vh] rounded shadow-lg object-contain">
                <button @click="open = false" class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div> {{-- x-data full screen wrapper END --}}
</div>

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
        background-color: #DBEAFE;
        border: 2px dashed #3B82F6;
        border-radius: 0.5rem;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in;
    }
</style>
@endpush
