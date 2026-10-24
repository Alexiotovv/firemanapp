<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityCategory;
use App\Models\AvailabilityElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AvailabilityAdminController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['user_id' => 'nullable|integer']);
        $users = User::where('is_admin', false)->orderBy('compania')->orderBy('name')->get(['id', 'name', 'apellidos', 'compania']);
        $selectedUser = null;

        if ($request->filled('user_id')) {
            $selectedUser = $users->firstWhere('id', (int) $request->input('user_id'));
            abort_if(! $selectedUser, 404);
        }

        return view('admin.availability.index', [
            'categories' => $selectedUser
                ? $selectedUser->availabilityCategories()->with(['elements' => fn ($q) => $q->orderBy('nombre')])->orderBy('nombre')->get()
                : collect(),
            'templates' => AvailabilityCategory::whereNull('user_id')
                ->with(['elements' => fn ($q) => $q->orderBy('nombre')])
                ->orderBy('nombre')
                ->get(),
            'users' => $users,
            'selectedUser' => $selectedUser,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'icono' => 'nullable|string|max:50|regex:/^bi-[a-z0-9-]+$/',
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('is_admin', 0)],
        ]);
        $user = User::where('is_admin', false)->findOrFail($data['user_id']);
        $user->availabilityCategories()->create([
            'nombre' => $data['nombre'],
            'icono' => $data['icono'] ?? 'bi-truck',
        ]);

        return redirect()->route('admin.availability.index', ['user_id' => $user->id])->with('success', 'Categoría creada.');
    }

    public function copyTemplate(Request $request, AvailabilityCategory $category)
    {
        abort_if($category->user_id !== null, 404);

        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('is_admin', 0)],
        ]);
        $user = User::where('is_admin', false)->findOrFail($data['user_id']);
        $category->loadMissing('elements');

        $copy = $user->availabilityCategories()->create([
            'nombre' => $category->nombre,
            'icono' => $category->icono,
            'activo' => $category->activo,
        ]);

        foreach ($category->elements as $element) {
            $copy->elements()->create(['nombre' => $element->nombre]);
        }

        return redirect()->route('admin.availability.index', ['user_id' => $user->id])
            ->with('success', 'Categoría copiada para el usuario.');
    }

    public function toggleCategory(AvailabilityCategory $category)
    {
        $category->update(['activo' => ! $category->activo]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroyCategory(AvailabilityCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Categoría eliminada.');
    }

    public function storeElement(Request $request, AvailabilityCategory $category)
    {
        $data = $request->validate(['nombre' => 'required|string|max:100']);
        $category->elements()->create($data);

        return back()->with('success', 'Elemento agregado.');
    }

    public function updateElement(Request $request, AvailabilityElement $element)
    {
        $data = $request->validate(['nombre' => 'required|string|max:100']);
        $element->update($data);

        return back()->with('success', 'Elemento actualizado.');
    }

    public function destroyElement(AvailabilityElement $element)
    {
        $element->delete();

        return back()->with('success', 'Elemento eliminado.');
    }
}