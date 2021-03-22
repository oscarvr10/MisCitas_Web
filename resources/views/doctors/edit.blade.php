@extends('layouts.panel')

@section('styles')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Editar Médico</h3>
            </div>
            <div class="col text-right">
                <a href="{{ url('doctors') }}" class="btn btn-sm btn-default">
                    < Volver </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ url('doctors/'.$doctor->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre del Médico</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $doctor->name) }}">
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email) }}">
            </div>
            <div class="form-group">
                <label for="id_card">Id Identidad (CURP)</label>
                <input type="text" name="id_card" class="form-control" value="{{ old('id_card', $doctor->id_card) }}">
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $doctor->address) }}">
            </div>
            <div class="form-group">
                <label for="phone">Teléfono Celular</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $doctor->phone) }}">
            </div>
            <div class="form-group">
                <label for="phone">Contraseña</label>
                <input type="tel" name="password" class="form-control" value="">
                <p><em>Ingrese un valor sólo si desea cambiar la contraseña</em></p>
            </div>
            <div class="form-group">
                <label for="specialties">Especialidades</label>
                <select name="specialties[]" id="specialties" class="form-control selectpicker"
                    data-style="btn-outline-default" multiple title="Seleccione una o varias">
                    @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
<script>
    $(document).ready(() => {
        $('#specialties').selectpicker('val', @json($specialties_ids));
    });
    
</script>
@endsection