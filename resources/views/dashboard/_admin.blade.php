@php
    $emJson = $emergencies->map(function ($e) {
        return [
            'id' => $e->id, 'numero' => $e->numero, 'tipo' => $e->tipo_label, 'icono' => $e->tipo_icono,
            'prioridad' => $e->prioridad, 'lat' => $e->lat, 'lng' => $e->lng,
        ];
    })->values();
@endphp

<div id="liveStats" class="row g-2 mb-3">
    @php
        $cards = [
            ['Emergencias Activas', $stats['activas'], 'bi-fire', 'danger'],
            ['Unidades en Servicio', $stats['servicio'], 'bi-truck-front-fill', 'primary'],
            ['Unidades Disponibles', $stats['disponibles'], 'bi-check-lg', 'success'],
            ['Unidades Fuera de Servicio', $stats['fuera'], 'bi-wrench-adjustable', 'warning'],
            ['Compañías Operativas', $stats['companias_ok'] . ' / ' . $stats['companias'], 'bi-building', 'purple'],
        ];
    @endphp
    @foreach($cards as [$label, $value, $icon, $color])
        <div class="col-6 col-md-4 col-xl">
            <div class="dash-stat stat-{{ $color }}">
                <span class="dash-stat-icon"><i class="bi {{ $icon }}"></i></span>
                <div><div class="small dash-muted">{{ $label }}</div><div class="fs-4 fw-bold lh-1">{{ $value }}</div></div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-xl-3 col-lg-4">
        <div class="dash-panel h-100">
            <div class="dash-panel-head">
                <span><i class="bi bi-fire text-danger me-2"></i>EMERGENCIAS ACTIVAS
                    <span class="badge bg-danger ms-1">{{ $emergencies->count() }}</span></span>
                <a href="{{ route('admin.emergencies.create') }}" class="btn btn-sm btn-danger py-0"><i class="bi bi-plus-lg"></i> Nueva</a>
            </div>
            <div class="dash-scroll" id="liveEmList">
                <script type="application/json" id="emData">@json($emJson)</script>
                @forelse($emergencies as $e)
                    <div class="em-item prio-{{ $e->prioridad }}" data-id="{{ $e->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small">#{{ $e->numero }}
                                <span class="badge {{ $e->prioridad === 'alta' ? 'bg-danger' : ($e->prioridad === 'media' ? 'bg-warning text-dark' : 'bg-success') }}">{{ strtoupper($e->prioridad) }}</span></span>
                            <span class="small dash-muted">{{ $e->created_at->format('H:i') }}</span>
                        </div>
                        <div class="mt-1"><i class="bi {{ $e->tipo_icono }} me-1"></i>{{ $e->tipo_label }}</div>
                        <div class="small dash-muted text-truncate" title="{{ $e->direccion }}">{{ $e->direccion }}</div>
                        @if($e->unidades)<div class="small dash-muted"><i class="bi bi-truck me-1"></i>{{ $e->unidades }}</div>@endif
                        <div class="d-flex gap-1 mt-2 align-items-center">
                            <select class="form-select form-select-sm em-estado" data-url="{{ route('admin.emergencies.estado', $e) }}">
                                @foreach(\App\Models\Emergency::ESTADOS as $k => $lbl)
                                    <option value="{{ $k }}" @selected($e->estado === $k)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.emergencies.edit', $e) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.emergencies.destroy', $e) }}" onsubmit="return confirm('¿Eliminar esta emergencia?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center dash-muted"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>Sin emergencias activas</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-5 col-lg-8">
        <div class="dash-panel h-100">
            <div class="dash-panel-head"><span><i class="bi bi-map text-primary me-2"></i>MAPA DE EMERGENCIAS</span></div>
            <div id="dashMap"></div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-12">
        @include('dashboard._availability')
    </div>
</div>