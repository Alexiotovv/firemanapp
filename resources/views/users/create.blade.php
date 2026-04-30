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
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="section-header mb-3">
                            <h5><i class="bi bi-person-badge me-2"></i>Información Básica</h5>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
                                @error('apellidos')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dni" class="form-label">DNI *</label>
                                <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" required>
                                @error('dni')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Institucional *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        @if(auth()->user()->is_admin)
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label for="compania" class="form-label">Compañía *</label>
                                <input type="text" class="form-control" id="compania" name="compania" value="{{ old('compania') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1">
                                    <label class="form-check-label" for="is_admin"><strong>Es Administrador</strong></label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Cancelar</a>
                            <button type="submit" class="btn btn-bomberos"><i class="bi bi-check-circle me-2"></i>Crear Usuario</button>
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