@extends('layouts.app')

@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="bi bi-person me-2"></i>
                {{ isset($user) ? 'Editar Perfil' : 'Nuevo Usuario' }}
            </h2>
            <p class="text-muted">
                {{ isset($user) ? 'Actualiza tu información personal y profesional' : 'Agrega un nuevo usuario al sistema' }}
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-bomberos">
                <div class="card-body">
                    <form method="POST" 
                          action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <!-- SECCIÓN: Información Personal -->
                        <div class="section-header mb-3">
                            <h5><i class="bi bi-person-badge me-2"></i>Información Personal</h5>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name ?? '') }}" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                       value="{{ old('apellidos', $user->apellidos ?? '') }}" required>
                                @error('apellidos')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dni" class="form-label">DNI *</label>
                                <input type="text" class="form-control" id="dni" name="dni" 
                                       value="{{ old('dni', $user->dni ?? '') }}" required>
                                @error('dni')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                                <select class="form-select" id="tipo_sangre" name="tipo_sangre">
                                    <option value="">Selecciona tu tipo de sangre</option>
                                    <option value="O+" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="A+" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('tipo_sangre', $user->tipo_sangre ?? '') === 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                                @error('tipo_sangre')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SECCIÓN: Información Profesional -->
                        <div class="section-header mb-3 mt-4">
                            <h5><i class="bi bi-briefcase me-2"></i>Información Profesional</h5>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código/Matrícula</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" 
                                       value="{{ old('codigo', $user->codigo ?? '') }}">
                                @error('codigo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="grados" class="form-label">Grados/Rango</label>
                                <input type="text" class="form-control" id="grados" name="grados" 
                                       value="{{ old('grados', $user->grados ?? '') }}">
                                @error('grados')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ultimo_cargo" class="form-label">Último Cargo Ocupado</label>
                                <input type="text" class="form-control" id="ultimo_cargo" name="ultimo_cargo" 
                                       value="{{ old('ultimo_cargo', $user->ultimo_cargo ?? '') }}">
                                @error('ultimo_cargo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="ubo" class="form-label">UBO (Unidad Base Operaciones)</label>
                                <input type="text" class="form-control" id="ubo" name="ubo" 
                                       value="{{ old('ubo', $user->ubo ?? '') }}">
                                @error('ubo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_graduacion" class="form-label">Fecha de Graduación</label>
                                <input type="date" class="form-control" id="fecha_graduacion" name="fecha_graduacion" 
                                       value="{{ old('fecha_graduacion', $user->fecha_graduacion ? $user->fecha_graduacion->format('Y-m-d') : '') }}">
                                @error('fecha_graduacion')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_asenso" class="form-label">Última Fecha de Ascenso</label>
                                <input type="date" class="form-control" id="fecha_asenso" name="fecha_asenso" 
                                       value="{{ old('fecha_asenso', $user->fecha_asenso ? $user->fecha_asenso->format('Y-m-d') : '') }}">
                                @error('fecha_asenso')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SECCIÓN: Capacitación -->
                        <div class="section-header mb-3 mt-4">
                            <h5><i class="bi bi-book me-2"></i>Capacitación y Cursos</h5>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="curso_basicos" class="form-label">Cursos Básicos</label>
                                <input type="text" class="form-control" id="curso_basicos" name="curso_basicos" 
                                       placeholder="ej. Completado, Pendiente" value="{{ old('curso_basicos', $user->curso_basicos ?? '') }}">
                                @error('curso_basicos')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="curso_tecnicos" class="form-label">Cursos Técnicos</label>
                                <input type="text" class="form-control" id="curso_tecnicos" name="curso_tecnicos" 
                                       placeholder="ej. Completado, Pendiente" value="{{ old('curso_tecnicos', $user->curso_tecnicos ?? '') }}">
                                @error('curso_tecnicos')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="curso_liderazgo" class="form-label">Cursos de Liderazgo</label>
                                <input type="text" class="form-control" id="curso_liderazgo" name="curso_liderazgo" 
                                       placeholder="ej. Completado, Pendiente" value="{{ old('curso_liderazgo', $user->curso_liderazgo ?? '') }}">
                                @error('curso_liderazgo')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SECCIÓN: Información de Contacto -->
                        <div class="section-header mb-3 mt-4">
                            <h5><i class="bi bi-telephone me-2"></i>Información de Contacto</h5>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Institucional *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $user->email ?? '') }}" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="correo_personal" class="form-label">Email Personal</label>
                                <input type="email" class="form-control" id="correo_personal" name="correo_personal" 
                                       value="{{ old('correo_personal', $user->correo_personal ?? '') }}">
                                @error('correo_personal')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="{{ old('telefono', $user->telefono ?? '') }}">
                                @error('telefono')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SECCIÓN: Solo para Admin y Crear Usuario -->
                        @if(auth()->user()->is_admin || !isset($user))
                            <div class="section-header mb-3 mt-4">
                                <h5><i class="bi bi-gear me-2"></i>Configuración</h5>
                                <hr>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="compania" class="form-label">Compañía *</label>
                                    <input type="text" class="form-control" id="compania" name="compania" 
                                           value="{{ old('compania', $user->compania ?? '') }}" 
                                           {{ auth()->user()->is_admin ? '' : 'disabled' }} required>
                                    @error('compania')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if(auth()->user()->is_admin)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" 
                                            value="1" {{ old('is_admin', isset($user) && $user->is_admin ? 'checked' : '') }}>
                                        <label class="form-check-label" for="is_admin">
                                            <strong>Es Administrador</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted">Los administradores tienen acceso completo al sistema.</small>
                                </div>
                                @endif
                            </div>
                        @endif

                        <!-- SECCIÓN: Contraseña -->
                        <div class="section-header mb-3 mt-4">
                            <h5><i class="bi bi-shield-lock me-2"></i>Contraseña</h5>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    {{ isset($user) ? 'Nueva Contraseña' : 'Contraseña *' }}
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       {{ isset($user) ? '' : 'required' }}>
                                @if(isset($user))
                                    <small class="text-muted">Déjalo en blanco si no deseas cambiar la contraseña</small>
                                @endif
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-bomberos">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ isset($user) ? 'Actualizar Perfil' : 'Crear Usuario' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instrucciones</h6>
                </div>
                <div class="card-body">
                    <p class="small">
                        <strong>Campos obligatorios:</strong> Marcados con * son requeridos.
                    </p>
                    <p class="small">
                        <strong>Campos opcionales:</strong> Los demás campos puedes dejarlos vacíos.
                    </p>
                    <p class="small">
                        <strong>Contraseña:</strong> Mínimo 6 caracteres ({{ isset($user) ? 'opcional en edición' : 'obligatoria' }}).
                    </p>
                </div>
            </div>

            @if(isset($user))
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Cronograma</h6>
                </div>
                <div class="card-body">
                    <p class="small">
                        <strong>Última actualización:</strong><br>
                        {{ $user->updated_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="small">
                        <strong>Creado:</strong><br>
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
            @endif

            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-key me-2"></i>Seguridad</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        La información de este formulario está protegida. 
                        Solo tú y los administradores pueden verla.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section-header h5 {
        color: var(--primary-red, #dc3545);
        font-weight: 600;
    }
</style>
@endsection