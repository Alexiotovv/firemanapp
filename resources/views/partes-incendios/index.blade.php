@extends('layouts.app')

@section('title', 'Partes de Incendios')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="bi bi-fire me-2"></i>Partes de Incendios</h2>
                    <p class="text-muted">Gestión de partes de servicios de bomberos</p>
                </div>
                <a href="{{ route('partes-incendios.create') }}" class="btn btn-bomberos">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Parte
                </a>
            </div>
        </div>
    </div>

    @include('components.alert')

    <div class="card card-bomberos mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-exclamation-octagon me-2"></i>Emergencias</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>N° Parte</th>
                            <th>Emergencia</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            @if(auth()->user()->is_admin)<th>Usuario asignado</th>@endif
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emergencies as $emergency)
                            <tr>
                                <td>{{ $emergency->numero }}</td>
                                <td>{{ $emergency->tipo_label }}</td>
                                <td>{{ $emergency->direccion }}</td>
                                <td>{{ $emergency->estado_label }}</td>
                                @if(auth()->user()->is_admin)
                                    <td>
                                        @if($emergency->estado === 'finalizada' && !$emergency->parteIncendio)
                                            <form method="POST" action="{{ route('admin.emergencies.assign-parte', $emergency) }}" class="d-flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="parte_user_id" class="form-select form-select-sm" required>
                                                    <option value="">Seleccionar usuario…</option>
                                                    @foreach($parteUsers as $user)
                                                        <option value="{{ $user->id }}" @selected($emergency->parte_user_id === $user->id)>
                                                            {{ $user->compania }} - {{ $user->name }} {{ $user->apellidos }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-sm btn-outline-primary">Asignar</button>
                                            </form>
                                        @else
                                            {{ $emergency->parteUser ? $emergency->parteUser->name.' '.$emergency->parteUser->apellidos : 'Sin asignar' }}
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    @if($emergency->parteIncendio)
                                        <a href="{{ route('partes-incendios.show', $emergency->parteIncendio) }}" class="btn btn-sm btn-outline-primary">
                                            Ver parte
                                        </a>
                                    @elseif(!auth()->user()->is_admin && $emergency->estado === 'finalizada' && $emergency->parte_user_id === auth()->id())
                                        <a href="{{ route('partes-incendios.create', ['emergency_id' => $emergency->id]) }}" class="btn btn-sm btn-danger">
                                            Completar parte
                                        </a>
                                    @else
                                        <span class="text-muted">Pendiente</span>
                                    @endif
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('admin.emergencies.edit', $emergency) }}" class="btn btn-sm btn-outline-secondary ms-1">
                                            Editar emergencia
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->is_admin ? 6 : 5 }}" class="text-center text-muted py-3">
                                    No hay emergencias asignadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-bomberos">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Parte</th>
                            <th>Fecha</th>
                            <th>Clasificación</th>
                            <th>Dirección</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partes as $parte)
                        <tr>
                            <td>{{ $parte->emergency?->numero ?? '#'.str_pad($parte->numero_parte, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $parte->fecha->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $parte->clasificacion_servicio_texto }}
                                </span>
                            </td>
                            <td>
                                <small>{{ Str::limit($parte->direccion_emergencia, 50) }}</small>
                            </td>
                            <td>{{ $parte->user->name }}</td>
                            <td>
                                @if($parte->aprobado)
                                    <span class="badge bg-success">Aprobado</span>
                                @elseif($parte->completado)
                                    <span class="badge bg-warning">Completado</span>
                                @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('partes-incendios.show', $parte) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if((!$parte->completado && $parte->user_id == auth()->id()) || auth()->user()->is_admin)
                                        <a href="{{ route('partes-incendios.edit', $parte) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('partes-incendios.imprimir', $parte) }}" 
                                       class="btn btn-sm btn-outline-info" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    
                                    @if(auth()->user()->is_admin)
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-parte-incendio"
                                                data-id="{{ $parte->id }}"
                                                data-numero="{{ $parte->emergency?->numero ?? '#'.str_pad($parte->numero_parte, 4, '0', STR_PAD_LEFT) }}"
                                                title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                                <span class="ms-1">Eliminar</span>
                                            </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($partes->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-3">No hay partes registrados</p>
                                <a href="{{ route('partes-incendios.create') }}" class="btn btn-bomberos">
                                    Crear Primer Parte
                                </a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->is_admin)
<div class="modal fade" id="deleteParteIncendioModal" tabindex="-1" aria-labelledby="deleteParteIncendioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteParteIncendioModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de eliminar el parte de incendio <strong id="parteIncendioNumero"></strong>?</p>
                <p class="text-danger mb-0"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteParteIncendioForm" method="POST"
                      action="{{ route('partes-incendios.destroy', ['partesIncendio' => '__PARTE_ID__']) }}"
                      data-action="{{ route('partes-incendios.destroy', ['partesIncendio' => '__PARTE_ID__']) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.delete-parte-incendio').click(function() {
        const parteId = $(this).data('id');
        const parteNumero = $(this).data('numero');

        $('#parteIncendioNumero').text(parteNumero);
        $('#deleteParteIncendioForm').attr(
            'action',
            $('#deleteParteIncendioForm').data('action').replace('__PARTE_ID__', parteId)
        );
        $('#deleteParteIncendioModal').modal('show');
    });

    // Auto-ocultar alertas
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endsection