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
        
        $availability = collect();
        if (auth()->user()->is_admin) {
            $categories = \App\Models\AvailabilityCategory::where('activo', true)
                ->with(['elements' => fn ($q) => $q->orderBy('nombre')])->get()->keyBy('id');
            $elements = $categories->flatMap->elements;
            $assigned = \Illuminate\Support\Facades\DB::table('availability_category_user')->get()->groupBy('user_id');
            $checks = \App\Models\AvailabilityCheck::whereIn('element_id', $elements->pluck('id'))->get()
                ->keyBy(fn ($c) => $c->user_id . '-' . $c->element_id);

            $availability = User::where('is_admin', false)->orderBy('name')->get()
                ->groupBy(fn ($usr) => $usr->compania ?: 'Sin compañía')->sortKeys()
                ->map(function ($users) use ($categories, $checks, $assigned) {
                    $rows = collect();
                    foreach ($users as $usr) {
                        foreach ($assigned->get($usr->id, collect())->pluck('category_id') as $catId) {
                            $cat = $categories->get($catId);
                            if (! $cat) { continue; }
                            foreach ($cat->elements as $el) {
                                $chk = $checks->get($usr->id . '-' . $el->id);
                                $rows->push((object) [
                                    'user' => $usr->name,
                                    'category' => $cat,
                                    'nombre' => $el->nombre,
                                    'disponible' => (bool) ($chk?->disponible),
                                    'updated_at' => $chk?->updated_at,
                                ]);
                            }
                        }
                    }
                    return (object) ['rows' => $rows, 'multi' => $users->count() > 1];
                })->filter(fn ($g) => $g->rows->isNotEmpty());
        }
        $rows = $availability->flatMap(fn ($g) => $g->rows);
        $stats = [
            'activas' => \App\Models\Emergency::activas()->count(),
            'servicio' => $rows->count(),
            'disponibles' => $rows->where('disponible', true)->count(),
            'fuera' => $rows->where('disponible', false)->count(),
            'companias_ok' => $availability->filter(fn ($g) => $g->rows->where('disponible', true)->isNotEmpty())->count(),
            'companias' => $availability->count(),
        ];

        return view('dashboard.index', [
            'stats' => $stats,
            'emergencies' => \App\Models\Emergency::activas()->latest()->get(),
            'availability' => $availability,
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
        ]);

        User::create([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'compania' => $request->compania,
            'dni' => $request->dni,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin') ? true : false,
            'password' => Hash::make($request->password),
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
        ];
        
        // Solo admin puede cambiar la compañía
        if (auth()->user()->is_admin) {
            $rules['compania'] = 'required|string|max:255';
        }
        
        $request->validate($rules);

        $data = $request->only([
            'name', 'apellidos', 'dni', 'email'
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

        // Export profiles joined with users. If a user has no profile, still export basic user info.
        $profiles = \App\Models\Profile::with('user')->get();

        $columns = [
            'Nombre', 'Apellidos', 'DNI', 'Compañia', 'Email Institucional',
            'Codigo', 'Grados', 'Fecha Asenso', 'Fecha Graduacion',
            'Curso Basicos', 'Curso Tecnicos', 'Curso Liderazgo', 'Telefono', 'UBO',
            'Correo Personal', 'Ultimo Cargo', 'Tipo Sangre', 'Es Admin'
        ];

        $callback = function() use ($profiles, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($profiles as $p) {
                $u = $p->user;
                $row = [
                    $u->name ?? '',
                    $u->apellidos ?? '',
                    $u->dni ?? '',
                    $u->compania ?? '',
                    $u->email ?? '',
                    $p->codigo,
                    $p->grados,
                    $p->fecha_asenso ? $p->fecha_asenso->format('Y-m-d') : '',
                    $p->fecha_graduacion ? $p->fecha_graduacion->format('Y-m-d') : '',
                    $p->curso_basicos,
                    $p->curso_tecnicos,
                    $p->curso_liderazgo,
                    $p->telefono,
                    $p->ubo,
                    $p->correo_personal,
                    $p->ultimo_cargo,
                    $p->tipo_sangre,
                    $u->is_admin ? 'SI' : 'NO'
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        $fileName = 'profiles_export_' . date('Ymd_His') . '.csv';

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