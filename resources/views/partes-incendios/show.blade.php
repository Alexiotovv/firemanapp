@extends('layouts.app')

@section('title', 'Ver Parte de Incendio')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="bi bi-fire me-2"></i>Parte de Incendio #{{ str_pad($partesIncendio->numero_parte, 4, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-muted">Fecha: {{ $partesIncendio->fecha->format('d/m/Y') }}</p>
                </div>
                <div>
                    <a href="{{ route('partes-incendios.imprimir', $partesIncendio) }}" 
                       class="btn btn-info" target="_blank">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </a>
                    
                    @if((!$partesIncendio->completado && $partesIncendio->user_id == auth()->id()) || auth()->user()->is_admin)
                        <a href="{{ route('partes-incendios.edit', $partesIncendio) }}" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                    @endif
                    
                    <a href="{{ route('partes-incendios.index') }}" class="btn btn-secondary">
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
                            @if($partesIncendio->aprobado)
                                <span class="badge bg-success">Aprobado</span>
                            @elseif($partesIncendio->completado)
                                <span class="badge bg-warning">Completado</span>
                            @else
                                <span class="badge bg-secondary">Pendiente</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Clasificación:</strong>
                            <span class="badge bg-info">{{ $partesIncendio->clasificacion_servicio_texto }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Creado por:</strong> {{ $partesIncendio->user->name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Fecha creación:</strong> {{ $partesIncendio->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    @if(auth()->user()->is_admin)
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('partes-incendios.aprobar', $partesIncendio) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Aprobar Parte
                                </button>
                            </form>
                            
                            @if(!$partesIncendio->completado)
                            <form action="{{ route('partes-incendios.completar', $partesIncendio) }}" 
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
            <!-- Mostrar todos los campos del parte -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Hora Salida:</strong> {{ $partesIncendio->hora_salida }}
                </div>
                <div class="col-md-4">
                    <strong>Hora Retorno:</strong> {{ $partesIncendio->hora_retorno }}
                </div>
                <div class="col-md-4">
                    <strong>Persona que recepcionó:</strong> {{ $partesIncendio->persona_recepciono_aviso }}
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Máquina:</strong> {{ $partesIncendio->maquina }}
                </div>
                <div class="col-md-4">
                    <strong>Al mando:</strong> {{ $partesIncendio->mando_maquina }}
                </div>
                <div class="col-md-4">
                    <strong>Piloto:</strong> {{ $partesIncendio->piloto_maquina }}
                </div>
            </div>
            
            <div class="mb-3">
                <strong>Dirección:</strong><br>
                {{ $partesIncendio->direccion_emergencia }}
            </div>
            
            <div class="mb-3">
                <strong>Causa:</strong><br>
                {{ $partesIncendio->causa_emergencia }}
            </div>
            
            @if($partesIncendio->clase_inmueble || $partesIncendio->tipo_construccion)
            <div class="mb-3">
                <strong>Detalles inmueble:</strong><br>
                Clase: {{ $partesIncendio->clase_inmueble }} - 
                Tipo: {{ $partesIncendio->tipo_construccion }}
            </div>
            @endif
            
            @if($partesIncendio->vehiculo)
            <div class="mb-3">
                <strong>Vehículo:</strong><br>
                {{ $partesIncendio->vehiculo }} {{ $partesIncendio->marca_vehiculo }} 
                Modelo: {{ $partesIncendio->modelo_vehiculo }} 
                Placa: {{ $partesIncendio->placa_vehiculo }} 
                Color: {{ $partesIncendio->color_vehiculo }}
            </div>
            @endif
            
            @if($partesIncendio->danos_vehiculo)
            <div class="mb-3">
                <strong>Daños observados:</strong><br>
                {{ $partesIncendio->danos_vehiculo }}
            </div>
            @endif
            
            <!-- Personas lesionadas -->
            @if($partesIncendio->personas_lesionadas)
            <div class="mb-3">
                <strong>Personas lesionadas:</strong>
                <ul class="list-group mt-2">
                    @foreach($partesIncendio->personas_lesionadas as $persona)
                    <li class="list-group-item">
                        {{ $persona['nombre'] ?? '' }} - 
                        DNI: {{ $persona['documento'] ?? '' }} - 
                        Lesiones: {{ $persona['lesiones'] ?? '' }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <div class="mb-3">
                <strong>Trabajo efectuado:</strong><br>
                {{ $partesIncendio->trabajo_efectuado }}
            </div>
            
            <div class="mb-3">
                <strong>Material utilizado:</strong><br>
                {{ $partesIncendio->material_utilizado }}
            </div>
            
            @if($partesIncendio->unidades_apoyo_cgbvp)
            <div class="mb-3">
                <strong>Unidades de apoyo CGBVP:</strong><br>
                {{ $partesIncendio->unidades_apoyo_cgbvp }}
            </div>
            @endif
            
            @if($partesIncendio->mando_operaciones_cgbvp)
            <div class="mb-3">
                <strong>Al mando operaciones CGBVP:</strong> {{ $partesIncendio->mando_operaciones_cgbvp }}
            </div>
            @endif
            
            @if($partesIncendio->unidades_policiales)
            <div class="mb-3">
                <strong>Unidades policiales:</strong><br>
                {{ $partesIncendio->unidades_policiales }}
            </div>
            @endif
            
            <!-- Personal asistente -->
            @if($partesIncendio->personal_asistente)
            <div class="mb-3">
                <strong>Personal asistente:</strong>
                <div class="row mt-2">
                    @foreach($partesIncendio->personal_asistente as $asistente)
                    <div class="col-md-6 mb-2">{{ $asistente }}</div>
                    @endforeach
                </div>
            </div>
            @endif
            
            @if($partesIncendio->personal_bombero_accidentado)
            <div class="mb-3">
                <strong>Personal bombero accidentado:</strong><br>
                {{ $partesIncendio->personal_bombero_accidentado }}
            </div>
            @endif
            
            @if($partesIncendio->personal_asistente_cuartel)
            <div class="mb-3">
                <strong>Personal asistente en el cuartel:</strong><br>
                {{ $partesIncendio->personal_asistente_cuartel }}
            </div>
            @endif
            
            @if($partesIncendio->damnificados_incendios)
            <div class="mb-3">
                <strong>Damnificados por incendios:</strong><br>
                {{ $partesIncendio->damnificados_incendios }}
            </div>
            @endif
            
            @if($partesIncendio->observaciones)
            <div class="mb-3">
                <strong>Observaciones:</strong><br>
                {{ $partesIncendio->observaciones }}
            </div>
            @endif
            
            <!-- Firmas -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <strong>Bombero al mando:</strong><br>
                    {{ $partesIncendio->firma_bombero_mando }}
                </div>
                <div class="col-md-4">
                    <strong>Bombero que confecciona:</strong><br>
                    {{ $partesIncendio->firma_bombero_confecciona }}
                </div>
                <div class="col-md-4">
                    <strong>Segundo jefe de compañía:</strong><br>
                    {{ $partesIncendio->firma_segundo_jefe }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection