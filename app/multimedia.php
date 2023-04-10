<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class multimedia extends Model
{
    use HasFactory;
    //
    public function categories()
    {
        return $this->belongsToMany(category::class , 'multimedia_to_categories');
    }
    //
    public function uploads()
    {
        return $this->belongsToMany(Upload::class , 'multimedia_to_uploads');
    }


    public function tags()
    {
        return $this->morphToMany(tags::class, 'taggable');
    }
}
