<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AlbumList;  
use App\Livewire\PhotoGallery;    // Use the existing one, modified
use App\Livewire\CreateAlbumForm; 
// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    // Redirect to albums list or a dashboard
    return redirect()->route('albums.index');
});

// Route to list all albums
Route::get('/albums', AlbumList::class)->name('albums.index');

// Route to show a specific album's photo gallery
// Use Route Model Binding to automatically fetch the Album model
Route::get('/albums/{album}', PhotoGallery::class)->name('albums.show');

// (Optional) Route to show a form for creating a new album
Route::get('/albums/create/new', CreateAlbumForm::class)->name('albums.create'); // Avoid conflict with {album}


Route::get('/', PhotoGallery::class);
