<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmergencyController extends Controller
{
    public function create()
    {
        return view('admin.emergencies.create', [
            'tipos' => Emergency::TIPOS,
            'prioridades' => Emergency::PRIORIDADES,
        ]);
    }

    public function store(Request $request)
    {
        Emergency::create($this->validated($request) + [
            'numero' => Emergency::nextNumero(),
            'estado' => 'en_evaluacion',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Emergencia registrada.');
    }

    public function edit(Emergency $emergency)
    {
        return view('admin.emergencies.create', [
            'emergency' => $emergency,
            'tipos' => Emergency::TIPOS,
            'prioridades' => Emergency::PRIORIDADES,
            'estados' => Emergency::ESTADOS,
        ]);
    }

    public function update(Request $request, Emergency $emergency)
    {
        $data = $this->validated($request) + $request->validate([
            'estado' => ['required', Rule::in(array_keys(Emergency::ESTADOS))],
        ]);
        $emergency->update($data);

        return redirect()->route('dashboard')->with('success', 'Emergencia actualizada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', Rule::in(array_keys(Emergency::TIPOS))],
            'prioridad' => ['required', Rule::in(array_keys(Emergency::PRIORIDADES))],
            'direccion' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:2000',
            'llamante' => 'nullable|string|max:120',
            'telefono' => 'nullable|string|max:30',
            'unidades' => 'nullable|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ], [
            'lat.required' => 'Selecciona la ubicación en el mapa.',
            'lng.required' => 'Selecciona la ubicación en el mapa.',
        ]);
    }
    public function updateEstado(Request $request, Emergency $emergency)
    {
        $data = $request->validate(['estado' => ['required', Rule::in(array_keys(Emergency::ESTADOS))]]);
        $emergency->update($data);

        return response()->json(['ok' => true]);
    }

    public function destroy(Emergency $emergency)
    {
        $emergency->delete();

        return back()->with('success', 'Emergencia eliminada.');
    }
}