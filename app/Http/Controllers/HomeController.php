<?php

namespace App\Http\Controllers;

use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $minutes = $this->daysToMinutes(7);       
        $appointmentsByDay = Cache::remember('appointments_by_day', $minutes, function(){
            $results = Appointment::select([
                DB::raw('DAYOFWEEK(scheduled_date) AS day'),
                DB::raw('COUNT(*) AS count')])
            ->groupBy(DB::raw('DAYOFWEEK(scheduled_date)'))
            ->whereIn('status', ['Confirmada', 'Atendida'])
            ->get(['day', 'count'])
            ->mapWithKeys(function($item){
                return [$item['day'] => $item['count']];
            })->toArray();
    
            $counts= [];
            for ($i=1; $i <= 7; ++$i) { 
                if (array_key_exists($i, $results))
                   $counts[] = $results[$i];
                else
                    $counts[] = 0;
            }

            return $counts;
        });

        return view('home', compact('appointmentsByDay'));
    }

    private function daysToMinutes($days)
    {
        $hours = $days * 24;
        return $hours * 60;
    }
}
