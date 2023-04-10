<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sound extends Model
{
    use HasFactory;

    //
    public function categories()
    {
        return $this->belongsToMany(category::class , 'sounds_to_categories');
    }
    //
    public function uploads()
    {
        return $this->belongsToMany(Upload::class , 'sounds_to_uploads');
    }

    public function tags()
    {
        return $this->morphToMany(tags::class, 'taggable');
    }
}
