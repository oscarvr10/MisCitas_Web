<?php

namespace App\Http\Controllers;

use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //1->Sunday, 2->Monday, 3->Tuesday, 4-> Wednesday, 5->Thursday, 6->Friday, 7->Saturday
        $appointmentsByDay = Appointment::select([
                DB::raw('DAYOFWEEK(scheduled_date) AS day'),
                DB::raw('COUNT(*) AS count')])
            ->groupBy(DB::raw('DAYOFWEEK(scheduled_date)'))
            //->where('status', 'Confirmada')
            ->pluck('count');
        //dd($appointments);
        return view('home', compact('appointmentsByDay'));
    }
}
