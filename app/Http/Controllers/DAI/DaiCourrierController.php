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
                ->where('statut', 'en_traitement')
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
                ->where('statut', 'en_traitement')
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
}