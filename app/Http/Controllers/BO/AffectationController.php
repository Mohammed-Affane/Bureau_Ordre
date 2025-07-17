<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Models\Entite; // Missing import
use App\Models\User;   // Missing import
use Illuminate\View\View;

class AffectationController extends Controller
{
    /**
     * Afficher le formulaire d'affectation pour un courrier
     */
    public function create($courrierId)
    {
        $courrier = Courrier::with(['expediteur', 'entiteExpediteur'])->findOrFail($courrierId);
        
        // Get users who can receive assignments (filter by role or other criteria if needed)
        $users = User::whereHas('entites')
            ->with('entites')
            ->orderBy('name')
            ->get()
            ->groupBy(function($user) {
                return optional($user->entites->first())->nom_entite ?? 'Autres';
            });

        return view('affectations.create', compact('courrier', 'users'));
    }
}