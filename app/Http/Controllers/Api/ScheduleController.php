<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ScheduleServiceInterface;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function hours(Request $request, ScheduleServiceInterface $scheduleService)
    {
        //dd($request->all());
        $rules = [
            'date' => 'required|date_format:"Y-m-d"',
            'doctor_id' => 'required|exists:users,id'
        ];

        $this->validate($request, $rules);
        $date = $request->input('date');
        $doctorId = $request->input('doctor_id');

        $success = true;
        $data = $scheduleService->getAvailableIntervals($date, $doctorId);
        return response()->json(compact('success', 'data'));
    }
}
