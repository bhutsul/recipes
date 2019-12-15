<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\IngredientIndex;
use App\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class AddRecipeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        //$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function create()
    {
        $ingredients = explode(',',$this->request->post('ingredients'));

        $data = [
            'name_recipe' => $this->request->input('name_recipe'),
            'description' => $this->request->description_recipe,
            'image_recipe' => $this->request->file('image'),
        ];

        $rules = [
            'name_recipe' => ['required', 'string', 'max:255', 'unique:recipes'],
            'description' => ['required', 'string'],
            'image_recipe' => $this->request->file('image') == 'undefined'  ? 'image|mimes:jpeg,jpg,png,gif' : '',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $name = $this->request->file('image')->store('uploads', 'public');
            //створення рецепту
            Recipe::Create([
                'name_recipe' => $this->request->input('name_recipe'),
                'description' => $this->request->description_recipe,
                'image_name' => $name,
                'category_id' => $this->request->input('category_id'),
            ]);

            $idRecipe = Recipe::getIdRecipe($this->request->input('name_recipe'));
            //добавлення інгредієнтів яких немає в таблиці
            foreach ($ingredients as $name) {
                Ingredient::updateOrCreate([
                    'name' => $name,
                ]);
            }

            $idRecipesAndIngredients = [];
            //створення масиву id рецептів та інгредієнтів
            foreach (Ingredient::getIngredientsId($ingredients) as $ingredientId) {
                $idRecipesAndIngredients[] = [
                    'ingredient_id' => $ingredientId['id'],
                    'recipe_id' => $idRecipe[0]['id']
                ];
            }

            IngredientIndex::insert($idRecipesAndIngredients);
        }
    }
}
