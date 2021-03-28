<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\CancelledAppointment;
use App\Http\Requests\StoreAppointment;
use App\Interfaces\ScheduleServiceInterface;
use App\Specialty;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        
        if ($role == 'admin') {
            $pendingAppointments = Appointment::where('status', 'Reservada')
                ->paginate(10);
            $confirmedAppointments = Appointment::where('status', 'Confirmada')
                ->paginate(10);
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->paginate(10);
        } elseif ($role == 'doctor') {
            $pendingAppointments = Appointment::where('status', 'Reservada')
                ->where('doctor_id', auth()->id())
                ->paginate(10);
            $confirmedAppointments = Appointment::where('status', 'Confirmada')
                ->where('doctor_id', auth()->id())
                ->paginate(10);
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->where('doctor_id', auth()->id())
                ->paginate(10);
        } elseif ($role == 'patient') {
            $pendingAppointments = Appointment::where('status', 'Reservada')
                ->where('patient_id', auth()->id())
                ->paginate(10);
            $confirmedAppointments = Appointment::where('status', 'Confirmada')
                ->where('patient_id', auth()->id())
                ->paginate(10);
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->where('patient_id', auth()->id())
                ->paginate(10);
        }

        return view(
            'appointments.index',
            compact('pendingAppointments', 'confirmedAppointments', 'oldAppointments', 'role')
        );
    }

    public function show(Appointment $appointment)
    {
        $role = auth()->user()->role;
        return view('appointments.show', compact('appointment', 'role'));
    }

    public function create(ScheduleServiceInterface $scheduleService)
    {
        $specialties = Specialty::all();
        $specialtyId = old('specialty_id');
        if ($specialtyId) {
            $specialty = Specialty::find($specialtyId);
            $doctors = $specialty->users;
        } else {
            $doctors = collect();
        }

        $scheduledDate = old('scheduled_date');
        $doctorId = old('doctor_id');
        if ($scheduledDate && $doctorId) {
            $intervals = $scheduleService->getAvailableIntervals($scheduledDate, $doctorId);
        } else {
            $intervals = null;
        }
        return view('appointments.create', compact('specialties', 'doctors', 'intervals'));
    }

    public function store(StoreAppointment $request, ScheduleServiceInterface $scheduleService)
    {
        /*if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }*/

        $created = Appointment::createForPatient($request, auth()->id());
        if ($created)
            $notification = 'La cita se ha registrado exitosamente';
        else
            $notification = 'Ocurrió un problema al registrar la cita médica';

        
        return back()->with(compact('notification'));
    }

    public function postCancel(Appointment $appointment, Request $request)
    {
        if ($request->has('justification')) {
            $cancellation = new CancelledAppointment();
            $cancellation->justification = $request->input('justification');
            $cancellation->cancelled_by_id = auth()->id();
            //$cancellation->appointment_id = $appointment->id;
            //$appointment->save();
            $appointment->cancellation()->save($cancellation);
        }

        $appointment->status = 'Cancelada';
        $appointment->save();

        $notification = 'La cita se ha cancelado correctamente';
        return redirect('/appointments')->with(compact('notification'));
    }

    public function showCancelForm(Appointment $appointment)
    {
        $role = auth()->user()->role;
        if ($appointment->status == 'Confirmada' || ($appointment->status == 'Reservada' && $role != 'patient'))
            return view('appointments.cancel', compact('appointment','role'));

        return redirect('/appointments');
    }

    public function postConfirm(Appointment $appointment)
    {
        $appointment->status = 'Confirmada';
        $appointment->save(); //update

        $notification = 'La cita se ha confirmado correctamente';
        return redirect('/appointments')->with(compact('notification'));
    }
}
