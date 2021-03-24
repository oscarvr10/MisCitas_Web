<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {  
            $user = $request->user();
            
            $tokenResult = $user->createToken('JWT'); 
            $token = $tokenResult->token; 
            //if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();  
            
            $jwt = $tokenResult->accessToken;
            $success = true;

            $data = compact('user', 'jwt');
            return response()->json(compact('success', 'data'));
        }
        else {
            $success = false;
            $message = 'Invalid credentials';
            return response()->json(compact('success', 'message'), 401);
        }
        /*

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);*/
    }
    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $success = true;
        $message = 'User successfully logged out';
        return response()->json(compact('success', 'message'));        
    }
}
