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
        foreach (Recipe::recipeById($id) as $recipe){
            $data['recipe'] = $recipe;
        }

        foreach (Recipe::find($id)->ingredients as $ingredient) {
            $data['ingredients'][] = [
                'id' => $ingredient['id'],
                'name' => $ingredient['name'],
            ];
        }
        return response()->json($data)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }
}
