@extends('layouts.app')
@section('title', 'Disponibilidad - Admin')
@section('content')
<h2 class="mb-1"><i class="bi bi-grid-1x2 me-2"></i>Disponibilidad</h2>
<p class="text-muted">Crea categorías (Vehículos, Personal, Pilotos…) y agrega los elementos de cada una. Cada usuario marcará cuáles están disponibles.</p>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<div class="card mb-4"><div class="card-header bg-white fw-bold"><i class="bi bi-person-check me-2"></i>Asignar categorías a usuarios</div>
    <div class="card-body">
        <select id="assignUser" class="form-select mb-3">
            <option value="">Selecciona un usuario…</option>
            @foreach($users as $usr)
                <option value="{{ $usr->id }}">{{ $usr->compania }} - {{ $usr->name }} {{ $usr->apellidos }}</option>
            @endforeach
        </select>
        <div id="assignList" class="row g-2"></div>
        <div id="assignMsg" class="small text-muted mt-2"></div>
    </div>
</div>

<div class="card mb-4"><div class="card-header bg-white fw-bold">Nueva categoría</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.availability.categories.store') }}" class="row g-2">
            @csrf
            <div class="col-md-6"><input name="nombre" class="form-control" placeholder="Ej: Vehículos" required></div>
            <div class="col-md-4"><input name="icono" class="form-control" placeholder="Icono, ej: bi-truck" value="bi-truck"></div>
            <div class="col-md-2"><button class="btn btn-danger w-100">Crear</button></div>
        </form>
    </div>
</div>

<div class="row g-4">
@forelse($categories as $cat)
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi {{ $cat->icono }} me-2"></i>{{ $cat->nombre }}
                    @unless($cat->activo)<span class="badge bg-secondary">Inactiva</span>@endunless</span>
                <span class="d-flex gap-1">
                    <form method="POST" action="{{ route('admin.availability.categories.toggle', $cat) }}">@csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-secondary">{{ $cat->activo ? 'Desactivar' : 'Activar' }}</button></form>
                    <form method="POST" action="{{ route('admin.availability.categories.destroy', $cat) }}" onsubmit="return confirm('¿Eliminar categoría y sus elementos?')">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($cat->elements as $el)
                    <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                        <form method="POST" action="{{ route('admin.availability.elements.update', $el) }}" class="input-group input-group-sm">
                            @csrf @method('PUT')
                            <input name="nombre" class="form-control" value="{{ $el->nombre }}" required maxlength="100">
                            <button class="btn btn-outline-primary" title="Guardar cambio"><i class="bi bi-check-lg"></i></button>
                        </form>
                        <form method="POST" action="{{ route('admin.availability.elements.destroy', $el) }}" onsubmit="return confirm('¿Eliminar elemento?')">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></button></form>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Sin elementos.</li>
                @endforelse
            </ul>
            <div class="card-footer bg-white">
                <form method="POST" action="{{ route('admin.availability.elements.store', $cat) }}" class="input-group">
                    @csrf
                    <input name="nombre" class="form-control" placeholder="Nuevo elemento (ej: Ambulancia, Cisterna41)" required maxlength="100">
                    <button class="btn btn-danger"><i class="bi bi-plus-lg"></i> Agregar</button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-12"><div class="alert alert-info">Aún no hay categorías.</div></div>
@endforelse
</div>
@endsection
@php
$catsJs = $categories->map(function ($c) {
    return ['id' => $c->id, 'nombre' => $c->nombre, 'icono' => $c->icono, 'activo' => $c->activo, 'n' => $c->elements->count()];
})->values();
@endphp
@section('scripts')
<script>
(() => {
    const cats = @json($catsJs);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const sel = document.getElementById('assignUser'), list = document.getElementById('assignList'), msg = document.getElementById('assignMsg');
    const base = "{{ url('admin/disponibilidad/usuarios') }}";
    const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    sel.addEventListener('change', async () => {
        list.innerHTML = ''; msg.textContent = '';
        if (!sel.value) return;
        const res = await fetch(`${base}/${sel.value}/asignaciones`, {headers: {'Accept': 'application/json'}});
        const assigned = new Set(await res.json());
        cats.forEach(c => {
            const col = document.createElement('div');
            col.className = 'col-md-4';
            col.innerHTML = `<div class="form-check form-switch border rounded p-2 ps-5">
                <input class="form-check-input" type="checkbox" id="as${c.id}" data-id="${c.id}" ${assigned.has(c.id) ? 'checked' : ''}>
                <label class="form-check-label" for="as${c.id}"><i class="bi ${esc(c.icono)} me-1"></i>${esc(c.nombre)}
                <span class="badge bg-light text-dark">${c.n}</span>${c.activo ? '' : ' <span class="badge bg-secondary">Inactiva</span>'}</label></div>`;
            list.appendChild(col);
        });
    });

    list.addEventListener('change', async e => {
        const input = e.target; if (!input.dataset.id) return;
        const wanted = input.checked; input.disabled = true;
        try {
            const res = await fetch(`${base}/${sel.value}/categorias/${input.dataset.id}`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({assigned: wanted})
            });
            if (!res.ok) throw new Error();
            msg.textContent = wanted ? 'Categoría asignada.' : 'Categoría quitada.';
        } catch (_) {
            input.checked = !wanted; msg.textContent = 'No se pudo guardar el cambio.';
        } finally { input.disabled = false; }
    });
})();
</script>
@endsection