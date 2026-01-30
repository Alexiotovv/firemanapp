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
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong><br>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Apellidos:</strong><br>{{ $user->apellidos }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>DNI:</strong><br>{{ $user->dni }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Compañía:</strong><br>{{ $user->compania }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Email:</strong><br>{{ $user->email }}</p>
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
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-bomberos">
                            <i class="bi bi-pencil me-2"></i>Editar Usuario
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
            
            <div class="card card-bomberos mt-3">
                <div class="card-body">
                    <h6>Información del Sistema</h6>
                    <p class="small text-muted">
                        Los usuarios administradores tienen acceso completo al sistema, 
                        mientras que los usuarios regulares solo pueden ver información 
                        de su misma compañía.
                    </p>
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