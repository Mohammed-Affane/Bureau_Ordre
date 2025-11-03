<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\Affectation;
use App\Models\Traitement;
use App\Models\User;
use App\Models\Expediteur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Cards
        $totalCourriers = Courrier::count();
        
        $courriersRecusCeMois = Courrier::whereYear('date_reception', Carbon::now()->year)
            ->whereMonth('date_reception', Carbon::now()->month)
            ->where('type_courrier', 'arrive')
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->count();
        
        $courriersTraites = Courrier::where('type_courrier', 'arrive')
        ->whereHas('affectations', function ($query) {
        $query->whereNotNull('instruction')
            ->where('instruction', 'like', 'Gouverneur%');
            })
            ->count();



 // Top 5 Courriers INSTRUCTS
       $topCourrierInstructs = Courrier::select(
        'courriers.id',
        'courriers.reference_arrive',
        'courriers.reference_bo',
        'courriers.date_reception',
        'courriers.date_enregistrement',
        'courriers.Nbr_piece',
        'courriers.priorite',
        'courriers.statut',
        
    )
    ->join('affectations', 'courriers.id', '=', 'affectations.id_courrier')
    ->whereNotNull('affectations.instruction')
    ->where('type_courrier', 'arrive')
    ->where('affectations.instruction', 'like', 'Gouverneur%')
    ->orderByDesc('courriers.created_at')
    ->limit(5)
    ->get();
/* dd($topCourrierInstructs); */


        // 2. Charts Data
        
        // Pie Chart: Répartition par statut
        $repartitionStatut = Courrier::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        // Bar Chart: Courriers par type
        $courriersParType = Courrier::select('type_courrier', DB::raw('count(*) as total'))
            ->groupBy('type_courrier')
            ->pluck('total', 'type_courrier')
            ->toArray();
        
        // Line Chart: Évolution mensuelle (12 derniers mois)
        $evolutionMensuelle = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('Y-m');
            $monthLabel = $date->translatedFormat('M Y');
            
            $recus = Courrier::whereYear('date_reception', $date->year)
                ->whereMonth('date_reception', $date->month)
                ->where('statut','en_cours')
                ->where('type_courrier','arrive')
                ->count();
            
            $traites = Courrier::whereHas('affectations.traitements', function($query) use ($date) {
                    $query->where('statut','valide');
                })
                ->whereYear('date_reception', $date->year)
                ->whereMonth('date_reception', $date->month)
                ->where('statut','cloture')
                ->count();
               

            $evolutionMensuelle[] = [
                'month' => $monthLabel,
                'recus' => $recus,
                'traites' => $traites
            ];
        }
        
        // Stacked Bar: Priorité des courriers
        $prioriteCourriers = Courrier::select('priorite', DB::raw('count(*) as total'))
            ->groupBy('priorite')
            ->pluck('total', 'priorite')
            ->toArray();

        // 3. Tables
        
        
        // Top 5 Expéditeurs
        $topExpediteurs = Expediteur::select(
                'expediteurs.id',
                'expediteurs.nom',
                'expediteurs.type_source',
                'expediteurs.CIN',
                'expediteurs.adresse',
                'expediteurs.telephone',
                'expediteurs.created_at',
                'expediteurs.updated_at',
                DB::raw('count(courriers.id) as total_courriers')
            )
            ->join('courriers', 'courriers.id_expediteur', '=', 'expediteurs.id')
            ->groupBy(
                'expediteurs.id',
                'expediteurs.nom',
                'expediteurs.type_source',
                'expediteurs.CIN',
                'expediteurs.adresse',
                'expediteurs.telephone',
                'expediteurs.created_at',
                'expediteurs.updated_at'
            )
            ->orderByDesc('total_courriers')
            ->limit(5)
            ->get();
        

        // 4. Alertes
        
        // Courriers with only 7 days left before deadline (excluding cloturé & archivé)
        $alertesUrgents = Courrier::whereNotIn('statut', ['cloturé', 'archivé'])
            ->where('type_courrier', 'arrive')
            ->where('priorite', 'urgent')
            ->whereDate('delais', '<=', Carbon::now()->addDays(7)->toDateString())
            ->with('expediteur')
            ->get();
        
        // Courriers with only 20 days left before deadline (excluding cloturé & archivé)
        $alertesRetard = Courrier::whereNotIn('statut', ['cloturé', 'archivé'])
            ->where('type_courrier', 'arrive')
            ->whereDate('delais', '=', Carbon::now()->addDays(20)->toDateString())
            ->with('expediteur')
            ->get();

        return view('dashboards.cab.index', compact(
            'totalCourriers',
            'courriersRecusCeMois',
            'courriersTraites',
            'repartitionStatut',
            'courriersParType',
            'evolutionMensuelle',
            'prioriteCourriers',
            'topExpediteurs',
            'alertesUrgents',
            'alertesRetard',
            'topCourrierInstructs'
        ));
    }
}