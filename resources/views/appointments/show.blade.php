@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Cita #{{ $appointment->id }}</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul>
            @if ($role == 'patient' || $role == 'admin')
            <li>
                <strong>Médico: </strong>{{ $appointment->doctor->name }}
            </li>
            @endif 
            @if ($role=='doctor' || $role == 'admin') 
            <li>
            <strong>Paciente: </strong>{{ $appointment->patient->name }}
            </li>
            @endif 
            <li>
                <strong>Especialidad: </strong>{{ $appointment->specialty->name }}
            </li>
            <li>
                <strong>Tipo: </strong>{{ $appointment->type }}
            </li>
            <li>
                <strong>Fecha: </strong>{{ $appointment->scheduled_date }}
            </li>
            <li>
                <strong>Hora: </strong>{{ $appointment->scheduled_time }}
            </li>
            <li>
                <strong>Estado: </strong>
                @if( $appointment->status == 'Cancelada')
                <span class="badge badge-danger">{{ $appointment->status }}</span>
                @elseif( $appointment->status == 'Reservada')
                <span class="badge badge-warning">{{ $appointment->status }}</span>
                @else
                <span class="badge badge-success">{{ $appointment->status }}</span>
                @endif

            </li>
        </ul>
        @if( $appointment->status == 'Cancelada')
        <div class="alert alert-warning">
            <p>Acerca de la Cancelación: </p>
            @if ($appointment->cancellation)
            <li>
                <strong>Fecha de Cancelación: </strong>{{ $appointment->cancellation->created_at }}
            </li>
            <li>
                <strong>¿Quien canceló la cita?: </strong>
                @if (auth()->id() == $appointment->cancellation->cancelled_by_id)
                    Tú
                @elseif ($appointment->cancellation->cancelled_by->role == 'admin')
                    Administrador
                @else
                    {{ $appointment->cancellation->cancelled_by->name }}
                @endif
            </li>
            <li>
                <strong>Motivo de Cancelación: </strong>{{ $appointment->cancellation->justification }}
            </li>
            @else
            <br />
            <p>Esta cita fue cancelada antes de su confirmación.</p>
            @endif
        </div>
        @endif
        <a href="{{ url('/appointments') }}" class="btn btn-default">
            Volver
        </a>
    </div>
</div>
@endsection