@extends('layouts.app')

@section('title', 'Detalle de Partes - ' . ($user->name ?? 'Usuario'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><i class="bi bi-journal-text me-2"></i>Partes de {{ $user->name }} {{ $user->apellidos }}</h2>
                <p class="text-muted">DNI: {{ $user->dni }} • Compañía: {{ $user->compania }}</p>
            </div>
            <div>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Volver a Reportes</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Partes - Emergencias Médicas ({{ $emergencias->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($emergencias->isEmpty())
                        <p class="text-muted">No hay partes de emergencias médicas para este usuario.</p>
                    @else
                        @foreach($emergencias as $parte)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Parte #{{ $parte->id }}</strong>
                                        <div class="small text-muted">Fecha: {{ $parte->fecha ? $parte->fecha->format('d/m/Y') : '-' }} • Hora salida: {{ $parte->hora_salida ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ route('partes-emergencias.show', $parte) }}" target="_blank" class="btn btn-outline-primary btn-sm">Ver detalle</a>
                                    </div>
                                </div>
                                <div class="mt-2 small">
                                    Tipo de lugar: {{ $parte->tipo_lugar ?? '-' }}<br>
                                    Traslado a: {{ $parte->traslado_a ?? '-' }}<br>
                                    Nombre afectado: {{ $parte->nombre_afectado ?? '-' }} (DNI: {{ $parte->dni_afectado ?? '-' }})
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Partes - Incendios ({{ $incendios->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($incendios->isEmpty())
                        <p class="text-muted">No hay partes de incendios para este usuario.</p>
                    @else
                        @foreach($incendios as $parte)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Parte #{{ $parte->id }}</strong>
                                        <div class="small text-muted">Fecha: {{ $parte->fecha ? $parte->fecha->format('d/m/Y') : '-' }} • Hora salida: {{ $parte->hora_salida ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ route('partes-incendios.show', $parte) }}" target="_blank" class="btn btn-outline-primary btn-sm">Ver detalle</a>
                                    </div>
                                </div>
                                <div class="mt-2 small">
                                    Dirección: {{ Str::limit($parte->direccion_emergencia ?? $parte->direccion_incidente ?? '-', 120) }}
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
