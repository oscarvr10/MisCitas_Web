<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelledAppointment extends Model
{
    public function cancelled_by() // related to cancelled_by_id field
    {
        // N cancellation-> user 1
        return $this->belongsTo(User::class);
    }
}
