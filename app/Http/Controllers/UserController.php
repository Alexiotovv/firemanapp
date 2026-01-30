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

        $data = $request->only(['name', 'apellidos', 'dni', 'email']);
        
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
}