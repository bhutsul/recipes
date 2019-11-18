<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientIndex extends Model
{
    protected $table = 'ingredient_index';

    public $timestamps = false;

    protected $fillable = [
        'ingredient_id', 'recipe_id'
    ];
}
