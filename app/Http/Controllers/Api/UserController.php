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
        $user = Auth::guard('api')->user();
        $data = compact('user');
        
        return response()->json(compact('success', 'data'));
    }
}
