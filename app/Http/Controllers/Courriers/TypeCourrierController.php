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
    public function courrierArrivee(Request $request)
    {
        $courriers = $this->getCourriersByType('arrive', $request);
        return view('courriers.typesCourriers.arrive', compact('courriers'));
    }

    /**
     * Display outgoing mail courriers
     */
    public function courrierDepart(Request $request)
    {
        $courriers = $this->getCourriersByType('depart', $request);
        return view('courriers.typesCourriers.depart', compact('courriers'));
    }

    /**
     * Display internal mail courriers
     */
    public function courrierInterne(Request $request)
    {
        $courriers = $this->getCourriersByType('interne', $request);
        return view('courriers.typesCourriers.interne', compact('courriers'));
    }

    /**
     * Display confidential mail courriers
     */
    public function courrierVisa(Request $request)
    {
        $courriers = $this->getCourriersByType('visa', $request);
        return view('courriers.typesCourriers.visa', compact('courriers'));
    }

    /**
     * Display urgent mail courriers
     */
    public function courrierDecision(Request $request)
    {
        $courriers = $this->getCourriersByType('decision', $request);
        return view('courriers.typesCourriers.decision', compact('courriers'));
    }

    /**
     * Helper method to get courriers by type with search functionality
     */
    private function getCourriersByType($type, Request $request)
    {
        // Start with base query filtered by courier type
        $query = Courrier::where('type_courrier', $type);

        // Apply search filter if search term exists
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference_arrive', 'like', "%{$searchTerm}%")
                  ->orWhere('reference_depart', 'like', "%{$searchTerm}%")
                  ->orWhere('reference_visa', 'like', "%{$searchTerm}%")
                  ->orWhere('reference_decision', 'like', "%{$searchTerm}%")
                  ->orWhere('reference_interne', 'like', "%{$searchTerm}%")
                  ->orWhere('reference_bo', 'like', "%{$searchTerm}%")
                  ->orWhere('objet', 'like', "%{$searchTerm}%")
                  ->orWhere('statut', 'like', "%{$searchTerm}%")
                  ->orWhere('priorite', 'like', "%{$searchTerm}%")
                  ->orWhere('date_enregistrement', 'like', "%{$searchTerm}%")
                  ->orWhere('date_reception', 'like', "%{$searchTerm}%")
                  ->orWhereHas('expediteur', function ($expediteurQuery) use ($searchTerm) {
                      $expediteurQuery->where('nom', 'like', "%{$searchTerm}%")
                                    ->orWhere('email', 'like', "%{$searchTerm}%")
                                    ->orWhere('telephone', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('agent', function ($agentQuery) use ($searchTerm) {
                      $agentQuery->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Apply additional filters if needed
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Include relationships and paginate
        $courriers = $query->with(['expediteur', 'agent'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        // Preserve search parameter in pagination links
        $courriers->appends($request->query());

        return $courriers;
    }
}