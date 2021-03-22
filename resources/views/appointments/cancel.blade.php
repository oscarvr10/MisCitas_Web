@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Cancelar Cita</h3>
            </div>
        </div>
    </div>
    @if (session('notification'))
    <div class="card-body">
        <div class="alert alert-success" role="alert">
            {{ session('notification') }}
        </div>
    </div>
    @endif
    <div class="card-body">
        @if ($role == 'patient')
        <p>Estás a punto de cancelar tu cita reservada con el médico {{ $appointment->doctor->name }} (especialidad: {{ $appointment->specialty->name }})
            para el dia {{ $appointment->scheduled_date }} a las {{ $appointment->scheduled_time_12 }}.</p>
        @elseif($role == 'doctor')
        <p>Estás a punto de cancelar tu cita con el paciente {{ $appointment->patient->name }} (especialidad: {{ $appointment->specialty->name }})
            para el dia {{ $appointment->scheduled_date }} a las {{ $appointment->scheduled_time_12 }}.</p>
        @else
        <p>Estás a punto de cancelar la cita reservada por el paciente {{ $appointment->patient->name }} para ser atendido por el médico {{ $appointment->doctor->name }} (especialidad: {{ $appointment->specialty->name }})
            el dia {{ $appointment->scheduled_date }} a las {{ $appointment->scheduled_time_12 }}.</p>
        @endif
        <form action="{{ url('/appointments/'.$appointment->id.'/cancel') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="justification">Por favor, ingrese el motivo de la cancelación</label>
                <textarea name="justification" class="form-control" id="justification" rows="5"></textarea>
            </div>
            <button class="btn btn-danger" type="submit">Cancelar Cita</button>
            <a class="btn btn-default" href="{{ url('/appointments') }}">
                Volver al listado
            </a>
        </form>
    </div>

</div>
@endsection