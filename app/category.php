<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $guarded = [];

    public function sub_category(){

            return $this->hasMany(self::class , 'parent_id' , 'id');

    }



    public function tags()
    {
        return $this->morphToMany(tags::class, 'taggable');
    }
}
