<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Album;

class CreateAlbumForm extends Component
{
    public $name = '';
    public $description = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:albums,name', // Ensure album names are unique
        'description' => 'nullable|string',
    ];

    public function saveAlbum()
    {
        $this->validate();

        $album = Album::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Album "' . $album->name . '" created successfully!');

        // Redirect to the newly created album's gallery or the albums list
        return redirect()->route('albums.show', $album);
        // Or: return redirect()->route('albums.index');
    }

    public function render()
    {
        return view('livewire.create-album-form')->with('layout', 'layouts.app');
    }
}