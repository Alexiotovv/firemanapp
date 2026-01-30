@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="bi bi-people me-2"></i>Gestión de Usuarios</h2>
                    <p class="text-muted">Administra los usuarios del sistema</p>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-bomberos">
                    <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-bomberos">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>DNI</th>
                            <th>Compañía</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->apellidos }}</td>
                            <td>{{ $user->dni }}</td>
                            <td>{{ $user->compania }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-primary">User</span>
                                @endif
                            </td>
                            <td>
                                <!-- En las acciones, condicionar según rol: -->
                                @if(auth()->user()->is_admin || $user->id === auth()->user()->id)
                                    <a href="{{ route('users.edit', $user) }}" 
                                    class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                @if(auth()->user()->is_admin && $user->id !== auth()->user()->id)
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-user" 
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
    
    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endsection