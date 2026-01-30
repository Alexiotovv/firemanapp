<?php

namespace App\Http\Controllers;

use App\Models\ParteEmergencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParteEmergenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->is_admin) {
            // Admin ve todos los partes CON PAGINACIÓN
            $partes = ParteEmergencia::orderBy('created_at', 'desc')->paginate(15);
        } else {
            // Usuario regular ve solo sus partes CON PAGINACIÓN
            $partes = ParteEmergencia::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('partes-emergencias.index', compact('partes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clasificaciones = ParteEmergencia::getClasificaciones();
        
        return view('partes-emergencias.create', compact('clasificaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora_salida' => 'required',
            'hora_ingreso' => 'required',
            'piloto_maquina' => 'required|string|max:255',
            'velocimetro_salida' => 'nullable|string|max:50',
            'velocimetro_retorno' => 'nullable|string|max:50',
            
            // Clasificación
            'clasificacion_servicio' => 'required|array',
            'clasificacion_servicio.*' => 'nullable|string',
            
            // Lugar y traslado
            'tipo_lugar' => 'nullable|string|max:255',
            'direccion_incidente' => 'required|string',
            'traslado_a' => 'nullable|string|max:255',
            'persona_responsable' => 'nullable|string|max:255',
            
            // Datos del afectado
            'nombre_afectado' => 'nullable|string|max:255',
            'dni_afectado' => 'nullable|string|max:20',
            'edad_afectado' => 'nullable|integer|min:0|max:150',
            'sexo_afectado' => 'nullable|string|in:M,F',
            
            // Signos vitales
            'presion_arterial' => 'nullable|string|max:50',
            'pulso' => 'nullable|string|max:50',
            'respiracion' => 'nullable|string|max:50',
            'temperatura' => 'nullable|string|max:50',
            'neurologico_consciente' => 'nullable|string|max:50',
            'neurologico_inconsciente' => 'nullable|string|max:50',
            'pupilas' => 'nullable|string|max:50',
            'spo2' => 'nullable|string|max:50',
            
            // Información médica
            'lesiones_observadas' => 'nullable|string',
            'diagnostico_presuntivo' => 'required|string',
            'tratamiento_efectuado' => 'required|string',
            'material_utilizado' => 'nullable|string',
            'observaciones' => 'nullable|string',
            
            // Unidades
            'unidades_cgbvp' => 'nullable|string',
            'unidades_policiales' => 'nullable|string',
            
            // Concurrentes
            'concurrentes' => 'nullable|string',
            'no_concurrentes' => 'nullable|string',
            'total_concurrentes' => 'nullable|integer|min:0',
            'total_no_concurrentes' => 'nullable|integer|min:0',
            
            // Firmas
            'firma_bombero_mando' => 'nullable|string|max:255',
            'firma_bombero_confecciona' => 'required|string|max:255',
        ]);

        // Generar número de parte
        $ultimoNumero = ParteEmergencia::max('numero_parte') ?? 0;
        $validated['numero_parte'] = $ultimoNumero + 1;
        
        // Agregar información del usuario
        $validated['user_id'] = Auth::id();
        $validated['compania_id'] = Auth::user()->compania_id ?? Auth::id();
        
        // Si el usuario no es admin, el parte se marca como completado
        if (!Auth::user()->is_admin) {
            $validated['completado'] = true;
        }

        // Convertir clasificación a JSON
        $validated['clasificacion_servicio'] = json_encode($validated['clasificacion_servicio']);

        // Crear el parte
        $parte = ParteEmergencia::create($validated);

        return redirect()->route('partes-emergencias.show', $parte)
            ->with('success', 'Parte de emergencia creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParteEmergencia $partesEmergencia)
    {
        if (!Auth::user()->is_admin && $partesEmergencia->user_id !== Auth::id()) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'No tienes permiso para ver este parte.');
        }
        
        $clasificaciones = ParteEmergencia::getClasificaciones();
        
        return view('partes-emergencias.show', compact('partesEmergencia', 'clasificaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParteEmergencia $partesEmergencia)
    {
        if ($partesEmergencia->completado && !Auth::user()->is_admin) {
            return redirect()->route('partes-emergencias.show', $partesEmergencia)
                ->with('error', 'No puedes editar un parte ya completado.');
        }
        
        if (!Auth::user()->is_admin && $partesEmergencia->user_id !== Auth::id()) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'No tienes permiso para editar este parte.');
        }
        
        $clasificaciones = ParteEmergencia::getClasificaciones();
        
        return view('partes-emergencias.edit', compact('partesEmergencia', 'clasificaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParteEmergencia $partesEmergencia)
    {
        if (!Auth::user()->is_admin && $partesEmergencia->user_id !== Auth::id()) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'No tienes permiso para actualizar este parte.');
        }
        
        if ($partesEmergencia->completado && !Auth::user()->is_admin) {
            return redirect()->route('partes-emergencias.show', $partesEmergencia)
                ->with('error', 'No puedes editar un parte ya completado.');
        }

        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora_salida' => 'required',
            'hora_ingreso' => 'required',
            'piloto_maquina' => 'required|string|max:255',
            'velocimetro_salida' => 'nullable|string|max:50',
            'velocimetro_retorno' => 'nullable|string|max:50',
            
            // Clasificación
            'clasificacion_servicio' => 'required|array',
            'clasificacion_servicio.*' => 'nullable|string',
            
            // Lugar y traslado
            'tipo_lugar' => 'nullable|string|max:255',
            'direccion_incidente' => 'required|string',
            'traslado_a' => 'nullable|string|max:255',
            'persona_responsable' => 'nullable|string|max:255',
            
            // Datos del afectado
            'nombre_afectado' => 'nullable|string|max:255',
            'dni_afectado' => 'nullable|string|max:20',
            'edad_afectado' => 'nullable|integer|min:0|max:150',
            'sexo_afectado' => 'nullable|string|in:M,F',
            
            // Signos vitales
            'presion_arterial' => 'nullable|string|max:50',
            'pulso' => 'nullable|string|max:50',
            'respiracion' => 'nullable|string|max:50',
            'temperatura' => 'nullable|string|max:50',
            'neurologico_consciente' => 'nullable|string|max:50',
            'neurologico_inconsciente' => 'nullable|string|max:50',
            'pupilas' => 'nullable|string|max:50',
            'spo2' => 'nullable|string|max:50',
            
            // Información médica
            'lesiones_observadas' => 'nullable|string',
            'diagnostico_presuntivo' => 'required|string',
            'tratamiento_efectuado' => 'required|string',
            'material_utilizado' => 'nullable|string',
            'observaciones' => 'nullable|string',
            
            // Unidades
            'unidades_cgbvp' => 'nullable|string',
            'unidades_policiales' => 'nullable|string',
            
            // Concurrentes
            'concurrentes' => 'nullable|string',
            'no_concurrentes' => 'nullable|string',
            'total_concurrentes' => 'nullable|integer|min:0',
            'total_no_concurrentes' => 'nullable|integer|min:0',
            
            // Firmas
            'firma_bombero_mando' => 'nullable|string|max:255',
            'firma_bombero_confecciona' => 'required|string|max:255',
            
            // Campos de estado
            'completado' => 'sometimes|boolean',
            'aprobado' => 'sometimes|boolean',
        ]);

        // Si es admin, puede cambiar el estado
        if (Auth::user()->is_admin) {
            if (isset($validated['completado'])) {
                $partesEmergencia->completado = $validated['completado'];
            }
            if (isset($validated['aprobado'])) {
                $partesEmergencia->aprobado = $validated['aprobado'];
            }
        } elseif (!$partesEmergencia->completado) {
            $validated['completado'] = true;
        }

        // Convertir clasificación a JSON
        $validated['clasificacion_servicio'] = json_encode($validated['clasificacion_servicio']);

        $partesEmergencia->update($validated);

        return redirect()->route('partes-emergencias.show', $partesEmergencia)
            ->with('success', 'Parte de emergencia actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParteEmergencia $partesEmergencia)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'No tienes permiso para eliminar este parte.');
        }
        
        $partesEmergencia->delete();
        
        return redirect()->route('partes-emergencias.index')
            ->with('success', 'Parte de emergencia eliminado exitosamente.');
    }

    /**
     * Marcar como completado
     */
    public function completar(ParteEmergencia $partesEmergencia)
    {
        if (!Auth::user()->is_admin && $partesEmergencia->user_id !== Auth::id()) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'No tienes permiso para completar este parte.');
        }
        
        $partesEmergencia->marcarCompletado();
        
        return redirect()->route('partes-emergencias.show', $partesEmergencia)
            ->with('success', 'Parte marcado como completado.');
    }

    /**
     * Aprobar parte
     */
    public function aprobar(ParteEmergencia $partesEmergencia)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'Solo los administradores pueden aprobar partes.');
        }
        
        $partesEmergencia->aprobar();
        
        return redirect()->route('partes-emergencias.show', $partesEmergencia)
            ->with('success', 'Parte aprobado exitosamente.');
    }

    /**
     * Vista para imprimir
     */
    public function imprimir(ParteEmergencia $partesEmergencia)
    {
        if (!Auth::user()->is_admin && $partesEmergencia->user_id !== Auth::id()) {
            return redirect()->route('partes-emergencias.index')
                ->with('error', 'No tienes permiso para imprimir este parte.');
        }
        
        $clasificaciones = ParteEmergencia::getClasificaciones();
        
        return view('partes-emergencias.imprimir', compact('partesEmergencia', 'clasificaciones'));
    }
}