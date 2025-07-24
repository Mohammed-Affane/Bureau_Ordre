<?php

namespace App\Http\Controllers\Division;

use App\Models\Courrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entite;

class DivisionCourrierController extends Controller{

 public function index()
{
    // 1. Récupérer l'entité dont l'user est responsable
    $entite = auth()->user()->entite;

    if (!$entite) {
        return redirect()->back()
               ->with('error', 'Vous n\'êtes responsable d\'aucune division');
    }

    // 2. Récupérer les courriers pour cette entité
    $courriers = Courrier::where(function($query) use ($entite) {
            // Courriers où l'entité est destinataire direct
            $query->WhereHas('affectations', function($q) {
                    $q->where('id_affecte_a_utilisateur', auth()->id());
                });
                // OU courriers affectés à l'user responsable
               
        })
        ->with([
            'expediteur',
            'courrierDestinatairePivot.entite',
            'affectations' => function($query) {
                $query->with(['affecteA', 'affectePar'])
                      ->where('id_affecte_a_utilisateur', auth()->id());
            }
        ])
        ->orderBy('date_reception', 'desc')
        ->paginate(20);

    return view('dashboards.division.courriers.index', [
        'courriers' => $courriers,
        'entite' => $entite
    ]);
}
}