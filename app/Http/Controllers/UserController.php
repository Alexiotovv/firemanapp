<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCompanies = User::distinct('compania')->count('compania');
        $totalAdmins = User::where('is_admin', true)->count();
        
        return view('dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalCompanies' => $totalCompanies,
            'totalAdmins' => $totalAdmins,
        ]);
    }

    public function index()
    {
        // Si no es admin, solo puede ver usuarios de su misma compañía
        if (auth()->user()->is_admin) {
            $users = User::all();
        } else {
            $users = User::where('compania', auth()->user()->compania)->get();
        }
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'compania' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'is_admin' => 'boolean',
            'password' => 'required|string|min:6|confirmed',
            'codigo' => 'nullable|string|max:255',
            'grados' => 'nullable|string|max:255',
            'fecha_asenso' => 'nullable|date',
            'fecha_graduacion' => 'nullable|date',
            'curso_basicos' => 'nullable|string|max:255',
            'curso_tecnicos' => 'nullable|string|max:255',
            'curso_liderazgo' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'ubo' => 'nullable|string|max:255',
            'correo_personal' => 'nullable|string|email|max:255',
            'ultimo_cargo' => 'nullable|string|max:255',
            'tipo_sangre' => 'nullable|string|max:10',
        ]);

        User::create([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'compania' => $request->compania,
            'dni' => $request->dni,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin') ? true : false,
            'password' => Hash::make($request->password),
            'codigo' => $request->codigo,
            'grados' => $request->grados,
            'fecha_asenso' => $request->fecha_asenso,
            'fecha_graduacion' => $request->fecha_graduacion,
            'curso_basicos' => $request->curso_basicos,
            'curso_tecnicos' => $request->curso_tecnicos,
            'curso_liderazgo' => $request->curso_liderazgo,
            'telefono' => $request->telefono,
            'ubo' => $request->ubo,
            'correo_personal' => $request->correo_personal,
            'ultimo_cargo' => $request->ultimo_cargo,
            'tipo_sangre' => $request->tipo_sangre,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user)
    {
        // Si no es admin, solo puede ver usuarios de su misma compañía
        if (!auth()->user()->is_admin && $user->compania !== auth()->user()->compania) {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para ver este usuario.');
        }
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Si no es admin, solo puede editar su propio perfil
        if (!auth()->user()->is_admin && $user->id !== auth()->user()->id) {
            return redirect()->route('users.index')->with('error', 'Solo puedes editar tu propio perfil.');
        }
        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Reglas de validación
        $rules = [
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'codigo' => 'nullable|string|max:255',
            'grados' => 'nullable|string|max:255',
            'fecha_asenso' => 'nullable|date',
            'fecha_graduacion' => 'nullable|date',
            'curso_basicos' => 'nullable|string|max:255',
            'curso_tecnicos' => 'nullable|string|max:255',
            'curso_liderazgo' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'ubo' => 'nullable|string|max:255',
            'correo_personal' => 'nullable|string|email|max:255',
            'ultimo_cargo' => 'nullable|string|max:255',
            'tipo_sangre' => 'nullable|string|max:10',
        ];
        
        // Solo admin puede cambiar la compañía
        if (auth()->user()->is_admin) {
            $rules['compania'] = 'required|string|max:255';
        }
        
        $request->validate($rules);

        $data = $request->only([
            'name', 'apellidos', 'dni', 'email', 'codigo', 'grados', 
            'fecha_asenso', 'fecha_graduacion', 'curso_basicos', 'curso_tecnicos', 
            'curso_liderazgo', 'telefono', 'ubo', 'correo_personal', 
            'ultimo_cargo', 'tipo_sangre'
        ]);
        
        // Solo admin puede cambiar la compañía y el rol
        if (auth()->user()->is_admin) {
            $data['compania'] = $request->compania;
            $data['is_admin'] = $request->has('is_admin') ? true : false;
        }
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        // No permitir que un usuario se elimine a sí mismo
        if ($user->id === auth()->user()->id) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Export users and their profile information as CSV (admin only).
     */
    public function export()
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403);
        }

        $users = User::all();

        $columns = [
            'Nombre', 'Apellidos', 'Codigo', 'Grados', 'Fecha Asenso', 'Fecha Graduacion',
            'Curso Basicos', 'Curso Tecnicos', 'Curso Liderazgo', 'Telefono', 'UBO',
            'Correo Personal', 'Correo Institucional', 'Ultimo Cargo', 'Tipo Sangre', 'DNI', 'Compañia', 'Es Admin'
        ];

        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $u) {
                $row = [
                    $u->name,
                    $u->apellidos,
                    $u->codigo,
                    $u->grados,
                    $u->fecha_asenso ? $u->fecha_asenso->format('Y-m-d') : '',
                    $u->fecha_graduacion ? $u->fecha_graduacion->format('Y-m-d') : '',
                    $u->curso_basicos,
                    $u->curso_tecnicos,
                    $u->curso_liderazgo,
                    $u->telefono,
                    $u->ubo,
                    $u->correo_personal,
                    $u->email,
                    $u->ultimo_cargo,
                    $u->tipo_sangre,
                    $u->dni,
                    $u->compania,
                    $u->is_admin ? 'SI' : 'NO'
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        $fileName = 'users_export_' . date('Ymd_His') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Mostrar resumen de emergencias atendidas por personal (para administradores).
     */
    public function reports()
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403);
        }

        // Cargar todos los usuarios
        $users = \App\Models\User::all();

        $summary = [];
        $totalEmergencias = 0;
        $totalIncendios = 0;

        foreach ($users as $u) {
            $emergenciasCount = \App\Models\ParteEmergencia::where('user_id', $u->id)->count();
            $incendiosCount = \App\Models\ParteIncendio::where('user_id', $u->id)->count();

            // Obtener última fecha de atención (max de ambas tablas)
            $lastEmerg = \App\Models\ParteEmergencia::where('user_id', $u->id)->max('fecha');
            $lastInc = \App\Models\ParteIncendio::where('user_id', $u->id)->max('fecha');

            $lastDates = array_filter([$lastEmerg, $lastInc]);
            $lastAtencion = null;
            if (!empty($lastDates)) {
                $lastAtencion = collect($lastDates)->max();
            }

            $summary[] = [
                'user' => $u,
                'emergencias' => $emergenciasCount,
                'incendios' => $incendiosCount,
                'total' => $emergenciasCount + $incendiosCount,
                'last_atencion' => $lastAtencion,
            ];

            $totalEmergencias += $emergenciasCount;
            $totalIncendios += $incendiosCount;
        }

        $totals = [
            'total_emergencias' => $totalEmergencias,
            'total_incendios' => $totalIncendios,
            'total_partes' => $totalEmergencias + $totalIncendios,
        ];

        return view('admin.reports', compact('summary', 'totals'));
    }

    /**
     * Mostrar detalle de partes (emergencias e incendios) de un usuario.
     */
    public function reportUser(\App\Models\User $user)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403);
        }

        $emergencias = \App\Models\ParteEmergencia::where('user_id', $user->id)->orderBy('fecha', 'desc')->get();
        $incendios = \App\Models\ParteIncendio::where('user_id', $user->id)->orderBy('fecha', 'desc')->get();

        return view('admin.report_user', compact('user', 'emergencias', 'incendios'));
    }
}