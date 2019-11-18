<?php

namespace App\Http\Controllers;

use App\Category;
use App\Ingredient;
use App\Recipe;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

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
        $ingredients = [
            'цукор',
            'масло вершкове',
            'огірки',
            'сіль'
        ];

        $category = [
            'id' => 1,
        ];

        $data = [];

        if (isset($ingredients) && !$category) {

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

        } elseif (isset($category) && !$ingredients) {

            return response()->json(Recipe::getRecipesByCategoryId($category['id']))->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);

        } else {

            $arrayIngredientsId = [];

            foreach (Ingredient::getIngredientsId($ingredients) as $ingredientId) {
                $arrayIngredientsId[] = $ingredientId['id'];
            }

            $idCategory = $category['id'];

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function show(Sarch $search)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function edit(Search $search)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Search $search)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Search  $search
     * @return \Illuminate\Http\Response
     */
    public function destroy(Search $search)
    {
        //
    }
}
