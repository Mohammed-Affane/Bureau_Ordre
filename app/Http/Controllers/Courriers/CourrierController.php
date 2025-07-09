<?php

namespace App\Http\Controllers\Courriers;

use App\Models\User;
use App\Models\Entite;
use App\Models\Courrier;
use Illuminate\View\View;
use App\Models\Expediteur;
use Illuminate\Http\Request;
use App\Services\CourrierService;
use App\Http\Controllers\Controller;
use App\Models\CourrierDestinataire;
use Illuminate\Http\RedirectResponse;
// use App\Http\Requests\CourrierRequest;

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

    public function store(Request $request): RedirectResponse
    {

    //    $validated = $request->validate([
    //     'type_courrier' => 'required|in:arrive,depart,interne',
    //     'objet' => 'nullable|string|max:255',
    //     'expediteur_id' => 'nullable|exists:expediteurs,id',
    //     'entite_source' => 'nullable|exists:entites,id',
    //     'exp_nom' => 'nullable|string|max:255',
    //     'exp_type_source' => 'nullable|string|max:255',
    //     'exp_adresse' => 'nullable|string|max:500',
    //     'exp_telephone' => 'nullable|string|max:20',
    //     'destinataires_entite' => 'nullable|array',
    //     'destinataires_entite.*' => 'exists:entites,id',
    //     'destinataires_externe' => 'nullable|array',
    //     'destinataires_externe.*' => 'exists:expediteurs,id',
    // ]);





    $expediteurId = null;

// 1. Cas du courrier arrivé avec ajout manuel d'expéditeur
if ($request->type_courrier === 'arrive') {
    // Si utilisateur a rempli manuellement un expéditeur
    if ($request->filled('exp_nom') ) {
        $expediteur = Expediteur::create([
            'nom'          => $request->exp_nom,
            'type_source'  => $request->exp_type_source,
            'adresse'      => $request->exp_adresse,
            'telephone'    => $request->exp_telephone,
            'CIN'    => $request->exp_CIN,
        ]);
        $expediteurId = $expediteur->id;
    }
    // Sinon il a sélectionné un existant
    elseif ($request->filled('id_expediteur')) {
        $expediteurId = $request->id_expediteur;
    }
    
}



     // 2. Créer le courrier
    $courrier = Courrier::create([
        'type_courrier' => $request->type_courrier,
        'objet' => $request->objet,
        'Nbr_piece'=>$request->Nbr_piece,
        'reference_arrive'=>$request->reference_arrive,
        'reference_bo'=>$request->reference_bo,
        'reference_visa'=>$request->reference_visa,
        'reference_dec'=>$request->reference_dec,
        'reference_depart'=>$request->reference_depart,
        'date_reception'=>$request->date_reception,
        'date_depart'=>$request->date_depart,
        'date_enregistrement'=>$request->date_enregistrement,
        'priorite'=>$request->priorite,
        'id_agent_en_charge'=>$request->id_agent_en_charge,
        'id_expediteur'=>$expediteurId
    ]);



    
// === EXPEDITEUR (cas courrier départ) ===
if (in_array($courrier->type_courrier, ['depart', 'decision'])) {
    // Entité expéditrice (une seule)
    if ($request->filled('entite_id')) {
        $courrier->entite_id = $request->entite_id;
        $courrier->save();
    }
}

// === DESTINATAIRES EXTERNES SÉLECTIONNÉS ===
if ($request->has('destinataires_externe')) {
    foreach ($request->destinataires_externe as $idDestinataire) {
        if ($idDestinataire && is_numeric($idDestinataire)) {
            CourrierDestinataire::create([
                'id_courrier'    => $courrier->id,
                'entite_id'      => $request->entite_id,
                'type_courrier'  => 'interne', 
            ]);
        }
    }
}

// === DESTINATAIRES EXTERNES AJOUTÉS MANUELLEMENT ===
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