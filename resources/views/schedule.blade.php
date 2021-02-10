@extends('layouts.panel')

@section('content')


<form action="{{ url('/schedule') }}" method="POST">
    @csrf
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Gestionar Horario</h3>
                </div>
                <div class="col text-right">
                    <button type="submit" class="btn btn-sm btn-success">
                        Guardar cambios
                    </a>
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
        @if (session('errors'))
            <div class="card-body">
                <div class="alert alert-danger" role="alert">
                    Los cambios se han guardado pero tomar en cuenta que:
                    <ul>
                    @foreach (session('errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="table-responsive">
            <!-- Projects table -->
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Dia</th>
                        <th scope="col">Activo</th>
                        <th scope="col">Turno Matutino</th>
                        <th scope="col">Turno Vespertino</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $key => $day)
                        <tr>
                            <th>{{ $day }}</th>
                            <td>
                                <label class="custom-toggle">
                                    <input type="checkbox" name="active[]" 
                                        @if(count($workDays) > 0 && $workDays[$key]->active) checked @endif 
                                        value="{{ $key }}">
                                    <span class="custom-toggle-slider rounded-circle"></span>
                                </label>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <select class="form-control" name="morning_start[]">
                                            @foreach ($morning_hours as $hour)
                                                <option value="{{ $hour['value'] }}" @if(count($workDays) > 0 && $hour['data'].' AM' == $workDays[$key]->morning_start) selected @endif>
                                                    {{ $hour['data'] }} AM
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" name="morning_end[]">                                
                                            @foreach ($morning_hours as $hour)
                                                <option value="{{ $hour['value'] }}" @if(count($workDays) > 0 && $hour['data'].' AM' == $workDays[$key]->morning_end) selected @endif>
                                                    {{ $hour['data'] }} AM
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <select class="form-control" name="afternoon_start[]">
                                            @foreach ($afternoon_hours as $hour)
                                                <option value="{{ $hour['value'] }}" @if(count($workDays) > 0 && $hour['data'].' PM' == $workDays[$key]->afternoon_start) selected @endif>
                                                    {{ $hour['data'] }} PM
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" name="afternoon_end[]">                                
                                            @foreach ($afternoon_hours as $hour)
                                                <option value="{{ $hour['value'] }}" @if(count($workDays) > 0 && $hour['data'].' PM' == $workDays[$key]->afternoon_end) selected @endif>
                                                    {{ $hour['data'] }} PM
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>                            
                            </td>
                        </tr>    
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@endsection