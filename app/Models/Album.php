<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get all the photos for the Album.
     */
    public function photos(): HasMany
    {
        // Order photos within the album by their 'order' column
        return $this->hasMany(Photo::class)->orderBy('order');
    }
}