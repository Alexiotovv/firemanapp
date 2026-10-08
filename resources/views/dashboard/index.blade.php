@extends('layouts.app')

@section('title', 'Dashboard - Bomberos')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
            <p class="text-muted">Bienvenido al sistema de gestión de bomberos</p>
        </div>
    </div>

    @if(auth()->user()->is_admin)
        @include('dashboard._admin')
    @else
        @include('dashboard._emergency_cards')
    @endif

    @unless(auth()->user()->is_admin)

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card users">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>{{ $totalUsers }}</h3>
                        <p class="mb-0">Total de Usuarios</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card companies">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>{{ $totalCompanies }}</h3>
                        <p class="mb-0">Compañías</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>24/7</h3>
                        <p class="mb-0">Servicio Activo</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- En la sección de estadísticas, agregar una nueva tarjeta: -->
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3>{{ $totalAdmins }}</h3>
                        <p class="mb-0">Administradores</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- En la sección de acciones rápidas, condicionar según rol: -->
        @if(auth()->user()->is_admin)
        <div class="col-md-3 mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-bomberos w-100">
                <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
            </a>
        </div>
        @endif

        <!-- En la información del sistema, mostrar el rol: -->
        <div class="alert alert-info mt-3">
            <i class="bi bi-shield-check me-2"></i>
            <small>
                Tu rol actual: 
                <strong>{{ auth()->user()->is_admin ? 'Administrador' : 'Usuario' }}</strong>
                @if(auth()->user()->is_admin)
                    (Tienes acceso completo al sistema)
                @else
                    (Solo puedes ver información de tu compañía: {{ auth()->user()->compania }})
                @endif
            </small>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('users.create') }}" class="btn btn-bomberos w-100">
                                <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100">
                                <i class="bi bi-printer me-2"></i>Reportes
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-success w-100">
                                <i class="bi bi-file-earmark-text me-2"></i>Documentos
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-warning w-100">
                                <i class="bi bi-bell me-2"></i>Alertas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del sistema -->
    <div class="row">
        <div class="col-12">
            <div class="card card-bomberos">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Estado del Sistema</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Autenticación: <strong>Activa</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Base de datos: <strong>Conectada</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Usuarios activos: <strong>{{ $totalUsers }}</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Última actividad</h6>
                            <p>Tu sesión está activa desde: <strong>{{ date('H:i:s') }}</strong></p>
                            <p>Fecha actual: <strong>{{ date('d/m/Y') }}</strong></p>
                            <div class="alert alert-info mt-3">
                                <small>
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    Sistema desarrollado para la Compañía de Bomberos
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endunless
</div>
@endsection

@section('styles')
@if(auth()->user()->is_admin)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endif
<style>
    .dash-muted { color: var(--panel-muted); }
    .dash-panel { background: var(--panel-bg); border: 1px solid var(--panel-border); border-radius: 12px; box-shadow: var(--panel-shadow); display: flex; flex-direction: column; overflow: hidden; }
    .dash-panel-head { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; font-weight: 700; font-size: .85rem; letter-spacing: .03em; }
    .dash-scroll { overflow-y: auto; height: 560px; }
    #dashMap { height: 560px; width: 100%; z-index: 0; }
    [data-bs-theme="dark"] .leaflet-tile-pane { filter: invert(1) hue-rotate(180deg) brightness(.9) contrast(.9); }

    .dash-stat { display: flex; align-items: center; gap: 12px; padding: 12px 14px; background: var(--panel-bg); border: 1px solid var(--panel-border); border-radius: 12px; box-shadow: var(--panel-shadow); }
    .dash-stat-icon { width: 42px; height: 42px; border-radius: 10px; display: grid; place-items: center; color: #fff; font-size: 1.3rem; flex: none; }
    .stat-danger .dash-stat-icon { background: #ef4444; } .stat-primary .dash-stat-icon { background: #3b82f6; }
    .stat-success .dash-stat-icon { background: #22c55e; } .stat-warning .dash-stat-icon { background: #f59e0b; }
    .stat-purple .dash-stat-icon { background: #8b5cf6; }

    .em-item { padding: 10px 14px 10px 12px; border-top: 1px solid var(--panel-border); border-left: 4px solid #94a3b8; cursor: pointer; }
    .em-item:hover { background: var(--panel-hover); }
    .em-item.prio-alta { border-left-color: #ef4444; } .em-item.prio-media { border-left-color: #f59e0b; } .em-item.prio-baja { border-left-color: #22c55e; }

    .res-company { border-top: 1px solid var(--panel-border); padding: 10px 16px; }
    .res-dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
    .res-dot.success { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.2); }
    .res-dot.warning { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,.2); }
    .res-dot.danger { background: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.2); }
    .res-row { display: grid; grid-template-columns: 14px 1fr auto; gap: 10px; align-items: center; padding: 3px 0; font-size: .85rem; }
    .res-status.success { color: #16a34a; } .res-status.warning { color: #d97706; } .res-status.danger { color: #dc2626; }
    [data-bs-theme="dark"] .res-status.success { color: #4ade80; } [data-bs-theme="dark"] .res-status.warning { color: #fbbf24; } [data-bs-theme="dark"] .res-status.danger { color: #f87171; }

    .em-pin { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: grid; place-items: center; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,.4); }
    .em-pin i { transform: rotate(45deg); color: #fff; font-size: .95rem; }

    .em-user-card { background: #dc3545; color: #fff; border-radius: 8px; padding: 22px 24px; box-shadow: 0 4px 10px rgba(220,53,69,.35); border: 1px solid #b02a37; }
    .em-user-icon { width: 46px; height: 46px; border-radius: 8px; background: #fff; display: grid; place-items: center; color: #dc3545; font-size: 1.4rem; flex: none; }
    .em-user-state { color: #ffc107; font-weight: 600; font-size: 1.05rem; }
</style>
@endsection

@section('scripts')
@if(auth()->user()->is_admin)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endif
<script>
(() => {
    const LIVE = ['liveStats', 'liveEmList', 'liveResBody', 'liveCards'];
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const COLORS = {alta: '#ef4444', media: '#f59e0b', baja: '#22c55e'};
    let map = null, layer = null, markers = {};

    function applyFilter() {
        const sel = document.getElementById('resFilter'); if (!sel) return;
        document.querySelectorAll('.res-company').forEach(el => {
            el.style.display = !sel.value || el.dataset.company === sel.value ? '' : 'none';
        });
    }
    document.getElementById('resFilter')?.addEventListener('change', applyFilter);

    function renderMarkers(fit) {
        if (!map) return;
        const data = JSON.parse(document.getElementById('emData')?.textContent || '[]');
        layer.clearLayers(); markers = {};
        data.forEach(e => {
            const icon = L.divIcon({
                className: '', iconSize: [30, 30], iconAnchor: [15, 30],
                html: `<div class="em-pin" style="background:${COLORS[e.prioridad] || '#64748b'}"><i class="bi ${e.icono}"></i></div>`
            });
            const m = L.marker([e.lat, e.lng], {icon}).addTo(layer);
            m.bindTooltip(`#${e.numero}<br>${e.tipo}`, {direction: 'top', offset: [0, -28]});
            markers[e.id] = m;
        });
        if (fit && data.length) { map.fitBounds(data.map(e => [e.lat, e.lng]), {padding: [50, 50], maxZoom: 15}); }
        return data.length;
    }

    @if(auth()->user()->is_admin)
    map = L.map('dashMap', {zoomControl: true}).setView([-3.7491, -73.2538], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
    layer = L.layerGroup().addTo(map);
    if (!renderMarkers(true) && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(p => map.setView([p.coords.latitude, p.coords.longitude], 14), () => {}, {timeout: 8000});
    }

    document.addEventListener('click', e => {
        const item = e.target.closest('.em-item');
        if (!item || e.target.closest('select, button, form')) return;
        const m = markers[item.dataset.id];
        if (m) { map.setView(m.getLatLng(), 16); m.openTooltip(); }
    });

    document.addEventListener('change', async e => {
        const sel = e.target.closest('.em-estado'); if (!sel) return;
        sel.disabled = true;
        try {
            const res = await fetch(sel.dataset.url, {
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({estado: sel.value})
            });
            if (!res.ok) throw new Error();
        } catch (_) { alert('No se pudo actualizar el estado.'); }
        await refresh(true);
    });
    @endif

    async function refresh(force) {
        if (!force && document.activeElement?.closest('.em-item')) return;
        try {
            const res = await fetch(location.href, {headers: {'X-Requested-With': 'XMLHttpRequest'}, credentials: 'same-origin'});
            if (!res.ok) return;
            const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
            LIVE.forEach(id => {
                const cur = document.getElementById(id), next = doc.getElementById(id);
                if (cur && next) cur.innerHTML = next.innerHTML;
            });
            applyFilter(); renderMarkers(false);
        } catch (_) {}
    }
    setInterval(refresh, 30000);
})();
</script>
@endsection