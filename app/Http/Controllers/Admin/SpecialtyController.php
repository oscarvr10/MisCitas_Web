<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Specialty;
use App\Http\Controllers\Controller;

class SpecialtyController extends Controller
{
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

    public function store(Request $request)
    {
       //dd($request->all()); --> Imprimir en consola
       $this->performValidation($request);
       $specialty = new Specialty();
       $specialty->name = $request->input('name');
       $specialty->description = $request->input('description');
       $specialty->save();

       $notification = "La especialidad se ha registrado exitosamente.";
       return redirect('/specialties')->with(compact('notification'));
    }

    public function update(Request $request, Specialty $specialty)
    {       
       $this->performValidation($request);
       $specialty->name = $request->input('name');
       $specialty->description = $request->input('description');
       $specialty->save();

       $notification = "La especialidad se ha actualizado exitosamente.";
       return redirect('/specialties')->with(compact('notification'));
    }

    public function destroy(Specialty $specialty)
    {
       $deletedSpecialty = $specialty->name;
       $specialty->delete();
       $notification = "La especialidad '".$deletedSpecialty."' se ha eliminado exitosamente.";
       return redirect('/specialties')->with(compact('notification'));
    }

    private function performValidation(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
        ];
        $msssages = [
         'name.required' => 'Es necesario ingresar un nombre',
         'name.min' => 'Es necesario ingresar al menos 3 caracteres',
        ];
 
        $this->validate($request, $rules, $msssages);
    }

    #endregion
}