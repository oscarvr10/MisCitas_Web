<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialty;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    #region Views

    public function index()
    {
        $specialties = Specialty::all();
        return view('specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('specialties.create');
    }

    public function edit(Specialty $specialty)
    {
        return view('specialties.edit', compact('specialty'));
    }
    #endregion


    #region Http methods

    public function save(Request $request)
    {
       //dd($request->all()); --> Imprimir en consola
       $rules = [
           'name' => 'required|min:3',
       ];
       $msssages = [
        'name.required' => 'Es necesario ingresar un nombre',
        'name.min' => 'Es necesario ingresar al menos 3 caracteres',
       ];

       $this->validate($request, $rules, $msssages);

       $specialty = new Specialty();
       $specialty->name = $request->input('name');
       $specialty->description = $request->input('description');
       $specialty->save();

       return redirect('/specialties');
    }

    public function update(Request $request, Specialty $specialty)
    {
       $rules = [
           'name' => 'required|min:3',
       ];
       $msssages = [
        'name.required' => 'Es necesario ingresar un nombre',
        'name.min' => 'Es necesario ingresar al menos 3 caracteres',
       ];

       $this->validate($request, $rules, $msssages);

       $specialty->name = $request->input('name');
       $specialty->description = $request->input('description');
       $specialty->save();

       return redirect('/specialties');
    }

    #endregion
}
