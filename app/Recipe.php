<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name_recipe', 'recipe', 'image_name', 'category_id', 'description', 'user_id'
    ];

    public static function getIdRecipe($description = '')
    {
        return $recipes = Recipe::where('name_recipe' , $description)->get('id');
    }

    public static function getRecipe()
    {
        return $recipes = Recipe::all();
    }

    public static function recipeById($id)
    {
        return $recipe = Recipe::where('id', $id)->get();
    }


    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient', 'ingredient_index', 'recipe_id', 'ingredient_id');
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public static function getRecipesByCategoryId($categoryId)
    {
        return Recipe::where('category_id', '=', $categoryId)->get();
    }
}
