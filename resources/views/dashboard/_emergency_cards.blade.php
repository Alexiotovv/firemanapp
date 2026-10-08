<div id="liveCards" class="mb-4">
    <h5 class="mb-3"><i class="bi bi-exclamation-octagon-fill text-danger me-2"></i>Emergencias activas</h5>
    <div class="row g-3">
        @forelse($emergencies as $e)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="em-user-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <span class="em-user-icon me-3"><i class="bi bi-circle-fill"></i></span>
                        <h5 class="mb-0 text-uppercase fw-bold">#{{ $e->id }} {{ $e->tipo_label }}</h5>
                    </div>
                    <div class="mb-2">N° Parte: {{ str_replace('-', '', $e->numero) }}</div>
                    <div class="em-user-state mb-3"><i class="bi bi-arrow-repeat me-2"></i>{{ strtoupper($e->estado_label) }}</div>
                    <div>{{ $e->direccion }}@if($e->referencia) <small>({{ $e->referencia }})</small>@endif</div>
                    <div class="mt-1"><i class="bi bi-clock me-2"></i>{{ $e->created_at->format('d/m/Y h:i:s a') }}</div>
                    @if($e->unidades)
                        <div class="mt-1"><i class="bi bi-bus-front-fill me-2"></i>{{ $e->unidades }}</div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-success mb-0"><i class="bi bi-check-circle me-2"></i>No hay emergencias activas en este momento.</div></div>
        @endforelse
    </div>
</div>