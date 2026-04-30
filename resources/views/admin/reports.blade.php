@extends('layouts.app')

@section('title', 'Reportes Administrador')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-file-earmark-bar-graph me-2"></i>Reportes</h2>
            <p class="text-muted">Exportar información de usuarios</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-bomberos">
                <div class="card-body">
                        <p>Resumen de emergencias atendidas por el personal.</p>
                        <div class="mb-3">
                            <a href="{{ route('admin.users.export') }}" class="btn btn-bomberos">
                                <i class="bi bi-download me-2"></i>Exportar Usuarios (CSV)
                            </a>
                        </div>

                        @if(isset($totals))
                        <div class="mb-3">
                            <strong>Total Emergencias Médicas:</strong> {{ $totals['total_emergencias'] }}<br>
                            <strong>Total Incendios:</strong> {{ $totals['total_incendios'] }}<br>
                            <strong>Total Partes:</strong> {{ $totals['total_partes'] }}
                        </div>
                        @endif

                        @if(isset($summary) && count($summary) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Emergencias Médicas</th>
                                        <th>Incendios</th>
                                        <th>Total</th>
                                        <th>Detalle</th>
                                        <th>Última Atención</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summary as $row)
                                        <tr>
                                            <td>{{ $row['user']->name }} {{ $row['user']->apellidos }}</td>
                                            <td>{{ $row['user']->dni }}</td>
                                            <td>{{ $row['emergencias'] }}</td>
                                            <td>{{ $row['incendios'] }}</td>
                                            <td>{{ $row['total'] }}</td>
                                            <td>
                                                <a href="{{ route('admin.reports.user', $row['user']->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Detalle</a>
                                            </td>
                                            <td>
                                                @if($row['last_atencion'])
                                                    {{ \Illuminate\Support\Carbon::parse($row['last_atencion'])->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p class="text-muted">No hay partes registrados aún.</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
