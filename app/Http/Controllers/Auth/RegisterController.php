<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registration(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $rules = [
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8']
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }
        else {
            User::Create([
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'api_token' => Str::random(60),
            ]);

            return response()->json([
                'message' => 'You were successfully registered. Use your email and password to sign in.'
            ], 200);
        }
    }
}
