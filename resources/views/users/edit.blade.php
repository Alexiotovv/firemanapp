@extends('layouts.app')

@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card card-bomberos">
                <div class="card-body">
                    <div class="section-header mb-3">
                        <h5><i class="bi bi-person-badge me-2"></i>Editar Usuario</h5>
                        <hr>
                    </div>

                    <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                        @csrf
                        @if(isset($user)) @method('PUT') @endif

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
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instrucciones</h6>
                </div>
                <div class="card-body">
                    <p class="small"><strong>Campos obligatorios:</strong> marcados con *.</p>
                    <p class="small">Solo el administrador puede cambiar permisos y compañía.</p>
                    <p class="small">La contraseña es obligatoria solo al crear usuario.</p>
                </div>
            </div>
            @if(isset($user))
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Cronograma</h6>
                </div>
                <div class="card-body">
                    <p class="small"><strong>Última actualización:</strong><br>{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    <p class="small"><strong>Creado:</strong><br>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .section-header h5 { color: var(--primary-red, #dc3545); font-weight: 600; }
</style>
@endsection
                        