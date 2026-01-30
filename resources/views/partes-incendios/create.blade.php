@extends('layouts.app')

@section('title', 'Nuevo Parte de Incendio')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-fire me-2"></i>Nuevo Parte de Incendio</h2>
            <p class="text-muted">Complete todos los campos del formulario</p>
        </div>
    </div>

    <form method="POST" action="{{ route('partes-incendios.store') }}" id="parteForm">
        @csrf
        
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-card-checklist me-2"></i>
                    CUERPO GENERAL DE BOMBEROS VOLUNTARIOS DEL PERÚ<br>
                    XI COMANDANCIA DEPARTAMENTAL DE LORETO<br>
                    Compañía de Bomberos "BELEN" Nº 41
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">N° DE PARTE</label>
                            <input type="text" class="form-control bg-light" value="AUTOGENERADO" disabled>
                            <small class="text-muted">Se generará automáticamente</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">FECHA</label>
                            <input type="date" class="form-control" name="fecha" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CLASIFICACIÓN DEL SERVICIO -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-tags me-2"></i>CLASIFICACIÓN DEL SERVICIO</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Seleccione Clasificación *</label>
                            <select class="form-control" name="clasificacion_servicio" id="clasificacion_servicio" required>
                                <option value="">-- Seleccione --</option>
                                <option value="1">1. INCENDIOS</option>
                                <option value="2">2. FUGA DE GAS</option>
                                <option value="3">3. EMERGENCIAS MEDICAS</option>
                                <option value="4">4. RESCATES</option>
                                <option value="5">5. DERRAMES</option>
                                <option value="6">6. CORTO CIRCUITO</option>
                                <option value="7">7. SERVICIO ESPECIAL</option>
                                <option value="8">8. ACCIDENTE VEHICULAR</option>
                                <option value="9">9. FALSA ALARMA</option>
                                <option value="10">10. VARIOS</option>
                                <option value="11">11. COMISIONES</option>
                                <option value="12">12. AMAGO DE INCENDIO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Clasificación Detallada</label>
                            <input type="text" class="form-control" name="clasificacion_servicio_texto" 
                                   placeholder="Especifique la clasificación del servicio...">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">HORA DE SALIDA *</label>
                            <input type="time" class="form-control" name="hora_salida" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">HORA DE RETORNO *</label>
                            <input type="time" class="form-control" name="hora_retorno" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">PERSONA QUE RECEPCIONÓ EL AVISO *</label>
                            <input type="text" class="form-control" name="persona_recepciono_aviso" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DE LA MÁQUINA -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-truck me-2"></i>INFORMACIÓN DE LA MÁQUINA</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">MÁQUINA *</label>
                            <select class="form-control" name="maquina" required>
                                <option value="">-- Seleccione una máquina --</option>
                                <option value="M93-1">M93-1</option>
                                <option value="M93-5 ABC">M93-5 ABC</option>
                                <option value="ESC-93">ESC-93</option>
                                <option value="AUX-93">AUX-93</option>
                                <option value="RESLIG-93">RESLIG-93</option>
                                <option value="CIST-92">CIST-92</option>
                                <option value="AUX-92">AUX-92</option>
                                <option value="CIST-41">CIST-41</option>
                                <option value="M41-6">M41-6</option>
                                <option value="AMB-41">AMB-41</option>
                                <option value="RESLIG-41">RESLIG-41</option>
                                <option value="CIST-94">CIST-94</option>
                                <option value="AUX-94">AUX-94</option>
                                <option value="CIST-175">CIST-175</option>
                                <option value="AMB-175">AMB-175</option>
                                <option value="RESLIG-175">RESLIG-175</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">AL MANDO DE LA MÁQUINA *</label>
                            <input type="text" class="form-control" name="mando_maquina" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">PILOTO DE LA MÁQUINA *</label>
                            <input type="text" class="form-control" name="piloto_maquina" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DIRECCIÓN Y CAUSA -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>UBICACIÓN Y CAUSA</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">DIRECCIÓN DE LA EMERGENCIA *</label>
                    <textarea class="form-control" name="direccion_emergencia" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">POSIBLE CAUSA DE LA EMERGENCIA *</label>
                    <textarea class="form-control" name="causa_emergencia" rows="3" required></textarea>
                </div>
            </div>
        </div>

        <!-- DETALLES DEL INMUEBLE/VEHÍCULO -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>DETALLES DEL INMUEBLE / VEHÍCULO</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">CLASE DE INMUEBLE</label>
                        <input type="text" class="form-control" name="clase_inmueble">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">TIPO DE CONSTRUCCIÓN</label>
                        <input type="text" class="form-control" name="tipo_construccion">
                    </div>
                </div>
                
                <h6 class="mt-4 mb-3">INFORMACIÓN DEL VEHÍCULO (si aplica)</h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">VEHÍCULO</label>
                        <input type="text" class="form-control" name="vehiculo">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">MODELO</label>
                        <input type="text" class="form-control" name="modelo_vehiculo">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">PLACA</label>
                        <input type="text" class="form-control" name="placa_vehiculo">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">COLOR</label>
                        <input type="text" class="form-control" name="color_vehiculo">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">MARCA</label>
                        <input type="text" class="form-control" name="marca_vehiculo">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">DAÑOS OBSERVADOS</label>
                        <textarea class="form-control" name="danos_vehiculo" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERSONAS LESIONADAS -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>PERSONAS LESIONADAS</h5>
            </div>
            <div class="card-body">
                <div id="personas-lesionadas-container">
                    <div class="persona-lesionada mb-3 border p-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">NOMBRES Y APELLIDOS</label>
                                <input type="text" class="form-control" name="personas_lesionadas[0][nombre]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">DOC. IDENTIDAD</label>
                                <input type="text" class="form-control" name="personas_lesionadas[0][documento]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CLASE DE LESIONES</label>
                                <input type="text" class="form-control" name="personas_lesionadas[0][lesiones]">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-outline-secondary" id="agregar-persona">
                    <i class="bi bi-plus-circle me-2"></i>Agregar otra persona lesionada
                </button>
            </div>
        </div>

        <!-- TRABAJO EFECTUADO -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-tools me-2"></i>TRABAJO EFECTUADO</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">DESCRIPCIÓN DEL TRABAJO EFECTUADO *</label>
                    <textarea class="form-control" name="trabajo_efectuado" rows="4" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">MATERIAL UTILIZADO *</label>
                    <textarea class="form-control" name="material_utilizado" rows="3" required></textarea>
                </div>
            </div>
        </div>

        <!-- UNIDADES DE APOYO -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>UNIDADES DE APOYO</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">UNIDADES DE APOYO CGBVP</label>
                    <textarea class="form-control" name="unidades_apoyo_cgbvp" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">AL MANDO DE LAS OPERACIONES CGBVP</label>
                    <input type="text" class="form-control" name="mando_operaciones_cgbvp">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">UNIDADES POLICIALES</label>
                    <textarea class="form-control" name="unidades_policiales" rows="2"></textarea>
                </div>
            </div>
        </div>

        <!-- PERSONAL ASISTENTE -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>PERSONAL ASISTENTE A LA EMERGENCIA</h5>
            </div>
            <div class="card-body">
                <div id="personal-asistente-container">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="personal_asistente[]" 
                                   placeholder="Nombre del bombero asistente">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="personal_asistente[]" 
                                   placeholder="Nombre del bombero asistente">
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-outline-secondary" id="agregar-personal">
                    <i class="bi bi-plus-circle me-2"></i>Agregar más personal
                </button>
            </div>
        </div>

        <!-- INFORMACIÓN ADICIONAL -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>INFORMACIÓN ADICIONAL</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">PERSONAL BOMBERO ACCIDENTADO</label>
                    <textarea class="form-control" name="personal_bombero_accidentado" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">PERSONAL ASISTENTE EN EL CUARTEL</label>
                    <textarea class="form-control" name="personal_asistente_cuartel" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">DAMNIFICADOS POR INCENDIOS</label>
                    <textarea class="form-control" name="damnificados_incendios" rows="4"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">OBSERVACIONES</label>
                    <textarea class="form-control" name="observaciones" rows="4"></textarea>
                </div>
            </div>
        </div>

        <!-- FIRMAS -->
        <div class="card card-bomberos mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pen me-2"></i>FIRMAS</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">BOMBERO AL MANDO DE LA OPERACIÓN</label>
                        <input type="text" class="form-control" name="firma_bombero_mando">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">BOMBERO QUE CONFECCIONA EL PARTE *</label>
                        <input type="text" class="form-control" name="firma_bombero_confecciona" 
                               value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">SEGUNDO JEFE DE COMPAÑÍA</label>
                        <input type="text" class="form-control" name="firma_segundo_jefe">
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('partes-incendios.index') }}" class="btn btn-secondary">
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
    let personaCount = 1;
    let personalCount = 2;
    
    // Agregar persona lesionada
    $('#agregar-persona').click(function() {
        const html = `
        <div class="persona-lesionada mb-3 border p-3">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">NOMBRES Y APELLIDOS</label>
                    <input type="text" class="form-control" name="personas_lesionadas[${personaCount}][nombre]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">DOC. IDENTIDAD</label>
                    <input type="text" class="form-control" name="personas_lesionadas[${personaCount}][documento]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">CLASE DE LESIONES</label>
                    <input type="text" class="form-control" name="personas_lesionadas[${personaCount}][lesiones]">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-persona">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>`;
        
        $('#personas-lesionadas-container').append(html);
        personaCount++;
    });
    
    // Agregar personal asistente
    $('#agregar-personal').click(function() {
        const html = `
        <div class="row mb-3 personal-asistente">
            <div class="col-md-6">
                <input type="text" class="form-control" name="personal_asistente[]" 
                       placeholder="Nombre del bombero asistente">
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" name="personal_asistente[]" 
                           placeholder="Nombre del bombero asistente">
                    <button class="btn btn-outline-danger remove-personal" type="button">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
        
        $('#personal-asistente-container').append(html);
        personalCount += 2;
    });
    
    // Eliminar persona lesionada
    $(document).on('click', '.remove-persona', function() {
        $(this).closest('.persona-lesionada').remove();
    });
    
    // Eliminar personal asistente
    $(document).on('click', '.remove-personal', function() {
        $(this).closest('.personal-asistente').remove();
    });
    
    // Validación antes de enviar
    $('#parteForm').submit(function() {
        // Validar que la hora de retorno sea mayor que la de salida
        const horaSalida = $('input[name="hora_salida"]').val();
        const horaRetorno = $('input[name="hora_retorno"]').val();
        
        if (horaSalida && horaRetorno && horaRetorno <= horaSalida) {
            alert('La hora de retorno debe ser mayor que la hora de salida.');
            return false;
        }
        
        return true;
    });
});
</script>
@endsection