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
use App\Http\Requests\CourrierRequest; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

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
            'expediteurs' => Expediteur::orderBy('nom')->where('type_source','administration')->get(['id', 'nom']),
            'destinataires' => CourrierDestinataire::where('type_courrier', 'externe')
                                ->where('type_source','administration')
                                ->whereNotNull('nom') // Ensure they have a name
                                ->orderBy('nom')
                                ->get(),
        ]);
    }

    // Update the store method to use CourrierRequest instead of Request

public function store(CourrierRequest $request): RedirectResponse
{
    DB::beginTransaction();

    try {
        // 1. Handle expediteur for arrive/visa courriers
        $expediteurId = null;
        if (in_array($request->type_courrier, ['arrive', 'visa'])) {
            if ($request->filled('exp_nom')) {
                $expediteur = Expediteur::create([
                    'nom'          => $request->exp_nom,
                    'type_source'  => $request->exp_type_source,
                    'adresse'      => $request->exp_adresse,
                    'telephone'    => $request->exp_telephone,
                    'CIN'          => $request->exp_CIN ?? null,
                ]);
                $expediteurId = $expediteur->id;
            } elseif ($request->filled('id_expediteur')) {
                $expediteurId = $request->id_expediteur;
            }
        }


        // 3. Create courrier
        $courrierData = [
            'type_courrier'         => $request->type_courrier,
            'objet'                 => $request->objet,
            'reference_arrive'      => $request->reference_arrive,
            'reference_bo'           => $request->reference_bo,
            'reference_visa'         => $request->reference_visa,
            'reference_dec'          => $request->reference_dec,
            'reference_depart'       => $request->reference_depart,
            'date_reception'         => $request->date_reception,
            'date_depart'            => $request->date_depart,
            'date_enregistrement'    => $request->date_enregistrement,
            'priorite'               => 'normale',
            'delais'                 => now()->addDays(7), // Set default deadline
            'id_agent_en_charge'     => auth()->id(),
            'id_expediteur'          => $expediteurId,
            'fichier_scan'           => $this->handleFileUpload($request),
            'Nbr_piece'              => $request->Nbr_piece,
            'entite_id'              => $request->entite_id ?? null,
        ];

        $courrier = Courrier::create($courrierData);

        // 4. Handle all recipients (internal + external + manual)
        $allDestinataireIds = [];

        // 4.1 Internal recipients (entites)
        if ($request->has('destinataires_entite')) {
            foreach ($request->destinataires_entite as $entiteId) {
                $entite = Entite::find($entiteId);
                $destinataire = CourrierDestinataire::firstOrCreate(
                    [
                        'entite_id' => $entiteId,
                        'type_courrier' => 'interne'
                    ],
                    [
                        'nom' => $entite->nom ?? 'Entité inconnue',
                    ]
                );
                $allDestinataireIds[] = $destinataire->id;
            }
        }

        // 4.2 Existing external recipients (from list)
        if ($request->has('destinataires_externe')) {
            foreach ($request->destinataires_externe as $existingDestId) {
                $destinataire = CourrierDestinataire::where('id', $existingDestId)
                    ->where('type_courrier', 'externe')
                    ->first();
                if ($destinataire) {
                    $allDestinataireIds[] = $existingDestId;
                }
            }
        }

        // 4.3 Manually added external recipients
        if ($request->has('dest_nom')) {
            foreach ($request->dest_nom as $index => $nom) {
                if (!empty($nom)) {
                    $newDest = CourrierDestinataire::create([
                        'nom'            => $nom,
                        'type_source'    => $request->dest_type_source[$index] ?? null,
                        'adresse'        => $request->dest_adresse[$index] ?? null,
                        'CIN'            => $request->dest_CIN[$index] ?? null,
                        'telephone'      => $request->dest_telephone[$index] ?? null,
                        'type_courrier'  => 'externe',
                    ]);
                    $allDestinataireIds[] = $newDest->id;
                }
            }
        }

        // ✅ Attach all destinataires at once (avoid duplicates)
        $courrier->courrierDestinatairePivot()->syncWithoutDetaching(array_unique($allDestinataireIds));

        DB::commit();

        return redirect()->route("courriers.{$request->type_courrier}")
               ->with('success', 'Courrier créé avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Courrier creation failed: ' . $e->getMessage());
        
        return back()->withInput()
            ->with('error', 'Erreur lors de la création du courrier: ' . $e->getMessage());
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
        return $courrier->fichier_scan ? asset('storage/' . $courrier->fichier_scan) : null;
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
        // For CAB users - only allow priority and deadline updates
    if (auth()->user()->hasRole('cab')) {
            $courrier->update([
                'priorite' => $request->priorite,
                'delais' => $request->delais,
            ]);
            
            return redirect()->route("cab.courriers.{$courrier->type_courrier}")->with('success', 'Priorité et délais mis à jour avec succès.');
        }

        // Original full update logic for non-CAB users
        $expediteurId = $courrier->id_expediteur;

        if ($request->type_courrier === 'arrive') {
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
            elseif ($request->filled('id_expediteur')) {
                $expediteurId = $request->id_expediteur;
            }
        }

        // Handle file upload (only for non-CAB)
        $filename = $this->handleFileUpload($request, $courrier);

        // Update courrier
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

        // Handle entite_id
        if (in_array($courrier->type_courrier, ['depart', 'decision', 'interne'])) {
            $courrier->entite_id = $request->filled('entite_id') ? $request->entite_id : null;
            $courrier->save();
        }

        // Handle destinataires
        $this->updateDestinataires($request, $courrier);

        return $this->redirectBasedOnType($request->type_courrier);
    }

    // In your store method, change the file upload to be consistent:
    private function handleFileUpload($request, $courrier = null)
    {
        if (!$request->hasFile('fichier_scan')) {
            return $courrier ? $courrier->fichier_scan : null;
        }

        $subfolder = match($request->type_courrier) {
            'arrive'    => 'arrive',
            'depart'    => 'depart',
            'decision'  => 'decision',
            'visa'      => 'visa',
            'interne'   => 'interne',
            default     => 'other'
        };
        
        return $request->file('fichier_scan')->store(
            "courriers/{$subfolder}", 
            'public'
        );
    }

// Then use this method in both store and update methods

protected function updateDestinataires($request, $courrier)
{
    $courrier->courrierDestinatairePivot()->detach();

    if ($request->has('destinataires_entite')) {
        $ids = [];
        foreach ($request->destinataires_entite as $idDestinataire) {
            if ($idDestinataire && is_numeric($idDestinataire)) {
                $destinataire = CourrierDestinataire::create([
                    'entite_id' => $idDestinataire,
                    'type_courrier' => 'interne',
                ]);
                $ids[] = $destinataire->id;
            }
        }
        $courrier->courrierDestinatairePivot()->attach($ids);
    }

    if ($request->has('destinataires_externe')) {
        $courrier->courrierDestinatairePivot()->attach($request->destinataires_externe);
    }

    if ($request->has('dest_nom')) {
        $ids = [];
        foreach ($request->dest_nom as $index => $nom) {
            if (!empty($nom)) {
                $destinataire = CourrierDestinataire::create([
                    'nom' => $nom,
                    'type_source' => $request->dest_type_source[$index] ?? null,
                    'adresse' => $request->dest_adresse[$index] ?? null,
                    'CIN' => $request->dest_CIN[$index] ?? null,
                    'telephone' => $request->dest_telephone[$index] ?? null,
                    'type_courrier' => 'externe',
                ]);
                $ids[] = $destinataire->id;
            }
        }
        $courrier->courrierDestinatairePivot()->attach($ids);
    }
}

protected function redirectBasedOnType($type)
{
    $routes = [
        'visa' => 'courriers.visa',
        'depart' => 'courriers.depart',
        'decision' => 'courriers.decision',
        'interne' => 'courriers.interne',
        'arrive' => 'courriers.arrive',
    ];

    return redirect()
        ->route($routes[$type] ?? 'courriers.index')
        ->with('success', 'Courrier mis à jour avec succès.');
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