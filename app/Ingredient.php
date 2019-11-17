<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public static function searchIngredientsOnRequest($ingredient) {
        return Ingredient::where('name', $ingredient)->get('id');
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Recipe', 'ingredient_index', 'ingredient_id', 'recipe_id');
    }
}
