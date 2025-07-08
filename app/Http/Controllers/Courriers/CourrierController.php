<?php

namespace App\Http\Controllers\Courriers;

use App\Models\User;
use App\Models\Entite;
use App\Models\Courrier;
use Illuminate\View\View;
use App\Models\Expediteur;
use App\Services\CourrierService;
use App\Http\Controllers\Controller;
use App\Models\CourrierDestinataire;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CourrierRequest;

class CourrierController extends Controller
{
    public function __construct(
        private  CourrierService $courrierService
    ) {}

    public function index(): View
    {
        $courriers = Courrier::latest()->paginate(10);
        return view('courriers.index', compact('courriers'));
    }

    public function create(): View
    {
        return view('courriers.create', [
            'agents' => User::all(),
            'entites' => Entite::all(),
            'expediteurs' => Expediteur::orderBy('nom')->get(['id', 'nom']),
            'destinataires'=>CourrierDestinataire::all(),
        ]);
    }

    public function store(CourrierRequest $request): RedirectResponse
    {

       $validated = $request->validate([
        'type_courrier' => 'required|in:arrive,depart,interne',
        'objet' => 'nullable|string|max:255',
        'expediteur_id' => 'nullable|exists:expediteurs,id',
        'entite_source' => 'nullable|exists:entites,id',
        'exp_nom' => 'nullable|string|max:255',
        'exp_type_source' => 'nullable|string|max:255',
        'exp_adresse' => 'nullable|string|max:500',
        'exp_telephone' => 'nullable|string|max:20',
        'destinataires_entite' => 'nullable|array',
        'destinataires_entite.*' => 'exists:entites,id',
        'destinataires_externe' => 'nullable|array',
        'destinataires_externe.*' => 'exists:expediteurs,id',
    ]);

     // 2. Créer le courrier
    $courrier = Courrier::create([
        'type_courrier' => $request->type_courrier,
        'objet' => $request->objet,
        'reference_arrive'=>$request->reference_arrive,
        'reference_bo'=>$request->reference_bo,
        'reference_visa'=>$request->reference_visa,
        'reference_dec'=>$request->reference_dec,
        'reference_depart'=>$request->reference_depart,
        'date_reception'=>$request->date_reception,
        'date_depart'=>$request->date_depart,
        'date_enregistrement'=>$request->date_enregistrement,
        'priorite'=>$request->priorite,
        'id_agent_en_charge'=>$request->id_agent_en_charge
    ]);


   // 1. Ajouter les destinataires internes (via entite_id)
if ($request->has('destinataires_entite')) {
    foreach ($request->destinataires_entite as $entiteId) {
        if (!empty($entiteId)) {
            CourrierDestinataire::create([
                'id_courrier'    => $courrier->id,
                'entite_id'      => $entiteId,
                'type_courrier'  => 'interne',
            ]);
        }
    }
}

// 2. Ajouter les destinataires externes (via select multiple)
if ($request->has('destinataires_externe')) {
    foreach ($request->destinataires_externe as $expediteurId) {
        $expediteur = Expediteur::find($expediteurId);
        if ($expediteur) {
            CourrierDestinataire::create([
                'id_courrier'    => $courrier->id,
                'nom'            => $expediteur->nom,
                'type_source'    => $expediteur->type_source,
                'adresse'        => $expediteur->adresse,
                'type_courrier'  => 'externe',
            ]);
        }
    }
}

// 3. Ajouter les destinataires externes saisis manuellement
if ($request->has('dest_nom')) {
    foreach ($request->dest_nom as $index => $nom) {
        if (!empty($nom)) {
            CourrierDestinataire::create([
                'id_courrier'    => $courrier->id,
                'nom'            => $nom,
                'type_source'    => $request->dest_type_source[$index] ?? null,
                'adresse'        => $request->dest_adresse[$index] ?? null,
                'type_courrier'  => 'externe',
            ]);
        }
    }
}




   


    // 3. Ajouter les destinataires
    // Internes
     // Destinataires externes à enregistrer dans courrier_destinataire (copie des infos)
    

    // Externes (pour départ/interne uniquement)
    // if (in_array($request->type_courrier, ['depart', 'interne']) && $request->has('destinataires_externe')) {
    //     foreach ($request->destinataires_externe as $exp_id) {
    //         CourrierDestinataire::create([
    //             'courrier_id' => $courrier->id,
    //             'expediteur_id' => $exp_id,
    //         ]);
    //     }
    // }

    return redirect()->route('courriers.index')->with('success', 'Courrier créé avec succès.');
    }
}