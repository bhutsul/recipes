<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    public static function getCategory()
    {
        return $categories = Category::all();
    }
}
