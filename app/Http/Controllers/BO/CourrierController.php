<?php

namespace App\Http\Controllers\BO;

use App\Models\Courrier;
use App\Models\Expediteur;
use App\Models\User;
use App\Models\Entite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourrierController extends Controller
{
    // Display a listing of courriers
    public function index()
    {
        $courriers = Courrier::with(['expediteur', 'agent', 'destinataires'])->get();
        return view('dashboards.bo.courriers.index', compact('courriers'));
    }

    // Show the form for creating a new courrier
    public function create()
    {
        $expediteurs = Expediteur::all();
        $agents = User::all();
        $entites = Entite::all();
        $courriers=Courrier::all();
        
        
        return view('dashboards.bo.courriers.create', compact('expediteurs', 'agents', 'entites', 'courriers'));
    }

    // Store a newly created courrier
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_arrive' => 'required_if:type_courrier,arrive|nullable|string|max:255',
            'reference_BO' => 'required|string|max:255|unique:courriers',
            'type_courrier' => 'required|in:arrive,depart',
            'objet' => 'required|string|max:1000',
            'date_reception' => 'required|date',
            'date_enregistrement' => 'required|date',
            'Nbr_piece' => 'required|integer|min:1',
            'priorite' => 'required|in:normal,urgent,très urgent',
            'id_expediteur' => 'required|exists:expediteurs,id',
            'id_agent_en_charge' => 'required|exists:utilisateurs,id',
            'destinataires' => 'required_if:type_courrier,depart|array',
            'destinataires.*' => 'exists:entites,id',
        ]);

        // Create the courrier
        $courrier = Courrier::create($validated);

        // Add destinataires for courrier depart
        if ($request->type_courrier === 'depart' && !empty($request->destinataires)) {
            foreach ($request->destinataires as $entiteId) {
                $courrier->destinataires()->create([
                    'entite_id' => $entiteId,
                    'statut' => 'en attente'
                ]);
            }
        }

        return redirect()->route('bo.courriers.index')->with('success', 'Courrier créé avec succès.');
    }

    // Display the specified courrier
    public function show(Courrier $courrier)
    {
        $courrier->load(['expediteur', 'agent', 'destinataires.entite', 'traitements', 'affectations']);
        return view('bo.courriers.show', compact('courrier'));
    }

    // Show the form for editing the courrier
    public function edit(Courrier $courrier)
    {
        $expediteurs = Expediteur::all();
        $agents = User::all();
        $entites = Entite::all();
        $types = ['arrive' => 'Courrier Arrivée', 'depart' => 'Courrier Départ'];
        $selectedDestinataires = $courrier->destinataires->pluck('entite_id')->toArray();
        
        return view('bo.courriers.edit', compact('courrier', 'expediteurs', 'agents', 'entites', 'types', 'selectedDestinataires'));
    }

    // Update the specified courrier
    public function update(Request $request, Courrier $courrier)
    {
        $validated = $request->validate([
            'reference_arrive' => 'required_if:type_courrier,arrive|nullable|string|max:255',
            'reference_BO' => 'required|string|max:255|unique:courriers,reference_BO,'.$courrier->id,
            'type_courrier' => 'required|in:arrive,depart',
            'objet' => 'required|string|max:1000',
            'date_reception' => 'required|date',
            'date_enregistrement' => 'required|date',
            'Nbr_piece' => 'required|integer|min:1',
            'priorite' => 'required|in:normal,urgent,très urgent',
            'id_expediteur' => 'required|exists:expediteurs,id',
            'id_agent_en_charge' => 'required|exists:utilisateurs,id',
            'destinataires' => 'required_if:type_courrier,depart|array',
            'destinataires.*' => 'exists:entites,id',
        ]);

        // Update the courrier
        $courrier->update($validated);

        // Update destinataires for courrier depart
        if ($request->type_courrier === 'depart') {
            // Remove old destinataires
            $courrier->destinataires()->delete();
            
            // Add new destinataires
            if (!empty($request->destinataires)) {
                foreach ($request->destinataires as $entiteId) {
                    $courrier->destinataires()->create([
                        'entite_id' => $entiteId,
                        'statut' => 'en attente'
                    ]);
                }
            }
        }

        return redirect()->route('courriers.index')->with('success', 'Courrier mis à jour avec succès.');
    }

    // Remove the specified courrier
    public function destroy(Courrier $courrier)
    {
        $courrier->destinataires()->delete();
        $courrier->delete();
        
        return redirect()->route('courriers.index')->with('success', 'Courrier supprimé avec succès.');
    }
}