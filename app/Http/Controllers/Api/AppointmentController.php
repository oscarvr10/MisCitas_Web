<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index() 
    {
        $user = Auth::guard('api')->user();
        $appointments = $user->asPatientAppointments()
        ->with([
            'specialty' => function ($query){
                $query->select('id', 'name');
            }, 
            'doctor'=> function ($query){
                $query->select('id', 'name');
            }, 
        ])
        ->get([
            "id",
            "description",
            "specialty_id",
            "doctor_id",
            "scheduled_date",
            "scheduled_time",
            "type",
            "created_at",
            "status",
        ]);

        if($appointments)
            $success =true;
        else
            $success = false;
            
        $data = $appointments;
        return response()->json(compact('success', 'data'));
    }

    public function store(StoreAppointment $request) 
    {
        $patientId = Auth::guard('api')->id();
        $appointment = Appointment::createForPatient($request, $patientId);
        if ($appointment)
            $success = true;
        else
            $success = false;            
            
        return compact('success');
    }
}
