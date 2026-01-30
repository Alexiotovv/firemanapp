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
                            <td>#{{ str_pad($parte->numero_parte, 4, '0', STR_PAD_LEFT) }}</td>
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
                                        <form action="{{ route('partes-incendios.destroy', $parte) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('¿Eliminar este parte?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-ocultar alertas
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endsection