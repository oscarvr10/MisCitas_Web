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
        $this->info('Searching for confirmed appointments...');
        
        // Hora actual
        $now = Carbon::now();       
        $headers = ['id', 'scheduled_date', 'scheduled_time', 'patient_id'];

        $appointments = $this->getAppointments24Hours($now->copy());        
        $this->info('>>>>>>> Within 24 hours');
        $this->table($headers, $appointments->toArray());

        foreach($appointments as $appointment){
            $appointment->patient->sendPushNotification('No olvides tu cita programada mañana a esta hora.');
            $this->info('Notification has been sent to patient with ID:'.$appointment->patient_id.' within 24 hours.');
        }

        $appointmentsNextHour = $this->getAppointmentsNextHour($now->copy());
        $this->info('>>>>>>> Next hour');
        $this->table($headers, $appointmentsNextHour->toArray());

        foreach($appointmentsNextHour as $appointment){
            $appointment->patient->sendPushNotification('Tu cita programada es dentro de 1 hora. ¡Te esperamos!.');
            $this->info('Notification has been sent to patient with ID:'.$appointment->patient_id.' within 1 hour.');
        }
    }
    
    private function getAppointments24Hours($now)
    {
        //dd($now->toDateString());
        return Appointment::where('status', 'Confirmada')
            ->where('scheduled_date', $now->addDay()->toDateString())
            ->where('scheduled_time', '>=', $now->copy()->subMinutes(3)->toTimeString())
            ->where('scheduled_time', '<', $now->copy()->addMinutes(2)->toTimeString())
            ->get(['id', 'scheduled_date', 'scheduled_time', 'patient_id']);
            //->toArray();
    }

    private function getAppointmentsNextHour($now)
    {
        //dd($now->toDateString());   
        return Appointment::where('status', 'Confirmada')
            ->where('scheduled_date', $now->addHour()->toDateString())
            ->where('scheduled_time', '>=', $now->copy()->subMinutes(3)->toTimeString())
            ->where('scheduled_time', '<', $now->copy()->addMinutes(2)->toTimeString())
            ->get(['id', 'scheduled_date', 'scheduled_time', 'patient_id']);
    }
}
