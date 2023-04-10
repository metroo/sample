<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;
    protected $table = "pictures";
    public function categories()
    {
        return $this->belongsToMany(category::class , 'pictures_to_categories');
    }
    //
    public function uploads()
    {
        return $this->belongsToMany(Upload::class , 'pictures_to_uploads');
    }

    public function tags()
    {
        return $this->morphToMany(tags::class, 'taggable');
    }
}
