<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ValidateAndCreatePatient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    use ValidateAndCreatePatient;
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
            
            $tokenResult = $this->generateJwtToken($user);
            $jwt = $tokenResult->accessToken;
            $success = true;

            $data = compact('user', 'jwt');
            return response()->json(compact('success', 'data'));
        }
        
        $success = false;
        $message = 'Invalid credentials';
        return response()->json(compact('success', 'message'), 401);
        
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

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        
        Auth::login($user);
        $tokenResult = $this->generateJwtToken($user);
        $jwt = $tokenResult->accessToken;
        $success = true;
        $data = compact('user', 'jwt');

        return response()->json(compact('success', 'data'));       
    }

    private function generateJwtToken($user)
    {
        $tokenResult = $user->createToken('JWT'); 
        $token = $tokenResult->token;         
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return $tokenResult;            
    }
}
