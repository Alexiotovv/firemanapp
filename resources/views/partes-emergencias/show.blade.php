@extends('layouts.app')

@section('title', 'Ver Parte de Emergencia')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="bi bi-ambulance me-2"></i>Parte de Emergencia #{{ str_pad($partesEmergencia->numero_parte, 4, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-muted">Fecha: {{ $partesEmergencia->fecha->format('d/m/Y') }}</p>
                </div>
                <div>
                    <a href="{{ route('partes-emergencias.imprimir', $partesEmergencia) }}" 
                       class="btn btn-info" target="_blank">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </a>
                    
                    @if((!$partesEmergencia->completado && $partesEmergencia->user_id == auth()->id()) || auth()->user()->is_admin)
                        <a href="{{ route('partes-emergencias.edit', $partesEmergencia) }}" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                    @endif
                    
                    <a href="{{ route('partes-emergencias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('components.alert')

    <!-- Estado del parte -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Estado:</strong>
                            @if($partesEmergencia->aprobado)
                                <span class="badge bg-success">Aprobado</span>
                            @elseif($partesEmergencia->completado)
                                <span class="badge bg-warning">Completado</span>
                            @else
                                <span class="badge bg-secondary">Pendiente</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Creado por:</strong> {{ $partesEmergencia->user->name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Fecha creación:</strong> {{ $partesEmergencia->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Clasificación:</strong>
                            @php
                                $clasificacionesSeleccionadas = $partesEmergencia->clasificacion_servicio;
                                if (is_string($clasificacionesSeleccionadas)) {
                                    $clasificacionesSeleccionadas = json_decode($clasificacionesSeleccionadas, true) ?? [];
                                }
                            @endphp
                            @foreach($clasificacionesSeleccionadas as $clasificacion)
                                <span class="badge bg-info">{{ $clasificaciones[$clasificacion] ?? $clasificacion }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    @if(auth()->user()->is_admin)
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('partes-emergencias.aprobar', $partesEmergencia) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Aprobar Parte
                                </button>
                            </form>
                            
                            @if(!$partesEmergencia->completado)
                            <form action="{{ route('partes-emergencias.completar', $partesEmergencia) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">
                                    <i class="bi bi-check-square me-1"></i>Marcar como Completado
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido del parte -->
    <div class="card card-bomberos mb-4">
        <div class="card-body">
            <!-- Información básica -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <strong>Hora Salida:</strong> {{ $partesEmergencia->hora_salida }}
                </div>
                <div class="col-md-4">
                    <strong>Hora Ingreso:</strong> {{ $partesEmergencia->hora_ingreso }}
                </div>
                <div class="col-md-4">
                    <strong>Piloto de Máquina:</strong> {{ $partesEmergencia->piloto_maquina }}
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Velocímetro Salida:</strong> {{ $partesEmergencia->velocimetro_salida ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Velocímetro Retorno:</strong> {{ $partesEmergencia->velocimetro_retorno ?? 'N/A' }}
                </div>
            </div>
            
            <!-- Lugar y traslado -->
            <div class="mb-4">
                <strong>Dirección del incidente:</strong><br>
                {{ $partesEmergencia->direccion_incidente }}
            </div>
            
            @if($partesEmergencia->tipo_lugar)
            <div class="mb-4">
                <strong>Tipo de lugar:</strong> {{ $partesEmergencia->tipo_lugar }}
            </div>
            @endif
            
            @if($partesEmergencia->traslado_a)
            <div class="mb-4">
                <strong>Se trasladó a:</strong> {{ $partesEmergencia->traslado_a }}
            </div>
            @endif
            
            @if($partesEmergencia->persona_responsable)
            <div class="mb-4">
                <strong>Persona Responsable:</strong> {{ $partesEmergencia->persona_responsable }}
            </div>
            @endif
            
            <!-- Persona afectada -->
            @if($partesEmergencia->nombre_afectado)
            <div class="mb-4">
                <h5>Persona Afectada:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre:</strong> {{ $partesEmergencia->nombre_afectado }}
                    </div>
                    <div class="col-md-3">
                        <strong>DNI:</strong> {{ $partesEmergencia->dni_afectado ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Edad:</strong> {{ $partesEmergencia->edad_afectado ?? 'N/A' }}
                    </div>
                </div>
                @if($partesEmergencia->sexo_afectado)
                <div class="mt-2">
                    <strong>Sexo:</strong> {{ $partesEmergencia->sexo_afectado == 'M' ? 'Masculino' : 'Femenino' }}
                </div>
                @endif
            </div>
            @endif
            
            <!-- Signos vitales -->
            <div class="mb-4">
                <h5>Signos Vitales:</h5>
                <div class="row">
                    <div class="col-md-3">
                        <strong>P.A.:</strong> {{ $partesEmergencia->presion_arterial ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Pulso:</strong> {{ $partesEmergencia->pulso ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Respiración:</strong> {{ $partesEmergencia->respiracion ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Temperatura:</strong> {{ $partesEmergencia->temperatura ?? 'N/A' }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Consciente:</strong> {{ $partesEmergencia->neurologico_consciente ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Inconsciente:</strong> {{ $partesEmergencia->neurologico_inconsciente ?? 'N/A' }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Pupilas:</strong> {{ $partesEmergencia->pupilas ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>SpO2:</strong> {{ $partesEmergencia->spo2 ?? 'N/A' }}
                    </div>
                </div>
            </div>
            
            <!-- Información médica -->
            <div class="mb-4">
                <h5>Información Médica:</h5>
                <div class="mb-3">
                    <strong>Lesiones observadas:</strong><br>
                    {{ $partesEmergencia->lesiones_observadas }}
                </div>
                
                <div class="mb-3">
                    <strong>Diagnóstico presuntivo:</strong><br>
                    {{ $partesEmergencia->diagnostico_presuntivo }}
                </div>
                
                <div class="mb-3">
                    <strong>Tratamiento y trabajo efectuado:</strong><br>
                    {{ $partesEmergencia->tratamiento_efectuado }}
                </div>
                
                @if($partesEmergencia->material_utilizado)
                <div class="mb-3">
                    <strong>Material utilizado:</strong><br>
                    {{ $partesEmergencia->material_utilizado }}
                </div>
                @endif
                
                @if($partesEmergencia->observaciones)
                <div class="mb-3">
                    <strong>Observaciones:</strong><br>
                    {{ $partesEmergencia->observaciones }}
                </div>
                @endif
            </div>
            
            <!-- Unidades -->
            @if($partesEmergencia->unidades_cgbvp || $partesEmergencia->unidades_policiales)
            <div class="mb-4">
                <h5>Unidades:</h5>
                @if($partesEmergencia->unidades_cgbvp)
                <div class="mb-2">
                    <strong>Unidades del CGBVP:</strong><br>
                    {{ $partesEmergencia->unidades_cgbvp }}
                </div>
                @endif
                
                @if($partesEmergencia->unidades_policiales)
                <div class="mb-2">
                    <strong>Unidades Policiales:</strong><br>
                    {{ $partesEmergencia->unidades_policiales }}
                </div>
                @endif
            </div>
            @endif
            
            <!-- Concurrentes -->
            @if($partesEmergencia->concurrentes || $partesEmergencia->no_concurrentes)
            <div class="mb-4">
                <h5>Concurrentes:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Concurrentes:</strong><br>
                        @if($partesEmergencia->concurrentes)
                            @php
                                $concurrentes = explode("\n", $partesEmergencia->concurrentes);
                            @endphp
                            @foreach($concurrentes as $concurrente)
                                @if(trim($concurrente))
                                    <div>{{ $concurrente }}</div>
                                @endif
                            @endforeach
                        @else
                            Ninguno
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>No Concurrentes:</strong><br>
                        @if($partesEmergencia->no_concurrentes)
                            @php
                                $noConcurrentes = explode("\n", $partesEmergencia->no_concurrentes);
                            @endphp
                            @foreach($noConcurrentes as $noConcurrente)
                                @if(trim($noConcurrente))
                                    <div>{{ $noConcurrente }}</div>
                                @endif
                            @endforeach
                        @else
                            Ninguno
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong>Total concurrentes:</strong> {{ $partesEmergencia->total_concurrentes }}
                    </div>
                    <div class="col-md-6">
                        <strong>Total no concurrentes:</strong> {{ $partesEmergencia->total_no_concurrentes }}
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Firmas -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <strong>Bombero al mando de la operación:</strong><br>
                    {{ $partesEmergencia->firma_bombero_mando ?? 'No especificado' }}
                </div>
                <div class="col-md-6">
                    <strong>Bombero que confeccionó el parte:</strong><br>
                    {{ $partesEmergencia->firma_bombero_confecciona }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection