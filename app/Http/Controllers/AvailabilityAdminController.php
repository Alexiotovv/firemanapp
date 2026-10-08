<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityCategory;
use App\Models\AvailabilityElement;
use App\Models\User;
use Illuminate\Http\Request;

class AvailabilityAdminController extends Controller
{
    public function index()
    {
        return view('admin.availability.index', [
            'categories' => AvailabilityCategory::with(['elements' => fn ($q) => $q->orderBy('nombre')])->orderBy('nombre')->get(),
            'users' => User::where('is_admin', false)->orderBy('compania')->orderBy('name')->get(['id', 'name', 'apellidos', 'compania']),
        ]);
    }

    public function userAssignments(User $user)
    {
        abort_if($user->is_admin, 404);

        return response()->json($user->availabilityCategories()->pluck('availability_categories.id'));
    }

    public function toggleAssignment(Request $request, User $user, AvailabilityCategory $category)
    {
        abort_if($user->is_admin, 404);
        $data = $request->validate(['assigned' => 'required|boolean']);

        if ($data['assigned']) {
            $user->availabilityCategories()->syncWithoutDetaching([$category->id]);
        } else {
            $user->availabilityCategories()->detach($category->id);
        }

        return response()->json(['ok' => true, 'assigned' => (bool) $data['assigned']]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'icono' => 'nullable|string|max:50|regex:/^bi-[a-z0-9-]+$/',
        ]);
        AvailabilityCategory::create([
            'nombre' => $data['nombre'],
            'icono' => $data['icono'] ?? 'bi-truck',
        ]);

        return back()->with('success', 'Categoría creada.');
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