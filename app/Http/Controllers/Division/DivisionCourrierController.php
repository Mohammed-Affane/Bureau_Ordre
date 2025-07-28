<?php

namespace App\Http\Controllers\Division;

use App\Models\Courrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entite;

class DivisionCourrierController extends Controller{

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
    $courriers = Courrier::where('type_courrier','interne')
    ->where('statut','arriver')
    ->whereHas('affectations', function($query) {
        $query->where('id_affecte_a_utilisateur', auth()->id());
    })
    ->paginate(10);
    return view('dashboards.division.courriers.interne',[
        'courriers'=>$courriers,
    ]);
}
public function divisionCourrierArrive(Request $request)
{
    $courriers = Courrier::where('type_courrier','arrive')
    ->where('statut','arriver')
    ->whereHas('affectations', function($query) {
        $query->where('id_affecte_a_utilisateur', auth()->id());
    })
    ->paginate(10);
    return view('dashboards.division.courriers.arrive',[
        'courriers'=>$courriers,
    ]);
}
}