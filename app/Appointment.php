<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Appointment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'specialty_id',
        'doctor_id',
        'patient_id',
        'scheduled_date',
        'scheduled_time',
        'type',
    ];

    protected $hidden = [
        'specialty_id',
        'doctor_id', 
        'scheduled_time'
    ];

    protected $appends = [
        'scheduled_time_12',
    ];

    // N appointment->specialty 1
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // N appointment->doctor 1
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }

    // N appointment->patient 1
    public function patient()
    {
        return $this->belongsTo(User::class);
    }

    // 1 appointment->cancelled_appointment 1/0
    public function cancellation()
    {
        return $this->hasOne(CancelledAppointment::class);
    }

    // accesor
    // $appointment->scheduled_time_12

    public function getScheduledTime12Attribute()
    {
        return (new Carbon($this->scheduled_time))->format('g:i A');
    }

    static public function createForPatient(Request $request, $patientId) 
    {
        $data = $request->only([
            'description',
            'specialty_id',
            'doctor_id',
            'patient_id',
            'scheduled_date',
            'scheduled_time',
            'type'
        ]);
        $data['patient_id'] = $patientId;

        // right time format
        $carbonTime = Carbon::createFromFormat('g:i A',  $data['scheduled_time']);
        $data['scheduled_time'] = $carbonTime->format('H:i:s');

        return self::create($data);
    }

    public function sendPushNotification($message)
    {
        return fcm()
            ->to($this->device_token) // $recipients must an array
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'title' => config('app.name'),
                'body' => $message
            ])
            ->send();
    }
}
