<?php

namespace App\Http\Controllers\Courriers;

use App\Models\User;
use App\Models\Entite;
use App\Models\Courrier;
use Illuminate\View\View;
use App\Models\Expediteur;
use App\Services\CourrierService;
use App\Http\Controllers\Controller;
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
            'expediteurs' => Expediteur::orderBy('nom')->get(['id', 'nom'])
        ]);
    }

    public function store(CourrierRequest $request): RedirectResponse
    {
        try {
            $this->courrierService->createCourrier($request->validated());
            
            return redirect()->route('courriers.index')
                ->with('success', 'Courrier créé avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du courrier: ' . $e->getMessage())
                ->withInput();
        }
    }
}