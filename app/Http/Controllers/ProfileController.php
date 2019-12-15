<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    protected $user;

    public function __construct()
    {
//        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function userInfo(){
        return response()->json(['email' => JWTAuth::user()->email]);
    }

    public function savedRecipes(){

    }

    public function customRecipes(Request $request){
        $recipes = $this->user->recipes()->get();
        if ($recipes) {
            return response()->json($recipes);
        }
    }
}
