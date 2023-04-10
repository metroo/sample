<?php
//php artisan make:model Todo -mcr
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config_type extends Model
{
    use HasFactory;
    protected $table = "config_type";
}
