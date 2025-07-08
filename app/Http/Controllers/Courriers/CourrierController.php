<?php

namespace App\Http\Controllers\Courriers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourrierRequest;
use App\Models\Expediteur;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Courrier;
use App\Services\CourrierService;

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