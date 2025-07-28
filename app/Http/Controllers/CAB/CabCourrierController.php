<?php

namespace App\Http\Controllers\CAB;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Affectation;
use Illuminate\Http\Request;
use App\Http\Traits\CourrierFilterTrait;

class CabCourrierController extends Controller
{
    use CourrierFilterTrait;
    public function cabCourrierInterne()
    {
        $query= Courrier::where('type_courrier', 'interne')
                ->where('statut', 'en_cours')
                ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->with(['affectations' => function($query) {
                $query->where('statut_affectation', 'a_cab');
            }]);
        $courriers =$this->applyCourrierFilters($query,'interne')
            ->paginate(10);

        return view('dashboards.cab.courriers.interne',[
            'courriers'=>$courriers,
        ]);
    }

    public function cabCourrierArrive()
    {
        // Get incoming mails with their affectations where status is a_cab\]
              $query= Courrier::where('type_courrier', 'arrive')
                ->where('statut', 'en_cours')
                ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->with(['affectations' => function($query) {
                $query->where('statut_affectation', 'a_cab');
            }]);
        $courriers =$this->applyCourrierFilters($query,'arrive')
            ->paginate(10);

        return view('dashboards.cab.courriers.arrive',[
            'courriers'=>$courriers
        ]);
    }
}
