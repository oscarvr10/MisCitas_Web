<!-- Navigation -->
<h6 class="navbar-heading text-muted">
    @if (auth()->user()->role == 'admin')   
        Gestionar datos
    @else
        Menú
    @endif
</h6>
<ul class="navbar-nav">
    @include('includes.panel.menu.'.auth()->user()->role)
    <li class="nav-item">
        <a class="nav-link" href="" onclick="event.preventDefault(); document.getElementById('formLogout').submit();">
            <i class="ni ni-button-power"></i> Cerrar sesión
        </a>
        <form id="formLogout" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>
</ul>
@if (auth()->user()->role == 'admin')
<!-- Divider -->
<hr class="my-3">
<!-- Heading -->
<h6 class="navbar-heading text-muted">Reportes</h6>
<!-- Navigation -->
<ul class="navbar-nav mb-md-3">
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/charts/appointments/line') }}">
            <i class="ni ni-collection text-purple"></i> Frecuencia de citas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/charts/appointments/column') }}">
            <i class="ni ni-chart-bar-32 text-green"></i> Médicos más activos
        </a>
    </li>
</ul>
@endif