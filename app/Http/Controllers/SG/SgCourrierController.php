<?php

namespace App\Http\Controllers\SG;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Affectation;
use Illuminate\Http\Request;
use App\Http\Traits\CourrierFilterTrait;
use Illuminate\View\View;

class SgCourrierController extends Controller
{
    use CourrierFilterTrait;
    public function sgCourrierInterne()
    {
        $query= Courrier::where('type_courrier', 'interne')
                ->where('statut', 'en_traitement')
                ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_sg');
            })
            ->with(['affectations' => function($query) {
                $query->where('statut_affectation', 'a_sg');
            }]);
        $courriers =$this->applyCourrierFilters($query,'interne') 
            ->latest()
            ->paginate(10);

        return view('dashboards.sg.courriers.interne',[
            'courriers'=>$courriers,
        ]);
    }

    public function sgCourrierArrive()
    {
        // Get incoming mails with their affectations where status is a_sg
        $query= Courrier::where('type_courrier', 'arrive')
            ->where('statut', 'en_traitement')
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_sg');
            })
            ->with(['affectations' => function($query) {
                $query->where('statut_affectation', 'a_sg');
            }]);
        $courriers =$this->applyCourrierFilters($query,'arrive')
            ->latest()
            ->paginate(10);

        return view('dashboards.sg.courriers.arrive',[
            'courriers'=>$courriers
        ]);
    }


public function recusTraitement(Request $request): View
{
    // Get filter parameters from request
    $statusFilter = $request->query('status', 'all');
    $searchTerm = $request->query('search', '');
    
    // Base query
    $query = Courrier::whereHas('affectations', function ($query) {
        // Filtrer uniquement les affectations vers une division
        $query->whereHas('affecteA.roles', function ($q) {
            $q->where('name', 'chef_division');
        });
    })
    ->with(['affectations' => function($query) {
        $query->with(['traitements', 'affecteA', 'affectePar']);
    }]);
    
    // Apply search filter
    if (!empty($searchTerm)) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('reference_arrive', 'like', '%' . $searchTerm . '%')
              ->orWhere('objet', 'like', '%' . $searchTerm . '%');
        });
    }
    
    // Apply status filter
    if ($statusFilter !== 'all') {
        if ($statusFilter === 'completed') {
            // Filter for completed courriers (all divisions treated and validated)
            $query->whereHas('affectations', function($q) {
                $q->whereHas('traitements', function($t) {
                    $t->where('statut', 'valide');
                });
            });
        } elseif ($statusFilter === 'pending') {
            // Filter for pending courriers (some divisions not treated or not validated)
            $query->where(function($q) {
                $q->whereDoesntHave('affectations.traitements')
                  ->orWhereHas('affectations.traitements', function($t) {
                      $t->where('statut', '!=', 'valide');
                  });
            });
        }
    }
    
    // Paginate results and preserve query parameters
    $courriers = $query->paginate(5)->appends($request->query());
    
    return view('dashboards.sg.traitements.arrive', compact('courriers', 'statusFilter', 'searchTerm'));

}
public function cloturerCourrier(Request $request, $id)
{
    $courrier = Courrier::findOrFail($id);
    

   
    // Mettre à jour le statut du courrier
    $courrier->statut = 'cloture';
    // $courrier->date_cloture = now(); // Décommentez cette ligne
    $courrier->save();
    
    // Supprimez ou commentez le dd() qui bloque l'exécution
    // dd($courrier);
    
    return redirect()->back()->with('success', 'Courrier clôturé avec succès.');
}
}
