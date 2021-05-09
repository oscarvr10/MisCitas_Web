<?php

namespace App\Console\Commands;

use App\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications via FCM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Searching for confirmed appointments within the next 24 hours...');
        
        // Hora actual
        $now = Carbon::now();
       
        $appointments = $this->getAppointments24Hours($now);
        //dd($appointments);

        foreach($appointments as $appointment){
            $appointment->patient->sendPushNotification('No olvides tu cita programada mañana a esta hora.');
            $this->info('Notification has been sent to patient with ID:'.$appointment->patient_id.' within 24 hours.');
        }

        $appointmentsNextHour = $this->getAppointmentsNextHour($now);

        foreach($appointmentsNextHour as $appointment){
            $appointment->patient->sendPushNotification('Tu cita programada es dentro de 1 hora. ¡Te esperamos!.');
            $this->info('Notification has been sent to patient with ID:'.$appointment->patient_id.' within 1 hour.');
        }
    }
    
    private function getAppointments24Hours($now)
    {
        //dd($now->toDateString());
        $now->addDay();
        $nowSub = $now->copy()->subMinutes(3)->toTimeString();
        $nowAdd = $now->copy()->addMinutes(2)->toTimeString();

        return Appointment::where('status', 'Confirmada')
            ->where('scheduled_date', $now->toDateString())
            ->where('scheduled_time', '>=', $nowSub)
            ->where('scheduled_time', '<', $nowAdd)
            ->get(['id', 'scheduled_date', 'scheduled_time', 'patient_id'])
            ->toArray();
    }

    private function getAppointmentsNextHour($now)
    {
        //dd($now->toDateString());
        $now->addHour();
        $nowSub = $now->copy()->subMinutes(3)->toTimeString();
        $nowAdd = $now->copy()->addMinutes(2)->toTimeString();

        return Appointment::where('status', 'Confirmada')
            ->where('scheduled_date', $now->toDateString())
            ->where('scheduled_time', '>=', $nowSub)
            ->where('scheduled_time', '<', $nowAdd)
            ->get(['id', 'scheduled_date', 'scheduled_time', 'patient_id'])
            ->toArray();
    }
}
