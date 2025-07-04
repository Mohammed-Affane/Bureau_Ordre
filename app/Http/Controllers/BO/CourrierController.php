<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Expediteur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CourrierController extends Controller
{
    public function index()
    {
        $courriers = Courrier::latest()->paginate(10);
        return view('dashboards.bo.courriers.index', compact('courriers'));
    }


/**
 * Show the form for creating a new courier.
 */
public function create()
{
    try {
        // Get active agents only, ordered by name
        $agents = Auth::user()->get();

        // Get expediteurs ordered by name
        $expediteurs = Expediteur::orderBy('nom')
            ->get(['id', 'nom']);

        return view('dashboards.bo.courriers.create', compact('agents', 'expediteurs'));
    } catch (\Exception $e) {
        Log::error('Error loading courier creation form: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Erreur lors du chargement du formulaire.');
    }
}

/**
 * Store a newly created courier in storage.
 */
public function store(Request $request)
{
    try {
        DB::beginTransaction();

        // Handle expediteur logic first
        $expediteur = $this->handleExpediteur($request);
        
        // Validate courier data
        $validated = $this->validateCourierData($request, $expediteur);
        
        // Create the courier
        $courrier = $this->createCourier($validated, $expediteur->id);
        
        DB::commit();
        
        return redirect()->route('bo.courriers.index')
            ->with('success', 'Courrier créé avec succès.');
            
    } catch (ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating courier: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Erreur lors de la création du courrier.')
            ->withInput();
    }
}

/**
 * Handle expediteur creation or selection logic.
 */
private function handleExpediteur(Request $request): Expediteur
{
    // If existing expediteur is selected
    if ($request->filled('expediteur_id')) {
        $expediteur = Expediteur::find($request->expediteur_id);
        
        if (!$expediteur) {
            throw new \Exception('Expéditeur sélectionné introuvable.');
        }
        
        return $expediteur;
    }
    
    // Validate new expediteur data
    $expediteurData = $request->validate([
        'exp_nom' => 'required|string|max:255',
        'exp_type_source' => 'required|string|max:100',
        'exp_adresse' => 'nullable|string|max:500',
        'exp_telephone' => 'nullable|string|max:20|regex:/^[0-9+\-\s\(\)]*$/',
    ]);
    
    // Create or find expediteur
    $expediteur = Expediteur::firstOrCreate([
        'nom' => trim($expediteurData['exp_nom']),
        'type_source' => trim($expediteurData['exp_type_source']),
    ], [
        'adresse' => $expediteurData['exp_adresse'] ? trim($expediteurData['exp_adresse']) : null,
        'telephone' => $expediteurData['exp_telephone'] ? trim($expediteurData['exp_telephone']) : null,
        'created_by' => auth()->id(),
    ]);
    
    return $expediteur;
}

/**
 * Validate courier data.
 */
private function validateCourierData(Request $request, Expediteur $expediteur): array
{
    $rules = [
        'type_courrier' => 'required|in:arrive,depart,interne',
        'objet' => 'required|string|max:255',
        'reference_arrive' => 'nullable|integer|min:1',
        'reference_BO' => 'nullable|integer|min:1',
        'date_reception' => 'nullable|date|before_or_equal:today',
        'date_enregistrement' => 'required|date|before_or_equal:today',
        'Nbr_piece' => 'required|integer|min:1|max:999',
        'priorite' => 'nullable|in:normale,urgent,confidentiel,A reponse obligatoire',
        'id_agent_en_charge' => 'nullable|exists:users,id',
    ];
    
    // Add custom validation messages
    $messages = [
        'type_courrier.required' => 'Le type de courrier est obligatoire.',
        'type_courrier.in' => 'Le type de courrier doit être : arrivé, départ ou interne.',
        'objet.required' => 'L\'objet du courrier est obligatoire.',
        'objet.max' => 'L\'objet ne peut pas dépasser 255 caractères.',
        'reference_arrive.integer' => 'La référence d\'arrivée doit être un nombre entier.',
        'reference_arrive.min' => 'La référence d\'arrivée doit être supérieure à 0.',
        'reference_BO.integer' => 'La référence BO doit être un nombre entier.',
        'reference_BO.min' => 'La référence BO doit être supérieure à 0.',
        'date_reception.date' => 'La date de réception n\'est pas valide.',
        'date_reception.before_or_equal' => 'La date de réception ne peut pas être dans le futur.',
        'date_enregistrement.required' => 'La date d\'enregistrement est obligatoire.',
        'date_enregistrement.date' => 'La date d\'enregistrement n\'est pas valide.',
        'date_enregistrement.before_or_equal' => 'La date d\'enregistrement ne peut pas être dans le futur.',
        'Nbr_piece.required' => 'Le nombre de pièces est obligatoire.',
        'Nbr_piece.integer' => 'Le nombre de pièces doit être un nombre entier.',
        'Nbr_piece.min' => 'Le nombre de pièces doit être au moins 1.',
        'Nbr_piece.max' => 'Le nombre de pièces ne peut pas dépasser 999.',
        'priorite.in' => 'La priorité sélectionnée n\'est pas valide.',
        'id_agent_en_charge.exists' => 'L\'agent sélectionné n\'existe pas.',
    ];
    
    return $request->validate($rules, $messages);
}

/**
 * Create the courier record.
 */
private function createCourier(array $validated, int $expediteurId): Courrier
{
    // Prepare courier data
    $courierData = [
        'type_courrier' => $validated['type_courrier'],
        'objet' => trim($validated['objet']),
        'reference_arrive' => $validated['reference_arrive'] ?? null,
        'reference_BO' => $validated['reference_BO'] ?? null,
        'date_reception' => $validated['date_reception'] ?? null,
        'date_enregistrement' => $validated['date_enregistrement'],
        'Nbr_piece' => $validated['Nbr_piece'],
        'priorite' => $validated['priorite'] ?? 'normale',
        'id_expediteur' => $expediteurId,
        'id_agent_en_charge' => $validated['id_agent_en_charge'] ?? null,
        'created_by' => auth()->id(),
        'status' => 'pending',
        ''=>'' // Default status
    ];
     // Gestion des documents
            // if ($request->hasFile('document_files')) {
            //     $this->handleDocumentUpload($request->file('document_files'), $courrier);
            // }

            DB::commit();
    // Generate reference number if not provided
    if (empty($courierData['reference_BO'])) {
        $courierData['reference_BO'] = $this->generateReferenceNumber($validated['type_courrier']);
    }
    
    return Courrier::create($courierData);
}

/**
 * Generate a unique reference number for the courier.
 */
private function generateReferenceNumber(string $type): int
{
    $prefix = match($type) {
        'arrive' => 1,
        'depart' => 2,
        'interne' => 3,
        default => 9
    };
    
    // Get the latest reference for this type
    $latestCourier = Courrier::where('type_courrier', $type)
        ->whereYear('created_at', now()->year)
        ->orderBy('reference_BO', 'desc')
        ->first();
    
    $sequence = 1;
    if ($latestCourier && $latestCourier->reference_BO) {
        // Extract sequence from existing reference
        $lastRef = (string) $latestCourier->reference_BO;
        if (strlen($lastRef) >= 5) {
            $sequence = (int) substr($lastRef, -4) + 1;
        }
    }
    
    // Format: TYYYY#### (T=type, YYYY=year, ####=sequence)
    return (int) ($prefix . now()->year . str_pad($sequence, 4, '0', STR_PAD_LEFT));
}

/**
 * Additional method to check for duplicate references if needed.
 */
private function validateUniqueReferences(Request $request): void
{
    if ($request->filled('reference_arrive')) {
        $exists = Courrier::where('reference_arrive', $request->reference_arrive)->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'reference_arrive' => 'Cette référence d\'arrivée existe déjà.'
            ]);
        }
    }
    
    if ($request->filled('reference_BO')) {
        $exists = Courrier::where('reference_BO', $request->reference_BO)->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'reference_BO' => 'Cette référence BO existe déjà.'
            ]);
        }
    }
}
}