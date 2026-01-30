@extends('layouts.app')

@section('title', 'Dashboard - Bomberos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
            <p class="text-muted">Bienvenido al sistema de gestión de bomberos</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card users">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>{{ $totalUsers }}</h3>
                        <p class="mb-0">Total de Usuarios</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card companies">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>{{ $totalCompanies }}</h3>
                        <p class="mb-0">Compañías</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>24/7</h3>
                        <p class="mb-0">Servicio Activo</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- En la sección de estadísticas, agregar una nueva tarjeta: -->
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>{{ $totalAdmins }}</h3>
                        <p class="mb-0">Administradores</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- En la sección de acciones rápidas, condicionar según rol: -->
        @if(auth()->user()->is_admin)
        <div class="col-md-3 mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-bomberos w-100">
                <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
            </a>
        </div>
        @endif

        <!-- En la información del sistema, mostrar el rol: -->
        <div class="alert alert-info mt-3">
            <i class="bi bi-shield-check me-2"></i>
            <small>
                Tu rol actual: 
                <strong>{{ auth()->user()->is_admin ? 'Administrador' : 'Usuario' }}</strong>
                @if(auth()->user()->is_admin)
                    (Tienes acceso completo al sistema)
                @else
                    (Solo puedes ver información de tu compañía: {{ auth()->user()->compania }})
                @endif
            </small>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('users.create') }}" class="btn btn-bomberos w-100">
                                <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100">
                                <i class="bi bi-printer me-2"></i>Reportes
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-success w-100">
                                <i class="bi bi-file-earmark-text me-2"></i>Documentos
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-warning w-100">
                                <i class="bi bi-bell me-2"></i>Alertas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del sistema -->
    <div class="row">
        <div class="col-12">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Estado del Sistema</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Autenticación: <strong>Activa</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Base de datos: <strong>Conectada</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Usuarios activos: <strong>{{ $totalUsers }}</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Última actividad</h6>
                            <p>Tu sesión está activa desde: <strong>{{ date('H:i:s') }}</strong></p>
                            <p>Fecha actual: <strong>{{ date('d/m/Y') }}</strong></p>
                            <div class="alert alert-info mt-3">
                                <small>
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    Sistema desarrollado para la Compañía de Bomberos
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection