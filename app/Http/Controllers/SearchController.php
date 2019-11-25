<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Recipe;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $ingredients = explode(',',$this->request->get('ingredients', false));
        $category = $this->request->get('category', false);

        $data = [];

        if (isset($ingredients) && $ingredients[0] != false && $category == false) {
            $arrayIngredientsId = [];

            foreach (Ingredient::getIngredientsId($ingredients) as $ingredientId) {
                $arrayIngredientsId[] = $ingredientId['id'];
            }

            $recipesAndIngredients = Ingredient::with(['recipes' => function($query)
            {
                $query->groupBy('recipes.id');
            }])->whereIn('id', $arrayIngredientsId)->get();

            foreach ($recipesAndIngredients as $recipes) {
                foreach ($recipes['recipes'] as $infoRecipes) {
                    $data[] = $infoRecipes;
                }
            }
            return response()->json($data)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
        } elseif (isset($category) && $ingredients[0] == false) {
            return response()->json(Recipe::getRecipesByCategoryId($category))->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
        } else {
            $arrayIngredientsId = [];

            foreach (Ingredient::getIngredientsId($ingredients) as $ingredientId) {
                $arrayIngredientsId[] = $ingredientId['id'];
            }

            $idCategory = $category;

            $recipesAndIngredients = Ingredient::with(['recipes' => function($query) use($idCategory)
            {
                $query->where('category_id', '=', $idCategory)->groupBy('recipes.id');
            }])->whereIn('id', $arrayIngredientsId)->get();

            foreach ($recipesAndIngredients as $recipes) {
                foreach ($recipes['recipes'] as $infoRecipes) {
                    unset($infoRecipes['pivot']);
                    $data[] = $infoRecipes;
                }
            }
            return response()->json($data)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
        }
    }
}
