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

        return $appointments;
    }

    public function store(StoreAppointment $request) 
    {
        $success = Appointment::createForPatient($request, auth()->id());
        return compact('success');
    }
}
