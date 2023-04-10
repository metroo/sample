<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class article extends Model
{
    use HasFactory;
    //
    public function categories()
    {
        return $this->belongsToMany(category::class , 'articles_to_categories');
    }
    //
    public function uploads()
    {
        return $this->belongsToMany(Upload::class , 'articles_to_uploads');
    }

    public function tags()
    {
        return $this->morphToMany(tags::class, 'taggable');
    }
}
