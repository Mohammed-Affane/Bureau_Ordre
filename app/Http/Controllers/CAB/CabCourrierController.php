<?php

namespace App\Http\Controllers\CAB;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Affectation;
use Illuminate\Http\Request;

class CabCourrierController extends Controller
{
    public function cabCourrierInterne()
    {
        $courriers = Courrier::where('type_courrier', 'interne')
                ->where('statut', 'en_cours')
                ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->with(['affectations' => function($query) {
                $query->where('statut_affectation', 'a_cab');
            }])
            ->paginate(10);

        return view('dashboards.cab.courriers.interne',[
            'courriers'=>$courriers,
        ]);
    }

    public function cabCourrierArrive()
    {
        // Get incoming mails with their affectations where status is a_cab
        $courriers = Courrier::where('type_courrier', 'arrive')
            ->where('statut', 'en_cours')
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->with(['affectations' => function($query) {
                $query->where('statut_affectation', 'a_cab');
            }])
            ->paginate(10);

        return view('dashboards.cab.courriers.arrive',[
            'courriers'=>$courriers
        ]);
    }
}
