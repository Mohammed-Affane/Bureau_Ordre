<?php

namespace App\Http\Controllers\Division;

use App\Models\Entite;
use App\Models\Courrier;
use App\Models\Traitement;
use App\Models\Affectation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\CourrierFilterTrait;

class DivisionCourrierController extends Controller{
  use CourrierFilterTrait;

public function index(Request $request)
{
    // 1. Get the authenticated user's entity
    $entite = auth()->user()->entite;

    if (!$entite) {
        return redirect()->back()
               ->with('error', 'Vous n\'êtes responsable d\'aucune division');
    }

    // 2. Build the query for courriers assigned to this division
    $query = Courrier::where(function($query) use ($entite) {
            // Courriers affectés à l'utilisateur de cette division
            $query->whereHas('affectations', function($q) {
                $q->where('id_affecte_a_utilisateur', auth()->id());
            });
        })
        ->with([
            'expediteur',
            'courrierDestinatairePivot.entite',
            'affectations' => function($query) {
                $query->with(['affecteA', 'affectePar'])
                      ->where('id_affecte_a_utilisateur', auth()->id());
            }
        ]);

    // 3. Apply search filters if provided
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('reference_arrive', 'like', '%'.$request->search.'%')
              ->orWhere('reference_bo', 'like', '%'.$request->search.'%')
              ->orWhere('objet', 'like', '%'.$request->search.'%')
              ->orWhereHas('expediteur', function($q) use ($request) {
                  $q->where('nom', 'like', '%'.$request->search.'%');
                  $q->orWhere('CIN', 'like', '%'.$request->search.'%');
              });
        });
    }

    // 4. Apply status filter
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // 5. Apply priority filter
    if ($request->filled('priorite')) {
        $query->where('priorite', $request->priorite);
    }

    // 6. Apply date filters
    if ($request->filled('date_range')) {
        switch ($request->date_range) {
            case 'today':
                $query->whereDate('date_reception', today());
                break;
            case 'week':
                $query->whereBetween('date_reception', 
                    [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('date_reception', 
                    [now()->startOfMonth(), now()->endOfMonth()]);
                break;
            case 'year':
                $query->whereBetween('date_reception', 
                    [now()->startOfYear(), now()->endOfYear()]);
                break;
            case 'custom':
                if ($request->filled('date_from') && $request->filled('date_to')) {
                    $query->whereBetween('date_reception', 
                        [$request->date_from, $request->date_to]);
                }
                break;
        }
    }

    // 7. Get paginated results
    $courriers = $query->orderBy('date_reception', 'desc')
                      ->paginate(20);

    return view('dashboards.division.courriers.index', [
        'courriers' => $courriers,
        'entite' => $entite,
        'searchParams' => $request->all() // Pass all search params to view
    ]);
}
public function divisionCourrierInterne(Request $request)
{
    $query = Courrier::where('type_courrier','interne')
    ->where('statut','arriver')
    ->whereHas('affectations', function($query) {
        $query->where('id_affecte_a_utilisateur', auth()->id());
    });
    $courriers = $this->applyCourrierFilters($query, 'interne')
    ->latest()
    ->paginate(10);
    
    return view('dashboards.division.courriers.interne',[
        'courriers'=>$courriers,
    ]);
}
public function divisionCourrierArrive(Request $request)
{
    $query = Courrier::where('type_courrier','arrive')
    ->where('statut','arriver')
    ->whereHas('affectations', function($query) {
        $query->where('id_affecte_a_utilisateur', auth()->id());
    });
    $courriers = $this->applyCourrierFilters($query, 'arrive')
    ->latest()
    ->paginate(10);
    return view('dashboards.division.courriers.arrive',[
        'courriers'=>$courriers,
    ]);
}
public function showTraitement(Affectation $affectation)

{

   // Vérifier que l'affectation est bien pour l'utilisateur connecté
        if ($affectation->id_affecte_a_utilisateur !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à traiter cette affectation");
        }

        return view('courriers.traitements', compact('affectation'));

}
public function storeTraitement(Request $request, Traitement $Traitement,Affectation $affectation)
{
    $validated = $request->validate([
        'action' => 'required|string|max:2000',
    ]);

    $Traitement->action = $validated['action'];
    $Traitement->statut = 'valide';// or another field name
    $Traitement->date_traitement = now(); // optional: track date of processing
    $Traitement->id_affectation = $affectation->id;
    $Traitement->save();

    return redirect()->route('division.courriers.arrive')
        ->with('success', 'Traitement du courrier enregistré avec succès.');
}

}