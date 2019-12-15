<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function userInfo(){
        return response()->json(['email' => JWTAuth::user()->email]);
    }

    public function savedRecipes() {
        $recipes = $this->user->savedRecipes()->get();
        if ($recipes) {
            return response()->json($recipes);
        }
    }

    public function customRecipes(Request $request){
        $recipes = $this->user->customRecipes()->get();
        if ($recipes) {
            return response()->json($recipes);
        }
    }
}
