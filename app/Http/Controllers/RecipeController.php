<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\IngredientIndex;
use App\Recipe;
use App\SavedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Illuminate\Support\Facades\Storage;

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

    public function create(Request $request)
    {
        $ingredients = explode(',',$request->post('ingredients'));

        $data = [
            'name_recipe' => $request->input('name_recipe'),
            'description' => $request->post('description_recipe'),
            'image_recipe' => $request->file('image'),
        ];

        $rules = [
            'name_recipe' => ['required', 'string', 'max:255', 'unique:recipes'],
            'description' => ['required', 'string'],
            'image_recipe' => $request->file('image') == 'undefined'  ? 'image|mimes:jpeg,jpg,png,gif' : '',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $name = NULL;
            if ($request->file('image') !== NULL) {
                $name = $request->file('image')->store('uploads', 'public');
            }
            //створення рецепту
            Recipe::Create([
                'name_recipe' => $request->input('name_recipe'),
                'description' => $request->input('description_recipe'),
                'image_name' => $name,
                'category_id' => $request->input('category_id'),
                'user_id' => JWTAuth::user()->id
            ]);

            $idRecipe = Recipe::getIdRecipe($request->input('name_recipe'));
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

    public function delete(Request $request)
    {
        $recipeId = $request->post('recipe_id');
        $userId = JWTAuth::user()->id;

        Recipe::where('user_id', $userId)->where('id', $recipeId)->delete();
    }

    public function update(Request $request)
    {
        $ingredients = explode(',',$request->input('ingredients'));

        $data = [
            'name_recipe' => $request->name_recipe,
            'description' => $request->input('description_recipe'),
            'image_recipe' => $request->file('image'),
        ];

        $rules = [
            'name_recipe' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_recipe' => $request->file('image') == 'undefined'  ? 'image|mimes:jpeg,jpg,png,gif' : '',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $name = NULL;
            if ($request->file('image') !== NULL) {
                $name = $request->file('image')->store('uploads', 'public');
            }
            //створення рецепту
            Recipe::where('user_id', JWTAuth::user()->id)->where('id', $request->input('recipe_id'))
                ->update([
                    'name_recipe' => $request->input('name_recipe'),
                    'description' => $request->input('description_recipe'),
                    'image_name' => $name,
                    'category_id' => $request->input('category_id'),
                    'user_id' => JWTAuth::user()->id
                 ]);

            //добавлення інгредієнтів яких немає в таблиці
            foreach ($ingredients as $name) {
                Ingredient::updateOrCreate([
                    'name' => $name,
                ]);
            }

            foreach (Ingredient::getIngredientsId($ingredients) as $ingredientId) {
                IngredientIndex::updateOrCreate([
                    'ingredient_id' => $ingredientId['id'],
                    'recipe_id' => $request->input('recipe_id')
                ]);
            }
            //створення масиву id інгредієнтів
            $idIngredients = [];
            foreach (Ingredient::getIngredientsId($ingredients) as $ingredient) {
                $idIngredients[] = $ingredient['id'];
            }

            IngredientIndex::where('recipe_id', $request->input('recipe_id'))
                        ->whereNotIn('ingredient_id',$idIngredients)
                        ->delete();
            unlink(storage_path('app/public/'.$request->old_image_name));
        }
    }
}
