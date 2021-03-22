<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function appointments()
    {
        $monthlyCounts = Appointment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(1) as count')
        )->groupBy('month')->get()->toArray();

        $counts = array_fill(0, 12, 0); //index, quantity, value

        foreach ($monthlyCounts as $monthlyCount) {
            $i = $monthlyCount['month'] - 1; // starts in 0-> January
            $counts[$i] = $monthlyCount['count'];
        }
        return view('charts.appointments', compact('counts'));
    }

    public function doctors()
    {
        $now = Carbon::now();
        $end = $now->format('Y-m-d');
        $start = $now->subYear()->format('Y-m-d');
        return view('charts.doctors', compact('start', 'end'));
    }

    public function doctorsJson(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $doctors = User::doctors()
            ->select('name')
            ->withCount([
                'attendedAppointments' => function($query) use ($start, $end) {
                    $query->whereBetween('scheduled_date', [$start, $end]);
                },
                'cancelledAppointments'=> function($query) use ($start, $end) {
                    $query->whereBetween('scheduled_date', [$start, $end]);
                },
            ])
            ->orderBy('attended_appointments_count', 'desc')
            ->take(5)
            ->get();

        $data = [];
        $data['categories'] = $doctors->pluck('name');

        $series = [];
        $series1['name'] = 'Citas atendidas';
        $series1['data'] =  $doctors->pluck('attended_appointments_count');

        $series2['name'] = 'Citas canceladas';
        $series2['data']  = $doctors->pluck('cancelled_appointments_count');

        $series[] = $series1;
        $series[] = $series2;

        $data['series'] = $series;

        return $data; //{categories: ['A', 'B'], series: [1,2]}
    }
}
