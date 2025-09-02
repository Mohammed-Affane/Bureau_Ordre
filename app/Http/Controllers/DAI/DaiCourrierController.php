<?php

namespace App\Http\Controllers\DAI;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Affectation;
use Illuminate\Http\Request;
use App\Http\Traits\CourrierFilterTrait;
use Illuminate\View\View;

class DaiCourrierController extends Controller
{
    use CourrierFilterTrait;
    
    /**
     * Affiche les courriers internes assignés à la DAI
     */
    public function daiCourrierInterne()
    {
        $query = Courrier::where('type_courrier', 'interne')
                ->where('statut', 'arriver')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_div');
                })
                ->with(['affectations' => function($query) {
                    $query->where('statut_affectation', 'a_div');
                }]);
                
        $courriers = $this->applyCourrierFilters($query, 'interne')
            ->paginate(10);

        return view('dashboards.dai.courriers.interne', [
            'courriers' => $courriers,
        ]);
    }

    /**
     * Affiche les courriers arrivés assignés à la DAI
     */
    public function daiCourrierArrive()
    {
        $query = Courrier::where('type_courrier', 'arrive')
                ->where('statut', 'arriver')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_div');
                })
                ->with(['affectations' => function($query) {
                    $query->where('statut_affectation', 'a_div');
                }]);
                
        $courriers = $this->applyCourrierFilters($query, 'arrive')
            ->paginate(10);

        return view('dashboards.dai.courriers.arrive', [
            'courriers' => $courriers,
        ]);
    }

    /**
     * Affiche les courriers en cours de traitement par les divisions
     */
    public function recusTraitement(): View
    {
        // Charger tous les courriers avec leurs affectations et traitements
        $courriers = Courrier::whereHas('affectations', function ($query) {
            // Filtrer uniquement les affectations vers une division
            $query->whereHas('affecteA.roles', function ($q) {
                $q->where('name', 'chef_division');
            });
        })
        ->with(['affectations' => function($query) {
            $query->with(['traitements', 'affecteA', 'affectePar']);
        }])
        ->paginate(5);

        return view('dashboards.dai.traitements.arrive', compact('courriers'));
    }

    /**
     * Clôture un courrier
     */
    public function cloturerCourrier(Request $request, $id)
    {
        $courrier = Courrier::findOrFail($id);
        
        // Mettre à jour le statut du courrier
        $courrier->statut = 'cloture';
        $courrier->save();
        
        return redirect()->back()->with('success', 'Courrier clôturé avec succès.');
    }
    
    /**
     * Affiche les courriers en retard
     */
    public function courriersEnRetard()
    {
        $query = Courrier::where('statut', 'en_traitement')
                ->whereDate('delais', '<', now())
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_dai');
                })
                ->with(['affectations' => function($query) {
                    $query->where('statut_affectation', 'a_dai');
                }]);
                
        $courriers = $this->applyCourrierFilters($query, null)
            ->paginate(10);

        return view('dashboards.dai.courriers.retard', [
            'courriers' => $courriers,
        ]);
    }
}