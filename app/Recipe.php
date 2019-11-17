<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $timestamps = false;

    public static function getRecipe()
    {
        return $recipes = Recipe::all();
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Recipe');
    }
}
