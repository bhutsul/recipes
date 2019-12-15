<?php

namespace App\Http\Controllers;

use App\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function showAll(Recipe $recipe)
    {
        $recipes = Recipe::getRecipe();

        return response()->json($recipes)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }

    public function showRecipe($id)
    {
        $recipe = Recipe::recipeById($id);

//        foreach (Recipe::find($id)->ingredients as $ingredient) {
//            echo $ingredient;
//        }
        return response()->json($recipe)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }
}
