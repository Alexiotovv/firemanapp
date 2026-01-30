@extends('layouts.app')

@section('title', 'Partes de Emergencias Médicas')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="row align-items-center">
                <div class="col-md-8 col-lg-9 mb-3 mb-md-0">
                    <h2 class="mb-0"><i class="bi bi-ambulance me-2"></i>Partes de Emergencias Médicas</h2>
                    <p class="text-muted mb-0">Gestión de partes de servicios médicos de emergencia</p>
                </div>
                
                <div class="col-md-4 col-lg-3 text-md-end">
                    <a href="{{ route('partes-emergencias.create') }}" class="btn btn-bomberos w-100 w-md-auto">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Parte
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('components.alert')

    <div class="card card-bomberos">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell">N°</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th class="d-none d-lg-table-cell">Diagnóstico</th>
                            <th>Destino</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partes as $parte)
                        <tr>
                            <td class="d-none d-md-table-cell">
                                #{{ str_pad($parte->numero_parte, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                {{ $parte->fecha->format('d/m/Y') }}
                                <div class="text-muted small d-md-none">
                                    {{ $parte->hora_salida }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    <div>
                                        <div>{{ $parte->nombre_afectado ?? 'N/A' }}</div>
                                        <div class="text-muted small">
                                            @if($parte->edad_afectado)
                                                {{ $parte->edad_afectado }} años
                                            @endif
                                            @if($parte->sexo_afectado)
                                                | {{ $parte->sexo_afectado == 'M' ? 'M' : 'F' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                    {{ $parte->diagnostico_presuntivo ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                    {{ $parte->traslado_a ?? 'N/A' }}
                                </span>
                            </td>
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('partes-emergencias.show', $parte) }}" 
                                       class="btn btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                        <span class="d-none d-md-inline ms-1">Ver</span>
                                    </a>
                                    
                                    @if((!$parte->completado && $parte->user_id == auth()->id()) || auth()->user()->is_admin)
                                        <a href="{{ route('partes-emergencias.edit', $parte) }}" 
                                           class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-md-inline ms-1">Editar</span>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('partes-emergencias.imprimir', $parte) }}" 
                                       class="btn btn-outline-info" target="_blank" title="Imprimir">
                                        <i class="bi bi-printer"></i>
                                        <span class="d-none d-md-inline ms-1">Imprimir</span>
                                    </a>
                                    
                                    @if(auth()->user()->is_admin)
                                        <button type="button" class="btn btn-outline-danger delete-parte" 
                                                data-id="{{ $parte->id }}" 
                                                data-numero="{{ $parte->numero_parte }}"
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-md-inline ms-1">Eliminar</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-ambulance display-4 d-block mb-3"></i>
                                    <h5>No hay partes de emergencias registrados</h5>
                                    <p class="mb-3">Comienza agregando un nuevo parte de emergencia</p>
                                    <a href="{{ route('partes-emergencias.create') }}" class="btn btn-bomberos">
                                        <i class="bi bi-plus-circle me-2"></i>Crear Primer Parte
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- PAGINACIÓN - SOLUCIÓN CORRECTA -->
        @if($partes instanceof \Illuminate\Pagination\LengthAwarePaginator && $partes->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $partes->firstItem() ?? 0 }} a {{ $partes->lastItem() ?? 0 }} de {{ $partes->total() }} partes
                </div>
                <div>
                    {{ $partes->links() }}
                </div>
            </div>
        </div>
        @endif
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
                <p>¿Estás seguro de eliminar el parte de emergencia <strong id="parteNumero"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Manejar clic en botón eliminar
    $('.delete-parte').click(function() {
        var parteId = $(this).data('id');
        var parteNumero = $(this).data('numero');
        
        $('#parteNumero').text('#' + parteNumero.toString().padStart(4, '0'));
        $('#deleteForm').attr('action', '/partes-emergencias/' + parteId);
        $('#deleteModal').modal('show');
    });
    
    // Auto-ocultar alertas
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>

<style>
/* Estilos para la paginación */
.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #dc3545;
}

.page-item.active .page-link {
    background-color: #dc3545;
    border-color: #dc3545;
}

.page-link:hover {
    color: #c82333;
}

/* Para móviles */
@media (max-width: 767.98px) {
    .pagination {
        font-size: 0.875rem;
    }
    
    .page-link {
        padding: 0.375rem 0.5rem;
    }
}
</style>
@endsection