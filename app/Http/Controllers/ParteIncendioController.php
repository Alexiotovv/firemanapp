<?php

namespace App\Http\Controllers;

use App\Models\ParteIncendio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParteIncendioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->is_admin) {
            // Admin ve todos los partes
            $partes = ParteIncendio::orderBy('created_at', 'desc')->get();
        } else {
            // Usuario regular ve solo sus partes
            $partes = ParteIncendio::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('partes-incendios.index', compact('partes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partes-incendios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clasificacion_servicio' => 'required|integer|between:1,12',
            'clasificacion_servicio_texto' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'hora_salida' => 'required',
            'hora_retorno' => 'required',
            'persona_recepciono_aviso' => 'required|string|max:255',
            'maquina' => 'required|string|max:100',
            'mando_maquina' => 'required|string|max:255',
            'piloto_maquina' => 'required|string|max:255',
            'direccion_emergencia' => 'required|string',
            'causa_emergencia' => 'required|string',
            
            // Datos del inmueble/vehículo
            'clase_inmueble' => 'nullable|string|max:100',
            'tipo_construccion' => 'nullable|string|max:100',
            'vehiculo' => 'nullable|string|max:100',
            'modelo_vehiculo' => 'nullable|string|max:100',
            'placa_vehiculo' => 'nullable|string|max:20',
            'color_vehiculo' => 'nullable|string|max:50',
            'marca_vehiculo' => 'nullable|string|max:100',
            'danos_vehiculo' => 'nullable|string',
            
            // Personas lesionadas (como array)
            'personas_lesionadas' => 'nullable|array',
            'personas_lesionadas.*.nombre' => 'nullable|string|max:255',
            'personas_lesionadas.*.documento' => 'nullable|string|max:50',
            'personas_lesionadas.*.lesiones' => 'nullable|string|max:255',
            
            // Trabajo efectuado
            'trabajo_efectuado' => 'required|string',
            'material_utilizado' => 'required|string',
            
            // Unidades de apoyo
            'unidades_apoyo_cgbvp' => 'nullable|string',
            'mando_operaciones_cgbvp' => 'nullable|string|max:255',
            'unidades_policiales' => 'nullable|string',
            
            // Personal asistente (como array)
            'personal_asistente' => 'nullable|array',
            'personal_asistente.*' => 'nullable|string|max:255',
            
            // Otros campos
            'personal_bombero_accidentado' => 'nullable|string',
            'personal_asistente_cuartel' => 'nullable|string',
            'damnificados_incendios' => 'nullable|string',
            'observaciones' => 'nullable|string',
            
            // Firmas
            'firma_bombero_mando' => 'nullable|string|max:255',
            'firma_bombero_confecciona' => 'required|string|max:255',
            'firma_segundo_jefe' => 'nullable|string|max:255',
        ]);

        // Generar número de parte
        $ultimoNumero = ParteIncendio::max('numero_parte') ?? 0;
        $validated['numero_parte'] = $ultimoNumero + 1;
        
        // Agregar información del usuario
        $validated['user_id'] = Auth::id();
        $validated['compania_id'] = Auth::user()->compania_id ?? Auth::id();
        
        // Si el usuario no es admin, el parte se marca como completado
        if (!Auth::user()->is_admin) {
            $validated['completado'] = true;
        }

        // Convertir arrays a JSON
        if (isset($validated['personas_lesionadas'])) {
            $validated['personas_lesionadas'] = json_encode($validated['personas_lesionadas']);
        }
        
        if (isset($validated['personal_asistente'])) {
            $validated['personal_asistente'] = json_encode($validated['personal_asistente']);
        }

        // Crear el parte
        $parte = ParteIncendio::create($validated);

        return redirect()->route('partes-incendios.show', $parte)
            ->with('success', 'Parte de incendio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParteIncendio $partesIncendio)
    {
        // Verificar permisos
        if (!Auth::user()->is_admin && $partesIncendio->user_id !== Auth::id()) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'No tienes permiso para ver este parte.');
        }
        
        return view('partes-incendios.show', compact('partesIncendio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParteIncendio $partesIncendio)
    {
        // Solo admin puede editar partes completados
        if ($partesIncendio->completado && !Auth::user()->is_admin) {
            return redirect()->route('partes-incendios.show', $partesIncendio)
                ->with('error', 'No puedes editar un parte ya completado.');
        }
        
        // Verificar que el usuario sea el creador o admin
        if (!Auth::user()->is_admin && $partesIncendio->user_id !== Auth::id()) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'No tienes permiso para editar este parte.');
        }
        
        return view('partes-incendios.edit', compact('partesIncendio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParteIncendio $partesIncendio)
    {
        // Verificar permisos
        if (!Auth::user()->is_admin && $partesIncendio->user_id !== Auth::id()) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'No tienes permiso para actualizar este parte.');
        }
        
        // Si no es admin y el parte está completado, no puede editar
        if ($partesIncendio->completado && !Auth::user()->is_admin) {
            return redirect()->route('partes-incendios.show', $partesIncendio)
                ->with('error', 'No puedes editar un parte ya completado.');
        }

        $validated = $request->validate([
            'clasificacion_servicio' => 'required|integer|between:1,12',
            'clasificacion_servicio_texto' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'hora_salida' => 'required',
            'hora_retorno' => 'required',
            'persona_recepciono_aviso' => 'required|string|max:255',
            'maquina' => 'required|string|max:100',
            'mando_maquina' => 'required|string|max:255',
            'piloto_maquina' => 'required|string|max:255',
            'direccion_emergencia' => 'required|string',
            'causa_emergencia' => 'required|string',
            
            // Datos del inmueble/vehículo
            'clase_inmueble' => 'nullable|string|max:100',
            'tipo_construccion' => 'nullable|string|max:100',
            'vehiculo' => 'nullable|string|max:100',
            'modelo_vehiculo' => 'nullable|string|max:100',
            'placa_vehiculo' => 'nullable|string|max:20',
            'color_vehiculo' => 'nullable|string|max:50',
            'marca_vehiculo' => 'nullable|string|max:100',
            'danos_vehiculo' => 'nullable|string',
            
            // Personas lesionadas (como array)
            'personas_lesionadas' => 'nullable|array',
            'personas_lesionadas.*.nombre' => 'nullable|string|max:255',
            'personas_lesionadas.*.documento' => 'nullable|string|max:50',
            'personas_lesionadas.*.lesiones' => 'nullable|string|max:255',
            
            // Trabajo efectuado
            'trabajo_efectuado' => 'required|string',
            'material_utilizado' => 'required|string',
            
            // Unidades de apoyo
            'unidades_apoyo_cgbvp' => 'nullable|string',
            'mando_operaciones_cgbvp' => 'nullable|string|max:255',
            'unidades_policiales' => 'nullable|string',
            
            // Personal asistente (como array)
            'personal_asistente' => 'nullable|array',
            'personal_asistente.*' => 'nullable|string|max:255',
            
            // Otros campos
            'personal_bombero_accidentado' => 'nullable|string',
            'personal_asistente_cuartel' => 'nullable|string',
            'damnificados_incendios' => 'nullable|string',
            'observaciones' => 'nullable|string',
            
            // Firmas
            'firma_bombero_mando' => 'nullable|string|max:255',
            'firma_bombero_confecciona' => 'required|string|max:255',
            'firma_segundo_jefe' => 'nullable|string|max:255',
            
            // Campos de estado (solo admin)
            'completado' => 'sometimes|boolean',
            'aprobado' => 'sometimes|boolean',
        ]);

        // Si es admin, puede cambiar el estado
        if (Auth::user()->is_admin) {
            if (isset($validated['completado'])) {
                $partesIncendio->completado = $validated['completado'];
            }
            if (isset($validated['aprobado'])) {
                $partesIncendio->aprobado = $validated['aprobado'];
            }
        } elseif (!$partesIncendio->completado) {
            // Si no es admin y no está completado, marcar como completado al actualizar
            $validated['completado'] = true;
        }

        // Convertir arrays a JSON
        if (isset($validated['personas_lesionadas'])) {
            $validated['personas_lesionadas'] = json_encode($validated['personas_lesionadas']);
        } else {
            $validated['personas_lesionadas'] = $partesIncendio->personas_lesionadas;
        }
        
        if (isset($validated['personal_asistente'])) {
            $validated['personal_asistente'] = json_encode($validated['personal_asistente']);
        } else {
            $validated['personal_asistente'] = $partesIncendio->personal_asistente;
        }

        $partesIncendio->update($validated);

        return redirect()->route('partes-incendios.show', $partesIncendio)
            ->with('success', 'Parte de incendio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParteIncendio $partesIncendio)
    {
        // Solo admin puede eliminar
        if (!Auth::user()->is_admin) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'No tienes permiso para eliminar este parte.');
        }
        
        $partesIncendio->delete();
        
        return redirect()->route('partes-incendios.index')
            ->with('success', 'Parte de incendio eliminado exitosamente.');
    }

    /**
     * Marcar como completado
     */
    public function completar(ParteIncendio $partesIncendio)
    {
        if (!Auth::user()->is_admin && $partesIncendio->user_id !== Auth::id()) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'No tienes permiso para completar este parte.');
        }
        
        $partesIncendio->marcarCompletado();
        
        return redirect()->route('partes-incendios.show', $partesIncendio)
            ->with('success', 'Parte marcado como completado.');
    }

    /**
     * Aprobar parte
     */
    public function aprobar(ParteIncendio $partesIncendio)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'Solo los administradores pueden aprobar partes.');
        }
        
        $partesIncendio->aprobar();
        
        return redirect()->route('partes-incendios.show', $partesIncendio)
            ->with('success', 'Parte aprobado exitosamente.');
    }

    /**
     * Vista para imprimir
     */
    public function imprimir(ParteIncendio $partesIncendio)
    {
        // Verificar permisos
        if (!Auth::user()->is_admin && $partesIncendio->user_id !== Auth::id()) {
            return redirect()->route('partes-incendios.index')
                ->with('error', 'No tienes permiso para imprimir este parte.');
        }
        
        return view('partes-incendios.imprimir', compact('partesIncendio'));
    }
}