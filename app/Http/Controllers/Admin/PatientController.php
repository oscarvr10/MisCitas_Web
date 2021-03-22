<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = User::patients()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->performValidation($request);

        //Mass assignment
        User::create(
            $request->only('name','email', 'id_card', 'address', 'phone')
            + [
                'role' => 'patient',
                'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
            ]);
        
        $notification = "El paciente se ha registrado exitosamente.";
        return redirect('/patients')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = User::patients()->findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->performValidation($request);

        $patient = User::patients()->findOrFail($id);
        $data = $request->only('name','email', 'id_card', 'address', 'phone');
        $password = $request->input('password');
        if($password) 
            $data += ['password' => password_hash($password, PASSWORD_BCRYPT)];

        $patient->fill($data);
        $patient->save();
        $notification = "La información del paciente se ha registrado exitosamente.";
        return redirect('/patients')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $patient)
    {
        $deletedPatient = $patient->name;
        $patient->delete();
        $notification = "El paciente $deletedPatient se ha eliminado exitosamente.";
        return redirect('/doctors')->with(compact('notification'));
    }

    private function performValidation(Request $request)
    {
        $rules = [
            'name'    => 'required|min:3',
            'email'   => 'required|email',
            'id_card' => 'nullable|max:18|min:18',
            'address' => 'nullable|min:3',
            'phone'   => 'nullable|digits:10'            
        ];
        $msssages = [
            'name.required' => 'Es necesario ingresar un nombre',
            'email.required' => 'Es necesario ingresar un correo electrónico.',
        ];
 
        $this->validate($request, $rules, $msssages);
    }
}
