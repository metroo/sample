<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    //
    public function categories()
    {
        return $this->belongsToMany(category::class , 'products_to_categories');
    }
    //
    public function uploads()
    {
        return $this->belongsToMany(Upload::class , 'products_to_uploads');
    }

}
