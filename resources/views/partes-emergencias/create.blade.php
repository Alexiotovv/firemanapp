@extends('layouts.app')

@section('title', 'Nuevo Parte de Emergencia Médica')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-ambulance me-2"></i>Nuevo Parte de Emergencia Médica</h2>
            <p class="text-muted">Complete todos los campos del formulario</p>
        </div>
    </div>

    <form method="POST" action="{{ route('partes-emergencias.store') }}" id="parteEmergenciaForm">
        @csrf
        
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
                            <input type="date" class="form-control" name="fecha" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">N° DE PARTE</label>
                            <input type="text" class="form-control bg-light" value="AUTOGENERADO" disabled>
                            <small class="text-muted">Se generará automáticamente</small>
                        </div>
                    </div>
                </div>
                
                <h5 class="text-center mb-4">PARTE DE EMERGENCIA MÉDICA</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Salida *</label>
                            <input type="time" class="form-control" name="hora_salida" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Ingreso *</label>
                            <input type="time" class="form-control" name="hora_ingreso" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Piloto de Máquina *</label>
                    <input type="text" class="form-control" name="piloto_maquina" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">N° Velocímetro de Salida</label>
                            <input type="text" class="form-control" name="velocimetro_salida">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">N° Velocímetro de Retorno</label>
                            <input type="text" class="form-control" name="velocimetro_retorno">
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
                <div class="row">
                    @foreach($clasificaciones as $key => $label)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="clasificacion_servicio[]" 
                                   value="{{ $key }}" 
                                   id="clasificacion_{{ $key }}">
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
                    <input type="text" class="form-control" name="tipo_lugar" placeholder="Ej: Domicilio, Vía pública, Centro comercial...">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Dirección del incidente *</label>
                    <textarea class="form-control" name="direccion_incidente" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Se trasladó a</label>
                    <input type="text" class="form-control" name="traslado_a" placeholder="Ej: Hospital Regional, Clínica...">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Persona Responsable</label>
                    <input type="text" class="form-control" name="persona_responsable">
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
                            <input type="text" class="form-control" name="nombre_afectado">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">DNI N°</label>
                            <input type="text" class="form-control" name="dni_afectado">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Edad</label>
                            <input type="number" class="form-control" name="edad_afectado" min="0" max="150">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Sexo</label>
                            <select class="form-control" name="sexo_afectado">
                                <option value="">-- Seleccione --</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
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
                            <input type="text" class="form-control" name="presion_arterial" placeholder="Ej: 120/80">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Pulso (x min)</label>
                            <input type="text" class="form-control" name="pulso" placeholder="Ej: 75">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Respiración (x min)</label>
                            <input type="text" class="form-control" name="respiracion" placeholder="Ej: 16">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Temperatura (°C)</label>
                            <input type="text" class="form-control" name="temperatura" placeholder="Ej: 36.5">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Consciente</label>
                            <input type="text" class="form-control" name="neurologico_consciente" placeholder="Ej: Sí, parcialmente...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Inconsciente</label>
                            <input type="text" class="form-control" name="neurologico_inconsciente" placeholder="Ej: No, completamente...">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pupilas</label>
                            <input type="text" class="form-control" name="pupilas" placeholder="Ej: Normales, dilatadas...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">SpO2</label>
                            <input type="text" class="form-control" name="spo2" placeholder="Ej: 98%">
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
                    <textarea class="form-control" name="lesiones_observadas" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Diagnóstico presuntivo *</label>
                    <textarea class="form-control" name="diagnostico_presuntivo" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tratamiento y trabajo efectuado *</label>
                    <textarea class="form-control" name="tratamiento_efectuado" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Material utilizado</label>
                    <textarea class="form-control" name="material_utilizado" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="3"></textarea>
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
                    <textarea class="form-control" name="unidades_cgbvp" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Unidades Policiales</label>
                    <textarea class="form-control" name="unidades_policiales" rows="2"></textarea>
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
                            <textarea class="form-control" name="concurrentes" rows="4" placeholder="Liste los concurrentes..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No concurrentes</label>
                            <textarea class="form-control" name="no_concurrentes" rows="4" placeholder="Liste los no concurrentes..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total concurrentes</label>
                            <input type="number" class="form-control" name="total_concurrentes" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total no concurrentes</label>
                            <input type="number" class="form-control" name="total_no_concurrentes" min="0" value="0">
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
                            <input type="text" class="form-control" name="firma_bombero_mando">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre y firma del bombero que confeccionó el parte *</label>
                            <input type="text" class="form-control" name="firma_bombero_confecciona" 
                                   value="{{ auth()->user()->name }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('partes-emergencias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Cancelar
                    </a>
                    
                    <div>
                        <button type="submit" name="action" value="save" class="btn btn-bomberos">
                            <i class="bi bi-check-circle me-2"></i>Guardar Parte
                        </button>
                        
                        @if(auth()->user()->is_admin)
                        <button type="submit" name="action" value="save_complete" class="btn btn-success">
                            <i class="bi bi-check-all me-2"></i>Guardar y Completar
                        </button>
                        @endif
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
        
        // Validar que la hora de ingreso sea mayor que la de salida
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