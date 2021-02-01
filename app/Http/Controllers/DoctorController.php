<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Doctor;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = User::doctors()->get();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'    => 'required|min:3',
            'email'   => 'required|email',
            'id_card' => 'nullable|max:18|min:18',
            'address' => 'nullable|min:3',
            'phone'   => 'nullable|digits:10'            
        ];
        $this->validate($request, $rules);

        //Mass assignment
        User::create(
            $request->only('name','email', 'id_card', 'address', 'phone')
            + [
                'role' => 'doctor',
                'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
            ]);
        
        $notification = "El médico se ha registrado exitosamente.";
        return redirect('/doctors')->with(compact('notification'));
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
        $doctor = User::doctors()->findOrFail($id);
        return view('doctors.edit', compact('doctor'));
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
        $rules = [
            'name'    => 'required|min:3',
            'email'   => 'required|email',
            'id_card' => 'nullable|max:18|min:18',
            'address' => 'nullable|min:3',
            'phone'   => 'nullable|digits:10'            
        ];
        $this->validate($request, $rules);

        $doctor = User::doctors()->findOrFail($id);
        $data = $request->only('name','email', 'id_card', 'address', 'phone');
        $password = $request->input('password');
        if($password) 
            $data += ['password' => password_hash($password, PASSWORD_BCRYPT)];

        $doctor->fill($data);
        $doctor->save();
        $notification = "La información del médico se ha registrado exitosamente.";
        return redirect('/doctors')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $doctor)
    {
        $deletedDoctor = $doctor->name;
        $doctor->delete();
        $notification = "El médico $deletedDoctor se ha eliminado exitosamente.";
        return redirect('/doctors')->with(compact('notification'));
    }
}
