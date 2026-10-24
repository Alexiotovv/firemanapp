@extends('layouts.app')
@section('title', 'Disponibilidad - Admin')
@section('content')
<h2 class="mb-1"><i class="bi bi-grid-1x2 me-2"></i>Disponibilidad</h2>
<p class="text-muted">Crea categorías y elementos independientes para cada usuario. Cada usuario marcará cuáles están disponibles.</p>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<div class="card mb-4"><div class="card-header bg-white fw-bold"><i class="bi bi-person-check me-2"></i>Usuario</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.availability.index') }}">
        <select name="user_id" class="form-select" onchange="this.form.submit()">
            <option value="">Selecciona un usuario…</option>
            @foreach($users as $usr)
                <option value="{{ $usr->id }}" @selected($selectedUser?->id === $usr->id)>{{ $usr->compania }} - {{ $usr->name }} {{ $usr->apellidos }}</option>
            @endforeach
        </select>
        </form>
    </div>
</div>

<div class="card mb-4"><div class="card-header bg-white fw-bold">Nueva categoría</div>
    <div class="card-body">
        @if($selectedUser)
        <form method="POST" action="{{ route('admin.availability.categories.store') }}" class="row g-2">
            @csrf
            <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
            <div class="col-md-6"><input name="nombre" class="form-control" placeholder="Ej: Vehículos" required></div>
            <div class="col-md-4"><input name="icono" class="form-control" placeholder="Icono, ej: bi-truck" value="bi-truck"></div>
            <div class="col-md-2"><button class="btn btn-danger w-100">Crear</button></div>
        </form>
        @else
            <p class="text-muted mb-0">Selecciona un usuario para crear una categoría propia.</p>
        @endif
    </div>
</div>

<div class="row g-4">
@if(!$selectedUser)
    <div class="col-12"><div class="alert alert-info">Selecciona un usuario para consultar y administrar sus categorías.</div></div>
@else
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
    <div class="col-12"><div class="alert alert-info">Este usuario aún no tiene categorías.</div></div>
@endforelse
@endif
@if($templates->isNotEmpty())
    <div class="col-12">
        <h3 class="h5 mt-2">Categorías anteriores sin usuario</h3>
        <p class="text-muted">Estas categorías se conservan como plantillas y no se comparten con los usuarios.</p>
    </div>
    @foreach($templates as $template)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white fw-bold"><i class="bi {{ $template->icono }} me-2"></i>{{ $template->nombre }}</div>
                <ul class="list-group list-group-flush">
                    @forelse($template->elements as $element)
                        <li class="list-group-item">{{ $element->nombre }}</li>
                    @empty
                        <li class="list-group-item text-muted">Sin elementos.</li>
                    @endforelse
                </ul>
                @if($selectedUser)
                    <div class="card-footer bg-white">
                        <form method="POST" action="{{ route('admin.availability.categories.copy', $template) }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-copy me-1"></i>Copiar para {{ $selectedUser->name }}</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif
</div>
@endsection