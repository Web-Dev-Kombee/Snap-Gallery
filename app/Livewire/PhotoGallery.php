<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Photo;
use App\Models\Album; // <-- Add Album model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Database\Eloquent\Collection; // For type hinting $allPhotos

class PhotoGallery extends Component
{
    use WithFileUploads;

    public Album $album; // <-- Inject the current album via Route Model Binding

    public $photos = [];
    public $titles = [];
    public $descriptions = [];
    public Collection $allPhotos; // <-- Type hint the collection

    // Mount method receives the Album from the route
    public function mount(Album $album)
    {
        $this->album = $album;
        $this->loadPhotos();
    }

    protected function loadPhotos()
    {
        // Load photos specifically for the current album, ordered
        $this->allPhotos = $this->album->photos()->orderBy('order')->get();
         // Use refresh to make sure Livewire re-renders the collection if needed
         // $this->allPhotos = $this->album->refresh()->photos()->orderBy('order')->get();
    }

    public function save()
    {
        $this->validate([
            'photos.*' => 'image|max:10048', 
            'titles.*' => 'nullable|string|max:255',
            'descriptions.*' => 'nullable|string',
        ]);

        // Determine the starting order for new photos WITHIN THIS ALBUM
        $maxOrder = Photo::where('album_id', $this->album->id)->max('order') ?? -1;

        foreach ($this->photos as $index => $photo) {
            $path = $photo->store('photos', 'public'); // Store in 'public/photos'

            Photo::create([
                'title' => $this->titles[$index] ?? 'Untitled',
                'description' => $this->descriptions[$index] ?? null,
                'file_path' => $path,
                'order' => $maxOrder + 1 + $index, // Assign sequential order number
                'album_id' => $this->album->id,   // <-- Associate with the current album
            ]);
        }

        $this->reset(['photos', 'titles', 'descriptions']);
        $this->loadPhotos(); // Reload photos for the current album
        session()->flash('message', 'Photos uploaded successfully to album: ' . $this->album->name);
    }

    public function deletePhoto($photoId)
    {
        // Ensure the photo belongs to the current album for security (optional but good practice)
        $photo = Photo::where('id', $photoId)->where('album_id', $this->album->id)->firstOrFail();

        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        } else {
             Log::warning("Photo file not found for deletion: " . $photo->file_path);
        }

        $photo->delete();

        // Reload photos for the current album after deletion
        $this->loadPhotos();
        session()->flash('message', 'Photo deleted successfully!');
    }

    public function updatePhotoOrder(array $orderedIds)
    {
        // Add extra check: ensure all IDs belong to the current album? Maybe overkill if UI prevents it.
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                Photo::where('id', $id)
                       ->where('album_id', $this->album->id) // Scope update to current album photos
                       ->update(['order' => $index]);
            }
        });
        $this->loadPhotos(); // Reload to reflect new order
    }


    public function render()
    {
        // Pass the album name to the view, $allPhotos is already available
        return view('livewire.photo-gallery')
                ->with('layout', 'layouts.app'); // Pass layout data to the view
    }
}      