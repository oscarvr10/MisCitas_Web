<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show() 
    {
        $success = true;
        $data = Auth::guard('api')->user();
        
        return response()->json(compact('success', 'data'));
    }

    public function update(Request $request) 
    {
        $user = Auth::guard('api')->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();
    }
}
