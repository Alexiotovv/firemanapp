<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    /** Show profile form for auth user. */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('profile.edit', compact('profile'));
    }
    /** Store profile for auth user or for given user (if admin). */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($request->has('user_id') && auth()->user()->is_admin) {
            $user = User::find($request->user_id) ?? $user;
        }

        $data = $request->validate([
            'codigo' => 'nullable|string|max:255',
            'grados' => 'nullable|string|max:255',
            'fecha_asenso' => 'nullable|date',
            'fecha_graduacion' => 'nullable|date',
            'curso_basicos' => 'nullable|string|max:255',
            'curso_tecnicos' => 'nullable|string|max:255',
            'curso_liderazgo' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'ubo' => 'nullable|string|max:255',
            'correo_personal' => 'nullable|email|max:255',
            'ultimo_cargo' => 'nullable|string|max:255',
            'tipo_sangre' => 'nullable|string|max:10',
        ]);

        $data['user_id'] = $user->id;

        $profile = Profile::updateOrCreate(['user_id' => $user->id], $data);

        return back()->with('success', 'Perfil guardado correctamente.');
    }

    /** Update profile (admin or owner). */
    public function update(Request $request, Profile $profile)
    {
        $user = auth()->user();
        if ($profile->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $data = $request->validate([
            'codigo' => 'nullable|string|max:255',
            'grados' => 'nullable|string|max:255',
            'fecha_asenso' => 'nullable|date',
            'fecha_graduacion' => 'nullable|date',
            'curso_basicos' => 'nullable|string|max:255',
            'curso_tecnicos' => 'nullable|string|max:255',
            'curso_liderazgo' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'ubo' => 'nullable|string|max:255',
            'correo_personal' => 'nullable|email|max:255',
            'ultimo_cargo' => 'nullable|string|max:255',
            'tipo_sangre' => 'nullable|string|max:10',
        ]);

        $profile->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
