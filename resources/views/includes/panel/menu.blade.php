<!-- Navigation -->
<h6 class="navbar-heading text-muted">
    @if (auth()->user()->role == 'admin')   
        Gestionar datos
    @else
        Menú
    @endif
</h6>
<ul class="navbar-nav">
    @if (auth()->user()->role == 'admin')     
        <li class="nav-item">
            <a class="nav-link" href="/home">
                <i class="ni ni-tv-2 text-danger"></i> Inicio
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/specialties">
                <i class="ni ni-planet text-blue"></i> Especialidades
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/doctors">
                <i class="ni ni-single-02 text-red"></i> Médicos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/patients">
                <i class="ni ni-satisfied text-info"></i> Pacientes
            </a>
        </li>        
    @elseif (auth()->user()->role == 'doctor')
        <li class="nav-item">
            <a class="nav-link" href="/home">
                <i class="ni ni-calendar-grid-58 text-danger"></i> Gestionar Horario
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/doctors">
                <i class="ni ni-time-alarm text-primary"></i> Mis Citas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/patients">
                <i class="ni ni-satisfied  text-info"></i> Mis Pacientes
            </a>
        </li>    
    @else {{-- patient --}}
        <li class="nav-item">
            <a class="nav-link" href="/home">
                <i class="ni ni-laptop text-danger"></i> Reservar Cita           
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/doctors">
                <i class="ni ni-time-alarm text-primary"></i> Mis Citas
            </a>
        </li>    
    @endif
    <li class="nav-item">
        <a class="nav-link" href="" onclick="event.preventDefault(); document.getElementById('formLogout').submit();">
            <i class="ni ni-user-run"></i> Cerrar sesión
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
        <a class="nav-link" href="#">
            <i class="ni ni-collection text-purple"></i> Frecuencia de citas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="ni ni-chart-bar-32 text-green"></i> Médicos más activos
        </a>
    </li>
</ul>
@endif