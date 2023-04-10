<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    public function multimedia()
    {
        return $this->morphedByMany(multimedia::class, 'taggable');
    }

    public function articles()
    {
        return $this->morphedByMany(article::class, 'taggable');
    }

    public function sounds()
    {
        return $this->morphedByMany(sound::class, 'taggable');
    }

    public function videos()
    {
        return $this->morphedByMany(video::class, 'taggable');
    }

    public function category()
    {
        return $this->morphedByMany(category::class, 'taggable');
    }

    public function picture()
    {
        return $this->morphedByMany(Picture::class, 'taggable');
    }

}
