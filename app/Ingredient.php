<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public static function getIngredients()
    {
        return $ingredients = Ingredient::all();
    }

    public static function getIngredientsId($ingredient = []) {
        return Ingredient::whereIn('name', $ingredient)->get('id');
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Recipe', 'ingredient_index', 'ingredient_id', 'recipe_id');
    }
}
