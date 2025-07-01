<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EntiteController extends Controller
{

    public function index()
    {
        $entites = Entite::latest()->paginate(10);
        return view('dashboards.admin.entites.index', compact('entites'));

    }


    public function create()
    {
        $users = User::all();
        $entites = Entite::all();
        return view('dashboards.admin.entites.create', compact('users', 'entites'));

    }


    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:entites,id',
        'responsable_id' => 'required|exists:users,id',
    ]);

    try {
        // Start a database transaction
        DB::beginTransaction();

        // Create the entity using mass assignment
        $entite = Entite::create([
            'nom' => $validatedData['nom'],
            'type' => $validatedData['type'],
            'code' => $validatedData['code'],
            'parent_id' => $validatedData['parent_id'] ?? null,
            'responsable_id' => $validatedData['responsable_id'],
        ]);

        // Commit the transaction
        DB::commit();

        // Redirect with success message
        return redirect()
            ->route('admin.entites.index')
            ->with('success', 'Entité créée avec succès.');
    } catch (\Exception $e) {
        // Rollback transaction if something goes wrong
        DB::rollBack();

        // Log the exact error
        Log::error('Erreur lors de la création de l\'entité: ' . $e->getMessage());

        // Redirect back with error message and old input
        return back()
            ->with('error', 'Une erreur est survenue lors de la création de l\'entité.')
            ->withInput();
    }
}


    public function show(Entite $entite)
    {
        return view('dashboards.admin.entites.show', compact('entite'));

    }


    public function edit(Entite $entite)
    {
        $users = User::all();
        $entites = Entite::all();
        return view('dashboards.admin.entites.edit', compact('entite', 'users', 'entites'));
    }


public function update(Request $request, Entite $entite)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:entites,id',
        'responsable_id' => 'required|exists:users,id',
    ]);

    try {
        // Start a database transaction
        DB::beginTransaction();

        // Update the entity using validated data
        $entite->update([
            'nom' => $validatedData['nom'],
            'type' => $validatedData['type'],
            'code' => $validatedData['code'],
            'parent_id' => $validatedData['parent_id'] ?? null,
            'responsable_id' => $validatedData['responsable_id'],
        ]);

        // Commit the transaction
        DB::commit();

        // Redirect with success message
        return redirect()
            ->route('admin.entites.index')
            ->with('success', 'Entité mise à jour avec succès.');
    } catch (\Exception $e) {
        // Rollback transaction if something goes wrong
        DB::rollBack();

        // Log the exact error
        Log::error('Erreur lors de la mise à jour de l\'entité: ' . $e->getMessage());

        // Redirect back with error message and old input
        return back()
            ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'entité.')
            ->withInput();
    }
}


    public function destroy(Entite $entite)
    {
        try {
            DB::beginTransaction();

            $entite->delete();

            DB::commit();

            return redirect()
                ->route('admin.entites.index')
                ->with('success', 'Entity deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the entity.');
        }
    }
}
