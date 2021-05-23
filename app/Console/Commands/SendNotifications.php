<?php

namespace App\Console\Commands;

use App\Appointment;
use App\NotifiedUser;
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
        $headers = ['id', 'scheduled_date', 'patient_id','scheduled_time'];

        $appointments = $this->getAppointments24Hours($now->copy());        
        $this->info('>>>>>>> Within 24 hours');
        $this->table($headers, $appointments->toArray());

        foreach($appointments as $appointment){
            $notifiedUser = NotifiedUser::where('appointment_id', $appointment->id)->get()->first();         
            if (!$notifiedUser || !$notifiedUser->push24h_sent) {
                $appointment->patient->sendPushNotification('No olvides tu cita programada mañana a esta hora.');
                $this->info('Notification has been sent to patient with ID:'.$appointment->patient_id.' within 24 hours.');
                $this->setNotifiedUser($appointment->patient_id, $appointment->id, true);
            }            
        }

        $appointmentsNextHour = $this->getAppointmentsNextHour($now->copy());
        $this->info('>>>>>>> Next hour');
        $this->table($headers, $appointmentsNextHour->toArray());

        foreach($appointmentsNextHour as $appointment) {
            $notifiedUser = NotifiedUser::where('appointment_id', $appointment->id)->get()->first();
            if (!$notifiedUser || !$notifiedUser->push1h_sent) {
                $appointment->patient->sendPushNotification('Tu cita programada es dentro de 1 hora. ¡Te esperamos!.');
                $this->info('Notification has been sent to patient with ID:'.$appointment->patient_id.' within 1 hour.');
                $this->setNotifiedUser($appointment->patient_id, $appointment->id, false, true);
            }
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

    private function setNotifiedUser($patientId, $appointmentId, $push24hSend = false, $push1hSend = false)
    { 
        $notifiedUser = NotifiedUser::where('appointment_id', $appointmentId)->get()->first(); 

        if ($notifiedUser) {
            if ($push24hSend) {                
                $notifiedUser->push24h_sent = $push24hSend;
            }
            else if ($push1hSend) {
                $notifiedUser->push1h_sent = $push1hSend;
            }
            $notifiedUser->save();            
        }
        else{
            //dd($push24hSend, $push1hSend);
            NotifiedUser::create([
                'patient_id' => $patientId,
                'appointment_id' => $appointmentId,
                'push24h_sent' => $push24hSend,
                'push1h_sent' => $push1hSend
            ]);
        }        
    }
}
