@extends('layouts.app')
@section('title', 'Mi Disponibilidad')
@section('content')
<h2 class="mb-1"><i class="bi bi-broadcast me-2"></i>Mi Disponibilidad</h2>
<p class="text-muted">Marca los elementos que están disponibles y desmarca los que no. Solo tú y los administradores ven esta información.</p>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

@if($categories->isEmpty())
    <div class="alert alert-info">El administrador aún no ha definido elementos de disponibilidad.</div>
@else
<form method="POST" action="{{ route('availability.update') }}">
    @csrf @method('PUT')
    @foreach($categories as $cat)
        <div class="card mb-4">
            <div class="card-header bg-white fw-bold"><i class="bi {{ $cat->icono }} me-2"></i>{{ $cat->nombre }}</div>
            <ul class="list-group list-group-flush">
                @foreach($cat->elements as $el)
                    <li class="list-group-item">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="elements[]" value="{{ $el->id }}" id="el{{ $el->id }}" @checked($checks[$el->id] ?? false)>
                            <label class="form-check-label" for="el{{ $el->id }}">{{ $el->nombre }}</label>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
    <button class="btn btn-danger"><i class="bi bi-save me-1"></i>Guardar disponibilidad</button>
</form>
@endif
@endsection