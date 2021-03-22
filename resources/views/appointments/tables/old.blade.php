<div class="table-responsive">
    <!-- Projects table -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">Especialidad</th>
                @if ($role == 'patient')
                <th scope="col">Médico</th>
                @elseif ($role == 'doctor')
                <th scope="col">Paciente</th>
                @endif
                <th scope="col">Estado</th>
                <th scope="col">Fecha</th>
                <th scope="col">Hora</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($oldAppointments as $appointment)
            <tr>
                <th scope="row">
                    {{ $appointment->specialty->name }}
                </th>
                @if ($role == 'patient')
                <td>
                    {{ $appointment->doctor->name }}
                </td>
                @elseif ($role == 'doctor')
                <td>
                    {{ $appointment->patient->name }}
                </td>
                @endif
                <td>
                    {{ $appointment->status }}
                </td>
                <td>
                    {{ $appointment->scheduled_date }}
                </td>
                <td>
                    {{ $appointment->scheduled_time_12 }}
                </td>
                <td>
                    <a href="{{ url('/appointments/'.$appointment->id)}}" class="btn btn-sm btn-default">
                        Ver
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card-body">
    {{ $oldAppointments->links() }}
</div>