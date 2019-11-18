<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryIndex;
use App\Ingredient;
use App\IngredientIndex;
use App\Recipe;
use Illuminate\Http\Request;

class AddRecipeController extends Controller
{
    public function create()
    {
        $requestFrontend = [

            'category' => [
                'id' => 4,
            ],

            'recipe' => [
                'name' => 'test_new3dd',
                'description' => 'test4',
                'image_name' => 'test4',
            ],

            'ingredient' =>[
                'test9','огірки','сіль', 'test5', 'молоко коровяче свіже'
            ]
        ];

        Recipe::Create([
            'name_recipe' => $requestFrontend['recipe']['name'],
            'recipe' => $requestFrontend['recipe']['description'],
            'image_name' => $requestFrontend['recipe']['image_name'],
            'category_id' => $requestFrontend['category']['id']
        ]);

        $idRecipe = Recipe::getIdRecipe($requestFrontend['recipe']['name']);

        foreach ($requestFrontend['ingredient'] as $name) {

            Ingredient::updateOrCreate([
                'name' => $name,
            ]);
        }

        $idRecipesAndIngredients = [];

        foreach (Ingredient::getIngredientsId($requestFrontend['ingredient']) as $ingredientId) {

            $idRecipesAndIngredients[] = [
                'ingredient_id' => $ingredientId['id'],
                'recipe_id' => $idRecipe[0]['id']
            ];
        }

        IngredientIndex::insert($idRecipesAndIngredients);
    }
}
