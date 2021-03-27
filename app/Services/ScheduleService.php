<?php

namespace App\Services;

use App\Appointment;
use App\Interfaces\ScheduleServiceInterface;
use App\WorkDay;
use Carbon\Carbon;

class ScheduleService implements ScheduleServiceInterface
{
    public function getAvailableIntervals($date, $doctorId)
    {
        $workDay = WorkDay::where('active', true)
            ->where('day', $this->getDayFromDate($date))
            ->where('user_id', $doctorId)
            ->first([
                'morning_start', 'morning_end',
                'afternoon_start', 'afternoon_end'
            ]);

        if ($workDay) {
            $morningIntervals = $this->getIntervals(
                $workDay->morning_start,
                $workDay->morning_end,
                $date,
                $doctorId
            );
    
            $afternoonIntervals = $this->getIntervals(
                $workDay->afternoon_start,
                $workDay->afternoon_end,
                $date,
                $doctorId
            );
        } else {
            $morningIntervals = [];
            $afternoonIntervals = [];
        }        

        $data = [];
        $data['morning'] = $morningIntervals;
        $data['afternoon'] = $afternoonIntervals;

        return $data;
    }

    public function isAvailableInterval($date, $doctorId, Carbon $start) 
    {
        $exists = Appointment::where('doctor_id', $doctorId)
                ->where('scheduled_date', $date)
                ->where('scheduled_time', $start->format('H:i:s'))
                ->exists();

        return !$exists; //Available if already none exists
    }

    private function getIntervals($start, $end, $date, $doctorId)
    {
        $start = new Carbon($start);
        $end = new Carbon($end);
        $intervals = [];
        while ($start < $end) {
            $interval = [];
            
            $interval['start'] = $start->format('g:i A');
            
            $isAvailable = $this->isAvailableInterval($date,$doctorId, $start);
           
            $start->addMinutes(30);
            $interval['end'] = $start->format('g:i A');

            if ($isAvailable) {
                $intervals[] = $interval;
            }
        }

        return $intervals;
    }

    private function getDayFromDate($date)
    {
        $dateCarbon = new Carbon($date);

        //day of Week
        //Carbon -> 0(sunday) - 6(saturday)
        //My convention -> 0(monday) - 6(sunday)
        $i = $dateCarbon->dayOfWeek;
        $day = ($i == 0) ? 6 : $i - 1;
        return $day;
    }
}
