<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Session;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
}
