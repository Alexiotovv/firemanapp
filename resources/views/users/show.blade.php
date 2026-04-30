@extends('layouts.app')

@section('title', 'Ver Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="bi bi-person me-2"></i>Perfil de Usuario</h2>
                    <p class="text-muted">Información detallada del usuario</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Información Personal -->
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong><br>{{ $user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Apellidos:</strong><br>{{ $user->apellidos ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>DNI:</strong><br>{{ $user->dni ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tipo de Sangre:</strong><br>
                                @if($user->tipo_sangre)
                                    <span class="badge bg-info">{{ $user->tipo_sangre }}</span>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Compañía:</strong><br>{{ $user->compania ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Rol:</strong><br>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Administrador</span>
                                @else
                                    <span class="badge bg-primary">Usuario</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Profesional -->
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Información Profesional</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Código/Matrícula:</strong><br>{{ $user->codigo ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Grados/Rango:</strong><br>{{ $user->grados ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Último Cargo Ocupado:</strong><br>{{ $user->ultimo_cargo ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>UBO (Unidad Base Operaciones):</strong><br>{{ $user->ubo ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Fecha de Graduación:</strong><br>
                                @if($user->fecha_graduacion)
                                    {{ $user->fecha_graduacion->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">No especificada</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Última Fecha de Ascenso:</strong><br>
                                @if($user->fecha_asenso)
                                    {{ $user->fecha_asenso->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">No especificada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Capacitación y Cursos -->
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-book me-2"></i>Capacitación y Cursos</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>Cursos Básicos:</strong><br>
                                @if($user->curso_basicos)
                                    <span class="badge bg-success">{{ $user->curso_basicos }}</span>
                                @else
                                    <span class="text-muted">Pendiente</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Cursos Técnicos:</strong><br>
                                @if($user->curso_tecnicos)
                                    <span class="badge bg-info">{{ $user->curso_tecnicos }}</span>
                                @else
                                    <span class="text-muted">Pendiente</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Cursos de Liderazgo:</strong><br>
                                @if($user->curso_liderazgo)
                                    <span class="badge bg-warning text-dark">{{ $user->curso_liderazgo }}</span>
                                @else
                                    <span class="text-muted">Pendiente</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Información de Contacto</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Teléfono:</strong><br>
                                @if($user->telefono)
                                    <a href="tel:{{ $user->telefono }}">{{ $user->telefono }}</a>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email Institucional:</strong><br>{{ $user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p><strong>Email Personal:</strong><br>
                                @if($user->correo_personal)
                                    <a href="mailto:{{ $user->correo_personal }}">{{ $user->correo_personal }}</a>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Fecha de creación:</strong><br>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Última actualización:</strong><br>{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-bomberos">
                            <i class="bi bi-pencil me-2"></i>Editar Perfil
                        </a>
                        
                        @if(auth()->user()->is_admin && $user->id !== auth()->user()->id)
                            <button type="button" class="btn btn-outline-danger delete-user" 
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                <i class="bi bi-trash me-2"></i>Eliminar Usuario
                            </button>
                        @endif
                        
                        @if($user->id === auth()->user()->id)
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Este es tu perfil de usuario</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card card-bomberos mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <p class="small">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Información Completa:</strong>
                    </p>
                    <ul class="small">
                        <li>✓ Datos Personales</li>
                        <li>✓ Información Profesional</li>
                        <li>✓ Capacitación</li>
                        <li>✓ Datos de Contacto</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de eliminar al usuario <strong id="userName"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Manejar clic en botón eliminar
    $('.delete-user').click(function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        
        $('#userName').text(userName);
        $('#deleteForm').attr('action', '/users/' + userId);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection