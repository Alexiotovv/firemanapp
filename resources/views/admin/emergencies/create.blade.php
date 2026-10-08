@extends('layouts.app')
@section('title', 'Nueva emergencia')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    #pickMap { height: 520px; border-radius: 10px; border: 1px solid var(--panel-border); }
    [data-bs-theme="dark"] .leaflet-tile-pane { filter: invert(1) hue-rotate(180deg) brightness(.9) contrast(.9); }
</style>
@endsection

@section('content')
<h2 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Nueva emergencia</h2>
<p class="text-muted">Completa los datos y marca la ubicación en el mapa (haz clic o arrastra el marcador).</p>

@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<form method="POST" action="{{ route('admin.emergencies.store') }}">
    @csrf
    <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
    <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card"><div class="card-body row g-3">
                <div class="col-md-7">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        @foreach($tipos as $k => $t)
                            <option value="{{ $k }}" @selected(old('tipo') === $k)>{{ $t[0] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Prioridad</label>
                    <select name="prioridad" class="form-select" required>
                        @foreach($prioridades as $k => $label)
                            <option value="{{ $k }}" @selected(old('prioridad', 'media') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Dirección</label>
                    <input name="direccion" class="form-control" value="{{ old('direccion') }}" required maxlength="255" placeholder="Ej: Jr. Amazonas 123 - Punchana"></div>
                <div class="col-12"><label class="form-label">Referencia</label>
                    <input name="referencia" class="form-control" value="{{ old('referencia') }}" maxlength="255"></div>
                <div class="col-12"><label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" maxlength="2000">{{ old('descripcion') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Llamante</label>
                    <input name="llamante" class="form-control" value="{{ old('llamante') }}" maxlength="120"></div>
                <div class="col-md-6"><label class="form-label">Teléfono</label>
                    <input name="telefono" class="form-control" value="{{ old('telefono') }}" maxlength="30"></div>
                <div class="col-12"><label class="form-label">Unidades despachadas</label>
                    <input name="unidades" class="form-control" value="{{ old('unidades') }}" maxlength="255" placeholder="Ej: M28-1 CIST-11 ESC-13"></div>
            </div></div>
        </div>
        <div class="col-lg-7">
            <div id="pickMap"></div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted" id="coords">Sin ubicación seleccionada</small>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="locate"><i class="bi bi-crosshair me-1"></i>Mi ubicación</button>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-danger"><i class="bi bi-megaphone me-1"></i>Registrar emergencia</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(() => {
    const DEFAULT = [-3.7491, -73.2538];
    const latEl = document.getElementById('lat'), lngEl = document.getElementById('lng'), info = document.getElementById('coords');
    const map = L.map('pickMap').setView(DEFAULT, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
    let marker = null;

    function setPoint(lat, lng, pan) {
        latEl.value = lat.toFixed(7); lngEl.value = lng.toFixed(7);
        info.textContent = `Lat ${lat.toFixed(5)}, Lng ${lng.toFixed(5)}`;
        if (!marker) {
            marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            marker.on('dragend', () => { const p = marker.getLatLng(); setPoint(p.lat, p.lng, false); });
        } else { marker.setLatLng([lat, lng]); }
        if (pan) map.setView([lat, lng], Math.max(map.getZoom(), 16));
    }

    map.on('click', e => setPoint(e.latlng.lat, e.latlng.lng, false));

    function locate() {
        if (!navigator.geolocation) return;
        navigator.geolocation.getCurrentPosition(
            pos => setPoint(pos.coords.latitude, pos.coords.longitude, true),
            () => info.textContent = 'No se pudo obtener tu ubicación; haz clic en el mapa.',
            {enableHighAccuracy: true, timeout: 8000}
        );
    }
    document.getElementById('locate').addEventListener('click', locate);

    if (latEl.value && lngEl.value) { setPoint(parseFloat(latEl.value), parseFloat(lngEl.value), true); }
    else { locate(); }
})();
</script>
@endsection
