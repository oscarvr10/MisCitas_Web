@extends('layouts.panel')

@section('content')

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">{{ __('Inicio') }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                {{ __('¡Bienvenido '.Auth::user()->name. '! Selecciona una opción del menú lateral') }}
            </div>
        </div>
    </div>
@if (auth()->user()->role == 'admin')
    <div class="col-xl-6 mb-5 mb-xl-0">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Notificaciones</h6>
                        <h2 class="mb-0">Enviar notificación a todos los usuarios</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session('notification'))
                <div class="alert alert-success" role="alert">
                    {{ session('notification') }}
                </div>
                @endif
                <form action="{{ url('/fcm/send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input value="{{ config('app.name') }}" class="form-control" type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="body">Mensaje</label>
                        <textarea class="form-control" name="body" id="body" cols="30" rows="3" required></textarea>
                    </div>
                    <button class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>    
    <div class="col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Total de Citas</h6>
                        <h2 class="mb-0">Según el dia de la semana</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Chart -->
                <div class="chart">
                    <canvas id="chart-orders" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
@endsection