<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'order',
        'album_id',
    ];


    public function album()
    {
        return $this->belongsTo(Album::class);
    }
    // Optional: add a mutator to get the full URL of the photo.
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
