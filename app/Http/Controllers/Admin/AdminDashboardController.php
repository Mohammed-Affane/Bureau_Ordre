<?php

namespace App\Http\Controllers\Admin;

use App\Models\Courrier;
use App\Models\User;
use App\Models\Entite;
use App\Models\Affectation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CourrierDestinataire;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_courriers' => Courrier::count(),
            'courriers_arrive' => Courrier::where('type_courrier', 'arrive')->count(),
            'courriers_depart' => Courrier::where('type_courrier', 'depart')->count(),
            'courriers_interne' => Courrier::where('type_courrier', 'interne')->count(),
            'en_attente' => Courrier::where('statut', 'en_attente')->count(),
            'en_traitement' => Courrier::where('statut', 'en_traitement')->count(),
            'cloture' => Courrier::where('statut', 'cloture')->count(),
            'retard' => Courrier::whereDate('delais', '<', Carbon::now())
                ->whereNotIn('statut', ['cloture', 'archiver'])
                ->count(),
        ];

        // Statistiques par entité
        $entitesStats = Entite::all();
        
        // Calcul des courriers par entité
        foreach ($entitesStats as $entite) {
            // Courriers où l'entité est destinataire
            $entite->courriers_count = CourrierDestinataire::where('entite_id', $entite->id)
                ->count();
                
            // Courriers en retard où l'entité est destinataire
            $entite->courriers_en_retard_count = CourrierDestinataire::where('entite_id', $entite->id)
                ->whereHas('courrierDestinatairePivot', function($q) {
                    $q->whereDate('delais', '<', Carbon::now());
                })
                ->count();
        }

        // Statistiques par priorité
        $prioritesStats = Courrier::select('priorite', DB::raw('count(*) as total'))
            ->groupBy('priorite')
            ->get()
            ->pluck('total', 'priorite')
            ->toArray();

        // Courriers récents
        $recentCourriers = Courrier::with(['expediteur', 'entiteExpediteur'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Statistiques mensuelles pour graphiques
        $monthlyStats = $this->getMonthlyStats();

        // Utilisateurs les plus actifs
        $activeUsers = User::withCount(['affectationsRecues' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            }])
            ->orderBy('affectations_recues_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboards.admin.index', compact(
            'stats', 
            'entitesStats', 
            'prioritesStats', 
            'recentCourriers', 
            'monthlyStats',
            'activeUsers'
        ));
    }

    private function getMonthlyStats()
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthlyData = Courrier::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN type_courrier = "arrive" THEN 1 ELSE 0 END) as arrive'),
                DB::raw('SUM(CASE WHEN type_courrier = "depart" THEN 1 ELSE 0 END) as depart'),
                DB::raw('SUM(CASE WHEN type_courrier = "interne" THEN 1 ELSE 0 END) as interne')
            )
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $formattedData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            $monthName = $currentDate->format('M Y');
            
            $monthData = $monthlyData->first(function ($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            
            $formattedData[$monthName] = [
                'total' => $monthData ? $monthData->total : 0,
                'arrive' => $monthData ? $monthData->arrive : 0,
                'depart' => $monthData ? $monthData->depart : 0,
                'interne' => $monthData ? $monthData->interne : 0,
            ];
            
            $currentDate->addMonth();
        }
        
        return $formattedData;
    }
}
