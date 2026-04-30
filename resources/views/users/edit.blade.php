@extends('layouts.app')

@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card card-bomberos">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="editTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-tab-pane" type="button" role="tab">Usuario</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">Perfil</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Usuario tab -->
                        <div class="tab-pane fade show active" id="user-tab-pane" role="tabpanel">
                            <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                                @csrf
                                @if(isset($user)) @method('PUT') @endif

                                <div class="section-header mb-3">
                                    <h5><i class="bi bi-person-badge me-2"></i>Información Personal</h5>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nombre *</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="apellidos" class="form-label">Apellidos *</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos', $user->apellidos ?? '') }}" required>
                                        @error('apellidos')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dni" class="form-label">DNI *</label>
                                        <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni', $user->dni ?? '') }}" required>
                                        @error('dni')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Institucional *</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                @if(auth()->user()->is_admin || !isset($user))
                                <div class="section-header mb-3 mt-4">
                                    <h5><i class="bi bi-gear me-2"></i>Configuración</h5>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="compania" class="form-label">Compañía *</label>
                                        <input type="text" class="form-control" id="compania" name="compania" value="{{ old('compania', $user->compania ?? '') }}" {{ auth()->user()->is_admin ? '' : 'disabled' }} required>
                                        @error('compania')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>

                                    @if(auth()->user()->is_admin)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin', isset($user) && $user->is_admin ? 'checked' : '') }}>
                                            <label class="form-check-label" for="is_admin"><strong>Es Administrador</strong></label>
                                        </div>
                                        <small class="text-muted">Los administradores tienen acceso completo al sistema.</small>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <div class="section-header mb-3 mt-4">
                                    <h5><i class="bi bi-shield-lock me-2"></i>Contraseña</h5>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">{{ isset($user) ? 'Nueva Contraseña' : 'Contraseña *' }}</label>
                                        <input type="password" class="form-control" id="password" name="password" {{ isset($user) ? '' : 'required' }}>
                                        @if(isset($user))<small class="text-muted">Déjalo en blanco si no deseas cambiar la contraseña</small>@endif
                                        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Cancelar</a>
                                    <button type="submit" class="btn btn-bomberos"><i class="bi bi-check-circle me-2"></i>{{ isset($user) ? 'Actualizar Usuario' : 'Crear Usuario' }}</button>
                                </div>
                            </form>
                        </div>

                        <!-- Perfil tab -->
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">
                            @php $profile = optional($user->profile); @endphp
                            <form method="POST" action="{{ $profile->id ? route('profile.update', $profile->id) : route('profile.store') }}">
                                @csrf
                                @if($profile->id) @method('PUT') @endif

                                <div class="section-header mb-3 mt-2">
                                    <h5><i class="bi bi-briefcase me-2"></i>Información Profesional</h5>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_profile" class="form-label">Código/Matrícula</label>
                                        <input type="text" class="form-control" id="codigo_profile" name="codigo" value="{{ old('codigo', $profile->codigo ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="grados_profile" class="form-label">Grados/Rango</label>
                                        <input type="text" class="form-control" id="grados_profile" name="grados" value="{{ old('grados', $profile->grados ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_graduacion_profile" class="form-label">Fecha de Graduación</label>
                                        <input type="date" class="form-control" id="fecha_graduacion_profile" name="fecha_graduacion" value="{{ old('fecha_graduacion', $profile->fecha_graduacion ? $profile->fecha_graduacion->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_asenso_profile" class="form-label">Última Fecha de Ascenso</label>
                                        <input type="date" class="form-control" id="fecha_asenso_profile" name="fecha_asenso" value="{{ old('fecha_asenso', $profile->fecha_asenso ? $profile->fecha_asenso->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="section-header mb-3 mt-4"><h5><i class="bi bi-book me-2"></i>Capacitación y Cursos</h5><hr></div>
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">Cursos Básicos</label><input class="form-control" name="curso_basicos" value="{{ old('curso_basicos', $profile->curso_basicos ?? '') }}"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Cursos Técnicos</label><input class="form-control" name="curso_tecnicos" value="{{ old('curso_tecnicos', $profile->curso_tecnicos ?? '') }}"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Cursos de Liderazgo</label><input class="form-control" name="curso_liderazgo" value="{{ old('curso_liderazgo', $profile->curso_liderazgo ?? '') }}"></div>
                                </div>

                                <div class="section-header mb-3 mt-4"><h5><i class="bi bi-telephone me-2"></i>Información de Contacto</h5><hr></div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="form-label">Email Personal</label><input class="form-control" name="correo_personal" value="{{ old('correo_personal', $profile->correo_personal ?? '') }}"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Teléfono</label><input class="form-control" name="telefono" value="{{ old('telefono', $profile->telefono ?? '') }}"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="form-label">UBO</label><input class="form-control" name="ubo" value="{{ old('ubo', $profile->ubo ?? '') }}"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Último Cargo</label><input class="form-control" name="ultimo_cargo" value="{{ old('ultimo_cargo', $profile->ultimo_cargo ?? '') }}"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">Tipo de Sangre</label><input class="form-control" name="tipo_sangre" value="{{ old('tipo_sangre', $profile->tipo_sangre ?? '') }}"></div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Cancelar</a>
                                    <button type="submit" class="btn btn-bomberos"><i class="bi bi-check-circle me-2"></i>{{ $profile->id ? 'Actualizar Perfil' : 'Guardar Perfil' }}</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- /.tab-content -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col-lg-8 -->

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
                        