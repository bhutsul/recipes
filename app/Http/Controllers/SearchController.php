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

        $arrayIngredientsId = [];
        //отримуємо id інгредієнтів
        foreach (Ingredient::getIngredientsId($ingredients) as $ingredientId) {
            $arrayIngredientsId[] = $ingredientId['id'];
        }

        if (isset($ingredients) && $ingredients[0] != false && $category == false) {
            //нетерпляче завантаження з додатковим обмежженням
            $recipesAndIngredients = Ingredient::with(['recipes' => function($query)
            {
                $query->groupBy('recipes.id');
            }])->whereIn('id', $arrayIngredientsId)->get();
            //для повернення тільки інформації про рецепти
            foreach ($recipesAndIngredients as $recipes) {
                foreach ($recipes['recipes'] as $infoRecipes) {
                    $data[] = $infoRecipes;
                }
            }
        } elseif (isset($category) && $ingredients[0] == false) {
            //пошук відносно категорії
            $data = Recipe::getRecipesByCategoryId($category);
        } else {
            //нетерпляче завантаження з додатковим обмежженням  відносно категорії
            $recipesAndIngredients = Ingredient::with(['recipes' => function($query) use($category)
            {
                $query->where('category_id', '=', $category)->groupBy('recipes.id');
            }])->whereIn('id', $arrayIngredientsId)->get();

            //для повернення тільки інформації про рецепти
            foreach ($recipesAndIngredients as $recipes) {
                foreach ($recipes['recipes'] as $infoRecipes) {
                    unset($infoRecipes['pivot']);
                    $data[] = $infoRecipes;
                }
            }
        }
        return response()->json($data)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }
}
