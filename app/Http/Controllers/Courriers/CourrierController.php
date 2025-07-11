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
use App\Http\Requests\CourrierRequest; // Add this import

class CourrierController extends Controller
{
    public function __construct(
        private  CourrierService $courrierService
    ) {}

    public function index(): View
    {
        $courriers = Courrier::latest()->paginate(5);
        return view('courriers.index', compact('courriers'));
    }

    public function create(): View
    {
        return view('courriers.create', [
            'entites' => Entite::all(),
            'expediteurs' => Expediteur::orderBy('nom')->get(['id', 'nom']),
            'destinataires'=>CourrierDestinataire::whereNotNull('nom')->get()
        ]);
    }

    // Update the store method to use CourrierRequest instead of Request
    public function store(CourrierRequest $request): RedirectResponse
    {
        $expediteurId = null;

        // 1. Cas du courrier arrivé avec ajout manuel d'expéditeur
        if ($request->type_courrier === 'arrive') {
            // Si utilisateur a rempli manuellement un expéditeur
            if ($request->filled('exp_nom')) {
                $expediteur = Expediteur::create([
                    'nom'          => $request->exp_nom,
                    'type_source'  => $request->exp_type_source,
                    'adresse'      => $request->exp_adresse,
                    'telephone'    => $request->exp_telephone,
                    'CIN'          => $request->exp_CIN,
                ]);
                $expediteurId = $expediteur->id;
            }
            // Sinon il a sélectionné un existant
            elseif ($request->filled('id_expediteur')) {
                $expediteurId = $request->id_expediteur;
            }
        }

        // Handle file upload before creating courrier
        $filename = null;
        if ($request->hasFile('fichier_scan') && $request->file('fichier_scan')->isValid()) {
            $file = $request->file('fichier_scan');

            // Create the directory if it doesn't exist
            if($request->type_courrier==='arrive'){
                $destinationPath = public_path('fichiers_scans_arrive');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
            }elseif($request->type_courrier==='depart'){
                $destinationPath = public_path('fichiers_scans_depart');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
            }elseif($request->type_courrier==='decision'){
                $destinationPath = public_path('fichiers_scans_decision');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
            }elseif($request->type_courrier==='visa'){
                $destinationPath = public_path('fichiers_scans_visa');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
            }elseif($request->type_courrier==='interne'){
                $destinationPath = public_path('fichiers_scans_interne');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
            }
            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move the file
            $file->move($destinationPath, $filename);
        }

        // 2. Créer le courrier
        $courrier = Courrier::create([
            'type_courrier' => $request->type_courrier,
            'objet' => $request->objet,
            'reference_arrive' => $request->reference_arrive,
            'reference_bo' => $request->reference_bo,
            'reference_visa' => $request->reference_visa,
            'reference_dec' => $request->reference_dec,
            'reference_depart' => $request->reference_depart,
            'date_reception' => $request->date_reception,
            'date_depart' => $request->date_depart,
            'date_enregistrement' => $request->date_enregistrement,
            'priorite' => $request->priorite,
            'id_agent_en_charge' => auth()->user()->id,
            'id_expediteur' => $expediteurId,
            'fichier_scan' => $filename,
            'Nbr_piece' => $request->Nbr_piece,
        ]);

        if ($request->has('destinataires_entite')) {
            $ids = [];
            foreach ($request->destinataires_entite as $idDestinataire) {
                if ($idDestinataire && is_numeric($idDestinataire)) {
                    $destinataire = CourrierDestinataire::create([
                        'entite_id'      => $idDestinataire,
                        'type_courrier'  => 'interne',
                    ]);
                    $ids[] = $destinataire->id;
                }
            }

            if (!empty($ids)) {
                $courrier->courrierDestinatairePivot()->attach($ids);
            }
        }

        // === EXPEDITEUR (cas courrier départ) ===
        if (in_array($courrier->type_courrier, ['depart', 'decision','interne'])) {
            // Entité expéditrice (une seule)
            if ($request->filled('entite_id')) {
                $courrier->entite_id = $request->entite_id;
                $courrier->save();
            }
        }

        // === DESTINATAIRES EXTERNES ===
        if ($request->has('destinataires_externe')) {
            $courrier->courrierDestinatairePivot()->attach($request->destinataires_externe);
        }

        // === DESTINATAIRES EXTERNES AJOUTÉS MANUELLEMENT ===
        if ($request->has('dest_nom')) {
            $ids = [];
            foreach ($request->dest_nom as $index => $nom) {
                if (!empty($nom)) {
                    $destinataire = CourrierDestinataire::create([
                        'nom'            => $nom,
                        'type_source'    => $request->dest_type_source[$index] ?? null,
                        'adresse'        => $request->dest_adresse[$index] ?? null,
                        'CIN'            => $request->dest_CIN[$index] ?? null,
                        'telephone'      => $request->dest_telephone[$index] ?? null,
                        'type_courrier'  => 'externe',
                    ]);
                    $ids[] = $destinataire->id;
                }
            }

            if (!empty($ids)) {
                $courrier->courrierDestinatairePivot()->attach($ids);
            }
        }

        return redirect()->route('courriers.index')->with('success', 'Courrier créé avec succès.');
    }
}