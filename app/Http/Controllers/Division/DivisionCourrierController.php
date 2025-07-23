<?php

namespace App\Http\Controllers\Division;

use App\Models\Courrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entite;

class DivisionCourrierController extends Controller{

    public function index(){

    $entiteId = Entite::where('responsable_id', auth()->user()->id)->first()->id;

    $courriers = Courrier::where('entite_id', $entiteId)
        ->whereIn('type_courrier', ['arrive', 'interne'])
        ->whereHas('affectations', function ($query) {
            $query->where('statut_affectation', 'a_div');
        })
        ->with(['affectations' => function ($query) {
            $query->where('statut_affectation', 'a_div');
        }])
        ->latest()
        ->paginate(20);

    return view('dashboards.division.courriers.index', compact('courriers'));


    }
}