<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifiedUser extends Model
{
    protected $fillable = [
        'patient_id',
        'appointment_id',
        'push24h_sent',
        'push1h_sent'
    ];

     // 1 appointment->notifiedUser 1
     public function appointment()
     {
         return $this->belongsTo(Appointment::class);
     }

     // N patient->notifiedUser 1
     public function patient()
     {
         return $this->belongsToMany(User::class);
     }
}
