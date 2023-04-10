<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class video extends Model
{
    use HasFactory;
    //
    public function categories()
    {
        return $this->belongsToMany(category::class , 'videos_to_categories');
    }
    //
    public function uploads()
    {
        return $this->belongsToMany(Upload::class , 'videos_to_uploads');
    }


    public function tags()
    {
        return $this->morphToMany(tags::class, 'taggable');
    }
}
