<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmergencyController extends Controller
{
    public function create()
    {
        return view('admin.emergencies.create', [
            'tipos' => Emergency::TIPOS,
            'prioridades' => Emergency::PRIORIDADES,
            'users' => User::where('is_admin', false)->orderBy('compania')->orderBy('name')->get(),
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
            'users' => User::where('is_admin', false)->orderBy('compania')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Emergency $emergency)
    {
        $data = $this->validated($request) + $request->validate([
            'estado' => ['required', Rule::in(array_keys(Emergency::ESTADOS))],
            'parte_user_id' => [
                'exclude_unless:estado,finalizada',
                'required',
                'integer',
                Rule::exists('users', 'id')->where('is_admin', 0),
            ],
        ]);

        if (
            $emergency->parteIncendio()->exists()
            && isset($data['parte_user_id'])
            && (int) $data['parte_user_id'] !== (int) $emergency->parte_user_id
        ) {
            return back()->withErrors(['parte_user_id' => 'No se puede cambiar el usuario asignado después de crear el parte.'])->withInput();
        }

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
        $data = $request->validate([
            'estado' => ['required', Rule::in(array_keys(Emergency::ESTADOS))],
            'parte_user_id' => [
                'exclude_unless:estado,finalizada',
                'required',
                'integer',
                Rule::exists('users', 'id')->where('is_admin', 0),
            ],
        ]);

        if ($data['estado'] === 'finalizada' && $emergency->parteIncendio()->exists()) {
            return response()->json(['message' => 'Esta emergencia ya tiene un parte registrado.'], 422);
        }

        $emergency->update([
            'estado' => $data['estado'],
            ...($data['estado'] === 'finalizada' ? ['parte_user_id' => $data['parte_user_id']] : []),
        ]);

        return response()->json(['ok' => true, 'parte_user_id' => $emergency->parte_user_id]);
    }

    public function assignParteUser(Request $request, Emergency $emergency)
    {
        abort_unless($emergency->estado === 'finalizada', 422, 'Solo se puede asignar el parte de una emergencia finalizada.');
        abort_if($emergency->parteIncendio()->exists(), 422, 'Esta emergencia ya tiene un parte registrado.');

        $data = $request->validate([
            'parte_user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('is_admin', 0),
            ],
        ]);

        $emergency->update(['parte_user_id' => $data['parte_user_id']]);

        return back()->with('success', 'Usuario asignado para completar el parte de la emergencia.');
    }

    public function destroy(Emergency $emergency)
    {
        if ($emergency->parteIncendio()->exists()) {
            return back()->with('error', 'No se puede eliminar una emergencia que ya tiene un parte registrado.');
        }

        $emergency->delete();

        return back()->with('success', 'Emergencia eliminada.');
    }
}