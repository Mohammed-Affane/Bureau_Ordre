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


    public function index(): View
    {
        $courriers = Courrier::with('courrierDestinatairePivot.entite')->latest()->paginate(5);

        return view('courriers.index', compact('courriers'));
    }

    public function show(Courrier $courrier){
        return view('courriers.show',compact('courrier'));
    }

    public function destroy(Courrier $courrier){
        $courrier->delete();
        return redirect()->route("courriers.$courrier->type_courrier")->with('success','Courrier deleted successfully');
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
        if ($request->hasFile('fichier_scan') ) {
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
        else {
            file_put_contents('php://stderr', "File upload failed or no file provided.\n");
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
            'priorite' => 'normale',
            'delais' => Carbon::now(),
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

        if($request->type_courrier==='visa'){
            return redirect()->route('courriers.visa')->with('success', 'Courrier créé avec succès.');
        }elseif($request->type_courrier==='depart'){
            return redirect()->route('courriers.depart')->with('success', 'Courrier créé avec succès.');
        }elseif($request->type_courrier==='decision'){
            return redirect()->route('courriers.decision')->with('success', 'Courrier créé avec succès.');    
        }elseif($request->type_courrier==='interne'){
            return redirect()->route('courriers.interne')->with('success', 'Courrier créé avec succès.');
        }elseif($request->type_courrier==='arrive'){
            return redirect()->route('courriers.arrive')->with('success', 'Courrier créé avec succès.');
        }
    }

    public function edit(Courrier $courrier)
    {
        // Load necessary relationships
        $courrier->load([
            'expediteur',
            'courrierDestinatairePivot', // Updated to use the pivot relationship
            'courrierDestinatairePivot.entite'
        ]);

        // Get all destinataires (for dropdowns)
        $destinatairesExternes = CourrierDestinataire::where('type_courrier', 'externe')->get();
        $entites = Entite::all();

        // Separate destinataires by type
        $destinatairesInterne = [];
        $destinatairesExterne = [];
        $destinatairesManuels = [];

        foreach ($courrier->courrierDestinatairePivot as $courrierDest) {
            if ($courrierDest->entite_id) {
                $destinatairesInterne[] = $courrierDest->entite_id;
            } elseif ($courrierDest->destinataire) {
                if ($courrierDest->type_courrier === 'externe') {
                    $destinatairesExterne[] = $courrierDest->destinataire_id;
                } else {
                    $destinatairesManuels[] = $courrierDest->destinataire;
                }
            }
        }

        // Get all expediteurs for dropdown
        $expediteurs = Expediteur::all();

         // Prepare file information
        $existingFile = null;
        if ($courrier->fichier_scan) {
            $existingFile = [
                'name' => $courrier->fichier_scan,
                'url' => $this->getFileUrl($courrier),
                'type' => $this->getFileType($courrier->fichier_scan)
            ];
        }

        return view('courriers.edit', [
            'courrier' => $courrier,
            'expediteurs' => $expediteurs,
            'destinatairesExternes' => $destinatairesExternes,
            'entites' => $entites,
            'selectedDestinatairesInterne' => $destinatairesInterne,
            'selectedDestinatairesExterne' => $destinatairesExterne,
            'destinatairesManuels' => $destinatairesManuels,
            'typeCourrier' => $courrier->type_courrier,
            'existingFile' => $existingFile,
        ]);
    }

        private function getFileUrl(Courrier $courrier)
    {
        switch ($courrier->type_courrier) {
            case 'arrive':
                return asset('fichiers_scans_arrive/' . $courrier->fichier_scan);
            case 'depart':
                return asset('fichiers_scans_depart/' . $courrier->fichier_scan);
            case 'decision':
                return asset('fichiers_scans_decision/' . $courrier->fichier_scan);
            case 'visa':
                return asset('fichiers_scans_visa/' . $courrier->fichier_scan);
            case 'interne':
                return asset('fichiers_scans_interne/' . $courrier->fichier_scan);
            default:
                return asset('fichiers_scans/' . $courrier->fichier_scan);
        }
    }

    private function getFileType($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'])) {
            return 'image';
        } elseif ($extension === 'pdf') {
            return 'application/pdf';
        }
        
        return 'unknown';
    }

    public function update(CourrierRequest $request, Courrier $courrier): RedirectResponse
        {
    $expediteurId = $courrier->id_expediteur;

    // 1. Handle expediteur for arrived courrier
    if ($request->type_courrier === 'arrive') {
        // If user manually filled expediteur info
        if ($request->filled('exp_nom')) {
                // If courrier already has an expediteur, update it
    $expediteur = Expediteur::create([
                    'nom'          => $request->exp_nom,
                    'type_source'  => $request->exp_type_source,
                    'adresse'      => $request->exp_adresse,
                    'telephone'    => $request->exp_telephone,
                    'CIN'          => $request->exp_CIN,
                ]);
                $expediteurId = $expediteur->id;
        }
        // User selected existing expediteur
        elseif ($request->filled('id_expediteur')) {
            $expediteurId = $request->id_expediteur;
        }
    }

    // Handle file upload
    $filename = $courrier->fichier_scan;
    if ($request->hasFile('fichier_scan')) {
        $file = $request->file('fichier_scan');

        // Determine destination path based on courrier type
        if($request->type_courrier==='arrive'){
            $destinationPath = public_path('fichiers_scans_arrive');
        } elseif($request->type_courrier==='depart'){
            $destinationPath = public_path('fichiers_scans_depart');
        } elseif($request->type_courrier==='decision'){
            $destinationPath = public_path('fichiers_scans_decision');
        } elseif($request->type_courrier==='visa'){
            $destinationPath = public_path('fichiers_scans_visa');
        } elseif($request->type_courrier==='interne'){
            $destinationPath = public_path('fichiers_scans_interne');
        }

        // Create directory if it doesn't exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Delete old file if exists
        if ($filename && file_exists($destinationPath.'/'.$filename)) {
            unlink($destinationPath.'/'.$filename);
        }

        // Generate unique filename and move new file
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $filename);
    }

    // 2. Update the courrier
    $courrier->update([
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
        'delais' => $request->delais,
        'id_expediteur' => $expediteurId,
        'fichier_scan' => $filename,
        'Nbr_piece' => $request->Nbr_piece,
    ]);

    // Handle entite_id for depart, decision, interne courriers
    if (in_array($courrier->type_courrier, ['depart', 'decision', 'interne'])) {
        $courrier->entite_id = $request->filled('entite_id') ? $request->entite_id : null;
        $courrier->save();
    }

    // === DESTINATAIRES MANAGEMENT ===
    // First, detach all existing destinataires
    $courrier->courrierDestinatairePivot()->detach();

    // Handle entity destinataires (interne)
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

    // Handle external destinataires (selected from existing)
    if ($request->has('destinataires_externe')) {
        $courrier->courrierDestinatairePivot()->attach($request->destinataires_externe);
    }

    // Handle manually added external destinataires
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

    // Redirect based on courrier type
    switch ($request->type_courrier) {
        case 'visa':
            return redirect()->route('courriers.visa')->with('success', 'Courrier mis à jour avec succès.');
        case 'depart':
            return redirect()->route('courriers.depart')->with('success', 'Courrier mis à jour avec succès.');
        case 'decision':
            return redirect()->route('courriers.decision')->with('success', 'Courrier mis à jour avec succès.');
        case 'interne':
            return redirect()->route('courriers.interne')->with('success', 'Courrier mis à jour avec succès.');
        case 'arrive':
            return redirect()->route('courriers.arrive')->with('success', 'Courrier mis à jour avec succès.');
        default:
            return back()->with('success', 'Courrier mis à jour avec succès.');
    }
}

    public function showDestinataires(Courrier $courrier): View
    {
        // Charger les relations avec entité
       
        $courrier->load('courrierDestinatairePivot.entite');
        

        return view('courriers.destinataires', compact('courrier'));
    }

   public function showAffectations(Courrier $courrier): View
{
    $courrier->load('affectations.affectePar', 'affectations.affecteA');


    return view('courriers.affectations', compact('courrier'));
}


               
                



}