<?php

namespace App\Http\Controllers;

use App\SavedRecipe;
use Illuminate\Http\Request;
use JWTAuth;

class SavedRecipeController extends Controller
{
    public function create(Request $request){
        $userId = JWTAuth::user()->id;
        $recipeId = $request->recipe_id;

        SavedRecipe::Create([
            'user_id' => $userId,
            'recipe_id' => $recipeId,
        ]);
    }

    public function delete(Request $request){
        $recipeId = $request->recipe_id;

        SavedRecipe::where('recipe_id', $recipeId)->delete();
    }
}
