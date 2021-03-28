<?php

namespace App\Http\Requests;

use App\Interfaces\ScheduleServiceInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointment extends FormRequest
{
    private $scheduleService;

    public function __construct(ScheduleServiceInterface $scheduleService) 
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required',
            'specialty_id' => 'exists:specialties,id',
            'doctor_id' => 'exists:users,id',
            'scheduled_date' => 'required',
            'scheduled_time' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'scheduled_time.required' => 'Por favor, seleccione una hora válida para registrar su cita',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date = $this->input('scheduled_date');
            $doctorId = $this->input('doctor_id');
            $scheduledTime = $this->input('scheduled_time');

            if (!$date || !$doctorId || !$scheduledTime) {
                return;
            }

            $start = new Carbon($scheduledTime);

            if (!$this->scheduleService->isAvailableInterval($date, $doctorId, $start)) {
                $validator->errors()->add('availableHour', 'La hora seleccionada ya se encuentra rservada por otro paciente');
            }
        });
    }
}
