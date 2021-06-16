<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Specialty;

class SpecialtyController extends Controller
{
    public function index()
    {
        $success = true;
        $data = Specialty::all(['id', 'name']);
        return response()->json(compact('success', 'data'));
    }

    public function doctors(Specialty $specialty)
    {
        $success = true;
        $data = $specialty->users()->get(['users.id', 'users.name']);
        return response()->json(compact('success', 'data'));
    }
}
