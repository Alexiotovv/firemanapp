@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card card-bomberos">
                <div class="card-body">
                    <div class="section-header mb-3">
                        <h5><i class="bi bi-person-circle me-2"></i>Mi Perfil</h5>
                        <hr>
                    </div>

                    <form method="POST" action="{{ route('profile.store') }}">
                        @csrf


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres_apellidos" class="form-label">Nombres y Apellidos</label>
                                <input type="text" class="form-control" id="nombres_apellidos" name="nombres_apellidos" value="{{ old('nombres_apellidos') }}">
                                @error('nombres_apellidos')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}">
                                @error('dni')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $profile->codigo ?? '') }}">
                                @error('codigo')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="grados" class="form-label">Grados / Rango</label>
                                <input type="text" class="form-control" id="grados" name="grados" value="{{ old('grados', $profile->grados ?? '') }}">
                                @error('grados')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_graduacion" class="form-label">Fecha de Graduación</label>
                                <input type="date" class="form-control" id="fecha_graduacion" name="fecha_graduacion" value="{{ old('fecha_graduacion', optional($profile?->fecha_graduacion)->format('Y-m-d') ?? '') }}">
                                @error('fecha_graduacion')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_asenso" class="form-label">Última Fecha de Ascenso</label>
                                <input type="date" class="form-control" id="fecha_asenso" name="fecha_asenso" value="{{ old('fecha_asenso', optional($profile?->fecha_asenso)->format('Y-m-d') ?? '') }}">
                                @error('fecha_asenso')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="section-header mb-3 mt-4">
                            <h5><i class="bi bi-book me-2"></i>Capacitación y Cursos</h5>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="curso_basicos" class="form-label">Cursos Básicos</label>
                                <input type="text" class="form-control" id="curso_basicos" name="curso_basicos" value="{{ old('curso_basicos', $profile->curso_basicos ?? '') }}">
                                @error('curso_basicos')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="curso_tecnicos" class="form-label">Cursos Técnicos</label>
                                <input type="text" class="form-control" id="curso_tecnicos" name="curso_tecnicos" value="{{ old('curso_tecnicos', $profile->curso_tecnicos ?? '') }}">
                                @error('curso_tecnicos')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="curso_liderazgo" class="form-label">Cursos de Liderazgo</label>
                                <input type="text" class="form-control" id="curso_liderazgo" name="curso_liderazgo" value="{{ old('curso_liderazgo', $profile->curso_liderazgo ?? '') }}">
                                @error('curso_liderazgo')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="section-header mb-3 mt-4">
                            <h5><i class="bi bi-telephone me-2"></i>Contacto</h5>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correo_personal" class="form-label">Correo electrónico personal/institucional</label>
                                <input type="email" class="form-control" id="correo_personal" name="correo_personal" value="{{ old('correo_personal', $profile->correo_personal ?? '') }}">
                                @error('correo_personal')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $profile->telefono ?? '') }}">
                                @error('telefono')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ubo" class="form-label">UBO</label>
                                <input type="text" class="form-control" id="ubo" name="ubo" value="{{ old('ubo', $profile->ubo ?? '') }}">
                                @error('ubo')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ultimo_cargo" class="form-label">Último Cargo</label>
                                <input type="text" class="form-control" id="ultimo_cargo" name="ultimo_cargo" value="{{ old('ultimo_cargo', $profile->ultimo_cargo ?? '') }}">
                                @error('ultimo_cargo')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                                <select class="form-select" id="tipo_sangre" name="tipo_sangre">
                                    <option value="" disabled selected>Selecciona un tipo</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('tipo_sangre', $profile->tipo_sangre ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_sangre')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a>
                            <button type="submit" class="btn btn-bomberos"><i class="bi bi-check-circle me-2"></i>Guardar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Solo Perfil</h6>
                </div>
                <div class="card-body">
                    <p class="small">
                        Aquí puedes completar únicamente los datos de tu perfil profesional y de contacto.
                    </p>
                    <p class="small">
                        No se editan datos de usuario, contraseña ni acceso desde esta pantalla.
                    </p>
                    <p class="small">
                        Cada envío crea un nuevo registro en el sistema. Puedes enviar varios registros si lo deseas.
                    </p>
                    <p class="small">
                        Guarda los cambios cuando termines.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section-header h5 { color: var(--primary-red, #dc3545); font-weight: 600; }
</style>
@endsection
