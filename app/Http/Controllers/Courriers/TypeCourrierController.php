<?php

namespace App\Http\Controllers\Courriers;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use Illuminate\Http\Request;

class TypeCourrierController extends Controller
{
    /**
     * Display incoming mail courriers
     */
    public function courrierArrivee()
    {
        

        $courriers = Courrier::where('type_courrier', 'arrive')->paginate(5);

        return view('courriers.typesCourriers.arrive', compact('courriers'));
    }

    /**
     * Display outgoing mail courriers
     */
    public function courrierDepart() 
    {
        $courriers = Courrier::where('type_courrier', 'depart')->paginate(5);
        return view('courriers.typesCourriers.depart', compact('courriers'));
    }

    /**
     * Display internal mail courriers
     */
    public function courrierInterne()
    {
        $courriers = Courrier::where('type_courrier', 'interne')->paginate(5);
        return view('courriers.typesCourriers.interne', compact('courriers'));
    }

    /**
     * Display confidential mail courriers
     */
    public function courrierVisa()
    {
        $courriers = Courrier::where('type_courrier', 'visa')->paginate(5);
        return view('courriers.typesCourriers.visa', compact('courriers'));
    }

    /**
     * Display urgent mail courriers
     */
    public function courrierDecision()
    {
        $courriers = Courrier::where('type_courrier', 'decision')->paginate(5);
        return view('courriers.typesCourriers.decision', compact('courriers'));
    }
}
