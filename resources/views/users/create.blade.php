@extends('layouts.app')

@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="bi bi-person me-2"></i>
                {{ isset($user) ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h2>
            <p class="text-muted">
                {{ isset($user) ? 'Modifica los datos del usuario' : 'Agrega un nuevo usuario al sistema' }}
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
                                <label for="compania" class="form-label">Compañía *</label>
                                <input type="text" class="form-control" id="compania" name="compania" 
                                       value="{{ old('compania', $user->compania ?? '') }}" required>
                                @error('compania')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="dni" class="form-label">DNI *</label>
                                <input type="text" class="form-control" id="dni" name="dni" 
                                       value="{{ old('dni', $user->dni ?? '') }}" required>
                                @error('dni')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    {{ isset($user) ? 'Nueva Contraseña' : 'Contraseña *' }}
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       {{ isset($user) ? '' : 'required' }}>
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
                                {{ isset($user) ? 'Actualizar Usuario' : 'Crear Usuario' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información</h6>
                </div>
                <div class="card-body">
                    <p class="small">
                        <strong>Campos obligatorios:</strong> Todos los campos marcados con * son obligatorios.
                    </p>
                    <p class="small">
                        <strong>DNI:</strong> Debe ser único para cada usuario.
                    </p>
                    <p class="small">
                        <strong>Contraseña:</strong> Mínimo 6 caracteres.
                    </p>
                    <p class="small">
                        <strong>Compañía:</strong> Indica la compañía de bomberos a la que pertenece el usuario.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection