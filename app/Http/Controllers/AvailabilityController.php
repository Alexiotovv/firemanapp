<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityCategory;
use App\Models\AvailabilityCheck;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->availabilityCategories()->where('activo', true)
            ->with(['elements' => fn ($q) => $q->orderBy('nombre')])
            ->orderBy('nombre')->get()
            ->filter(fn ($c) => $c->elements->isNotEmpty());

        $checks = AvailabilityCheck::where('user_id', auth()->id())->pluck('disponible', 'element_id');

        return view('availability.index', compact('categories', 'checks'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'elements' => 'nullable|array',
            'elements.*' => 'integer',
        ]);
        $checked = collect($data['elements'] ?? []);

        $elements = auth()->user()->availabilityCategories()->where('activo', true)->with('elements')->get()
            ->flatMap->elements;

        foreach ($elements as $el) {
            AvailabilityCheck::updateOrCreate(
                ['element_id' => $el->id, 'user_id' => auth()->id()],
                ['disponible' => $checked->contains($el->id)]
            );
        }

        return back()->with('success', 'Disponibilidad actualizada.');
    }
}