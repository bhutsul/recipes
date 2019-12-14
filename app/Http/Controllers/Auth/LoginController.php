<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Socialite;
use Session;

class LoginController extends Controller
{
    public function redirectToProviderLogin()
    {
        return Socialite::driver('google')->redirect();
    }
    public function authenticateSocial()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $findUser = User::where('google_email', $user->getEmail())->first();

        if (!$findUser)
        {
            User::create([
                'google_email' => $user->getEmail(),
            ]);
        }

        $token = $user->token;
        $expiresIn = $user->expiresIn;

        return response()->json([
            'token' => $token,
            'expires_at' => $expiresIn,
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised'
            ], 401);
        }

        $token = Auth::user()->createToken(config('app.name'));
        $token->token->expires_at = $request->remember_me ?
            Carbon::now()->addMonth() :
            Carbon::now()->addDay();

        $token->token->save();

        return response()->json([
            'token_type' => 'Bearer',
            'token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ], 200);
    }
}
