<div class="dash-panel h-100">
    <div class="dash-panel-head">
        <span><i class="bi bi-truck-front-fill text-primary me-2"></i>RECURSOS DISPONIBLES</span>
        <a href="{{ route('admin.availability.index') }}" class="text-decoration-none text-secondary" title="Configurar"><i class="bi bi-gear"></i></a>
    </div>
    <div class="px-3 pb-2">
        <select id="resFilter" class="form-select form-select-sm">
            <option value="">Todas las compañías</option>
            @foreach($availability->keys() as $comp)
                <option value="{{ $comp }}">{{ $comp }}</option>
            @endforeach
        </select>
    </div>
    <div class="dash-scroll" id="liveResBody">
        @forelse($availability as $compania => $g)
            @php
                $total = $g->rows->count();
                $ok = $g->rows->where('disponible', true)->count();
                if ($ok === $total) { [$label, $cls] = ['Operativa', 'success']; }
                elseif ($ok === 0) { [$label, $cls] = ['No operativa', 'danger']; }
                else { [$label, $cls] = ['Operativa limitada', 'warning']; }
                $last = $g->rows->max('updated_at');
            @endphp
            <div class="res-company" data-company="{{ $compania }}">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <div class="fw-bold text-uppercase small"><i class="bi bi-truck-front-fill me-1 text-{{ $cls }}"></i>{{ $compania }}</div>
                        <small class="res-status {{ $cls }}">{{ $label }}</small>
                    </div>
                    <small class="dash-muted text-end" style="font-size:.7rem">Últ. act.: {{ $last ? $last->format('H:i') : '—' }}</small>
                </div>
                @foreach($g->rows as $row)
                    <div class="res-row">
                        <span class="res-dot {{ $row->disponible ? 'success' : 'danger' }}"></span>
                        <span class="text-truncate" title="{{ $row->category->nombre }}@if($g->multi) · {{ $row->user }}@endif"><strong>{{ $row->nombre }}</strong>
                            <small class="dash-muted">· {{ $row->category->nombre }}</small></span>
                        <span class="res-status {{ $row->disponible ? 'success' : 'danger' }}">{{ $row->disponible ? 'Disponible' : 'No disponible' }}</span>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="p-4 text-center dash-muted">Aún no hay elementos asignados. <a href="{{ route('admin.availability.index') }}">Configurar</a></div>
        @endforelse
    </div>
</div>