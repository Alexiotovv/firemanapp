@extends('layouts.app')

@section('title', 'Editar Parte de Emergencia Médica')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-ambulance me-2"></i>Editar Parte de Emergencia Médica</h2>
            <p class="text-muted">Modifique los campos necesarios</p>
        </div>
    </div>

    <form method="POST" action="{{ route('partes-emergencias.update', $partesEmergencia) }}" id="parteEmergenciaForm">
        @csrf
        @method('PUT')
        
        <!-- Encabezado -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-center">
                    CUERPO GENERAL DE BOMBEROS VOLUNTARIOS DEL PERÚ<br>
                    XI COMANDANCIA DEPARTAMENTAL LORETO<br>
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" 
                                   value="{{ $partesEmergencia->fecha->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">N° DE PARTE</label>
                            <input type="text" class="form-control bg-light" 
                                   value="{{ str_pad($partesEmergencia->numero_parte, 4, '0', STR_PAD_LEFT) }}" disabled>
                        </div>
                    </div>
                </div>
                
                <h5 class="text-center mb-4">PARTE DE EMERGENCIA MÉDICA</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Salida *</label>
                            <input type="time" class="form-control" name="hora_salida" 
                                   value="{{ $partesEmergencia->hora_salida }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Ingreso *</label>
                            <input type="time" class="form-control" name="hora_ingreso" 
                                   value="{{ $partesEmergencia->hora_ingreso }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Piloto de Máquina *</label>
                    <input type="text" class="form-control" name="piloto_maquina" 
                           value="{{ $partesEmergencia->piloto_maquina }}" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">N° Velocímetro de Salida</label>
                            <input type="text" class="form-control" name="velocimetro_salida" 
                                   value="{{ $partesEmergencia->velocimetro_salida }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">N° Velocímetro de Retorno</label>
                            <input type="text" class="form-control" name="velocimetro_retorno" 
                                   value="{{ $partesEmergencia->velocimetro_retorno }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clasificación del servicio -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">CLASIFICACIÓN DEL SERVICIO *</h5>
            </div>
            <div class="card-body">
                @php
                    $clasificacionesSeleccionadas = $partesEmergencia->clasificacion_servicio;
                    if (is_string($clasificacionesSeleccionadas)) {
                        $clasificacionesSeleccionadas = json_decode($clasificacionesSeleccionadas, true) ?? [];
                    }
                @endphp
                
                <div class="row">
                    @foreach($clasificaciones as $key => $label)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="clasificacion_servicio[]" 
                                   value="{{ $key }}" 
                                   id="clasificacion_{{ $key }}"
                                   {{ in_array($key, $clasificacionesSeleccionadas) ? 'checked' : '' }}>
                            <label class="form-check-label" for="clasificacion_{{ $key }}">
                                {{ $label }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Información del lugar y traslado -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>INFORMACIÓN DEL LUGAR Y TRASLADO</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Tipo de lugar</label>
                    <input type="text" class="form-control" name="tipo_lugar" 
                           value="{{ $partesEmergencia->tipo_lugar }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Dirección del incidente *</label>
                    <textarea class="form-control" name="direccion_incidente" rows="2" required>{{ $partesEmergencia->direccion_incidente }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Se trasladó a</label>
                    <input type="text" class="form-control" name="traslado_a" 
                           value="{{ $partesEmergencia->traslado_a }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Persona Responsable</label>
                    <input type="text" class="form-control" name="persona_responsable" 
                           value="{{ $partesEmergencia->persona_responsable }}">
                </div>
            </div>
        </div>

        <!-- Datos de la persona afectada -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-heart me-2"></i>PERSONA(S) AFECTADA(S)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre_afectado" 
                                   value="{{ $partesEmergencia->nombre_afectado }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">DNI N°</label>
                            <input type="text" class="form-control" name="dni_afectado" 
                                   value="{{ $partesEmergencia->dni_afectado }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Edad</label>
                            <input type="number" class="form-control" name="edad_afectado" 
                                   value="{{ $partesEmergencia->edad_afectado }}" min="0" max="150">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Sexo</label>
                            <select class="form-control" name="sexo_afectado">
                                <option value="">-- Seleccione --</option>
                                <option value="M" {{ $partesEmergencia->sexo_afectado == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ $partesEmergencia->sexo_afectado == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signos vitales -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>SIGNOS VITALES</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">P.A. (mmhg)</label>
                            <input type="text" class="form-control" name="presion_arterial" 
                                   value="{{ $partesEmergencia->presion_arterial }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Pulso (x min)</label>
                            <input type="text" class="form-control" name="pulso" 
                                   value="{{ $partesEmergencia->pulso }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Respiración (x min)</label>
                            <input type="text" class="form-control" name="respiracion" 
                                   value="{{ $partesEmergencia->respiracion }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Temperatura (°C)</label>
                            <input type="text" class="form-control" name="temperatura" 
                                   value="{{ $partesEmergencia->temperatura }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Consciente</label>
                            <input type="text" class="form-control" name="neurologico_consciente" 
                                   value="{{ $partesEmergencia->neurologico_consciente }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Inconsciente</label>
                            <input type="text" class="form-control" name="neurologico_inconsciente" 
                                   value="{{ $partesEmergencia->neurologico_inconsciente }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pupilas</label>
                            <input type="text" class="form-control" name="pupilas" 
                                   value="{{ $partesEmergencia->pupilas }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">SpO2</label>
                            <input type="text" class="form-control" name="spo2" 
                                   value="{{ $partesEmergencia->spo2 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información médica -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>INFORMACIÓN MÉDICA</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Lesiones observadas *</label>
                    <textarea class="form-control" name="lesiones_observadas" rows="3" required>{{ $partesEmergencia->lesiones_observadas }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Diagnóstico presuntivo *</label>
                    <textarea class="form-control" name="diagnostico_presuntivo" rows="3" required>{{ $partesEmergencia->diagnostico_presuntivo }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tratamiento y trabajo efectuado *</label>
                    <textarea class="form-control" name="tratamiento_efectuado" rows="3" required>{{ $partesEmergencia->tratamiento_efectuado }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Material utilizado</label>
                    <textarea class="form-control" name="material_utilizado" rows="2">{{ $partesEmergencia->material_utilizado }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="3">{{ $partesEmergencia->observaciones }}</textarea>
                </div>
            </div>
        </div>

        <!-- Unidades -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-truck me-2"></i>UNIDADES</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Unidades del CGBVP</label>
                    <textarea class="form-control" name="unidades_cgbvp" rows="2">{{ $partesEmergencia->unidades_cgbvp }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Unidades Policiales</label>
                    <textarea class="form-control" name="unidades_policiales" rows="2">{{ $partesEmergencia->unidades_policiales }}</textarea>
                </div>
            </div>
        </div>

        <!-- Concurrentes -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>CONCURRENTES</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Concurrentes</label>
                            <textarea class="form-control" name="concurrentes" rows="4">{{ $partesEmergencia->concurrentes }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No concurrentes</label>
                            <textarea class="form-control" name="no_concurrentes" rows="4">{{ $partesEmergencia->no_concurrentes }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total concurrentes</label>
                            <input type="number" class="form-control" name="total_concurrentes" 
                                   value="{{ $partesEmergencia->total_concurrentes }}" min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total no concurrentes</label>
                            <input type="number" class="form-control" name="total_no_concurrentes" 
                                   value="{{ $partesEmergencia->total_no_concurrentes }}" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Firmas -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pen me-2"></i>FIRMAS</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre y firma del bombero al mando de la operación</label>
                            <input type="text" class="form-control" name="firma_bombero_mando" 
                                   value="{{ $partesEmergencia->firma_bombero_mando }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre y firma del bombero que confeccionó el parte *</label>
                            <input type="text" class="form-control" name="firma_bombero_confecciona" 
                                   value="{{ $partesEmergencia->firma_bombero_confecciona }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado (solo para admin) -->
        @if(auth()->user()->is_admin)
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>ESTADO DEL PARTE</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="completado" 
                                   value="1" id="completado" {{ $partesEmergencia->completado ? 'checked' : '' }}>
                            <label class="form-check-label" for="completado">
                                Marcado como completado
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="aprobado" 
                                   value="1" id="aprobado" {{ $partesEmergencia->aprobado ? 'checked' : '' }}>
                            <label class="form-check-label" for="aprobado">
                                Aprobado
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Botones -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('partes-emergencias.show', $partesEmergencia) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Cancelar
                    </a>
                    
                    <div>
                        <button type="submit" class="btn btn-bomberos">
                            <i class="bi bi-check-circle me-2"></i>Actualizar Parte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validación de horas
    $('#parteEmergenciaForm').submit(function() {
        const horaSalida = $('input[name="hora_salida"]').val();
        const horaIngreso = $('input[name="hora_ingreso"]').val();
        
        if (horaSalida && horaIngreso && horaIngreso <= horaSalida) {
            alert('La hora de ingreso debe ser mayor que la hora de salida.');
            return false;
        }
        
        return true;
    });
    
    // Contar concurrentes automáticamente
    $('textarea[name="concurrentes"]').on('input', function() {
        const text = $(this).val();
        const lines = text.split('\n').filter(line => line.trim() !== '');
        $('input[name="total_concurrentes"]').val(lines.length);
    });
    
    $('textarea[name="no_concurrentes"]').on('input', function() {
        const text = $(this).val();
        const lines = text.split('\n').filter(line => line.trim() !== '');
        $('input[name="total_no_concurrentes"]').val(lines.length);
    });
});
</script>
@endsection