<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entite;
use App\Models\User;
use Illuminate\Http\Request;

class EntiteController extends Controller
{
    // Display a listing of the entities
    public function index()
    {
        $entites = Entite::with(['parent', 'responsable'])->get();


        return view('dashboards.admin.entites.index', compact('entites'));
    }

    // Show the form for creating a new entity
    public function create()
    {
        $entites = Entite::all();
        $users = User::all();
        return view('dashboards.admin.entites.create', compact('entites', 'users'));
    }

    // Store a newly created entity
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:entites',
            'parent_id' => 'nullable|exists:entites,id',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        Entite::create($validated);

        return redirect()->route('admin.entites.index')->with('success', 'Entité créée avec succès.');
    }

    // Display the specified entity
    public function show(Entite $entite)
    {
        return view('dashboards.admin.entites.show', compact('entite'));
    }

    // Show the form for editing the entity
    public function edit(Entite $entite)
    {
        $entites = Entite::where('id', '!=', $entite->id)->get();
        $users = User::all();
        return view('dashboards.admin.entites.edit', compact('entite', 'entites', 'users'));
    }

    // Update the specified entity
    public function update(Request $request, Entite $entite)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:entites,code,' . $entite->id,
            'parent_id' => 'nullable|exists:entites,id',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        $entite->update($validated);

        return redirect()->route('admin.entites.index')->with('success', 'Entité mise à jour avec succès.');
    }

    // Remove the specified entity
    public function destroy(Entite $entite)
    {
        // Check if entity has children
        if ($entite->children()->count() > 0) {
            return back()->with('error', 'Cette entité a des sous-entités et ne peut pas être supprimée.');
        }

        $entite->delete();
        return redirect()->route('admin.entites.index')->with('success', 'Entité supprimée avec succès.');
    }
}