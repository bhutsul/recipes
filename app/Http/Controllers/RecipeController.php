<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\SavedRecipe;
use Illuminate\Http\Request;
use JWTAuth;

class RecipeController extends Controller
{
    public function showAll(Recipe $recipe)
    {
        $recipes = Recipe::getRecipe();

        return response()->json($recipes)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }

    public function showRecipe($id, Request $request)
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

    public function showRecipeAuth($id, Request $request)
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

        if ($request->get('token')){
            $userId = JWTAuth::user()->id;

            $data['favorite'] = SavedRecipe::where('user_id', $userId)->where('recipe_id', $id)->first() ? true : false;
        }
        return response()->json($data)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }
}
