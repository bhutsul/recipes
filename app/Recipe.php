<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name_recipe', 'recipe', 'image_name', 'category_id'
    ];

    public static function getIdRecipe($description = '')
    {
        return $recipes = Recipe::where('name_recipe' , $description)->get('id');
    }

    public static function getRecipe()
    {
        return $recipes = Recipe::all();
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Recipe');
    }

    public static function getRecipesByCategoryId($categoryId)
    {
        return Recipe::where('category_id', '=', $categoryId)->get();
    }
}
