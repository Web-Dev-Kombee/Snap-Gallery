<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Album;

class AlbumList extends Component
{
    public $albums = [];

    public function mount()
    {
        // Eager load photo count for efficiency
        $this->albums = Album::withCount('photos')->latest()->get();
    }

     public function deleteAlbum($albumId)
     {
        $album = Album::findOrFail($albumId);
        // Photos might be deleted automatically due to onDelete('cascade')
        // Add file deletion logic here if needed BEFORE deleting the album
        // foreach ($album->photos as $photo) {
        //     if (Storage::disk('public')->exists($photo->file_path)) {
        //         Storage::disk('public')->delete($photo->file_path);
        //     }
        // }
        $album->delete();
        session()->flash('message', 'Album deleted successfully!');
        $this->mount(); // Refresh the list
     }

    public function render()
    {
        return view('livewire.album-list'); // Ensure the Blade template extends the layout
    }
}