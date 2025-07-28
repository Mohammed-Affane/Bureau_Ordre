<?php

namespace App\Http\Controllers\SG;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Affectation;
use Illuminate\Http\Request;
use App\Http\Traits\CourrierFilterTrait;

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
            ->paginate(10);

        return view('dashboards.sg.courriers.arrive',[
            'courriers'=>$courriers
        ]);
    }
}
