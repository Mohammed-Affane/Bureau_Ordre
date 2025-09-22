<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\Affectation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SgDashboardController extends Controller
{
    public function index()
    {
        // Statistiques des courriers pour le SG
        $stats = [
            'total_courriers' => Courrier::whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_sg');
            })->count(),
            'courriers_arrive' => Courrier::where('type_courrier', 'arrive')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_sg');
                })->count(),
            'courriers_depart' => Courrier::where('type_courrier', 'depart')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_sg');
                })->count(),
            'courriers_interne' => Courrier::where('type_courrier', 'interne')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_sg');
                })->count(),
            'en_attente' => Courrier::where('statut', 'en_attente')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_sg');
                })->count(),
            'en_traitement' => Courrier::where('statut', 'en_traitement')
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_sg');
                })->count(),
            'retard' => Courrier::whereDate('delais', '<', Carbon::now())
                ->whereNotIn('statut', ['cloture', 'archiver'])
                ->whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_sg');
                })->count(),
        ];

        // Statistiques par priorité
        $prioritesStats = Courrier::select('priorite', DB::raw('count(*) as total'))
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_sg');
            })
            ->groupBy('priorite')
            ->get()
            ->pluck('total', 'priorite')
            ->toArray();

        // Courriers récents
        $recentCourriers = Courrier::with(['expediteur', 'entiteExpediteur'])
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_sg');
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Statistiques mensuelles pour graphiques
        $monthlyStats = $this->getMonthlyStats();

        return view('dashboards.sg.index', compact(
            'stats', 
            'prioritesStats', 
            'recentCourriers', 
            'monthlyStats'
        ));
    }

    private function getMonthlyStats()
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $monthlyData = Courrier::select(
                DB::raw('YEAR(courriers.created_at) as year'),
                DB::raw('MONTH(courriers.created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN courriers.type_courrier = "arrive" THEN 1 ELSE 0 END) as arrive'),
                DB::raw('SUM(CASE WHEN courriers.type_courrier = "depart" THEN 1 ELSE 0 END) as depart'),
                DB::raw('SUM(CASE WHEN courriers.type_courrier = "interne" THEN 1 ELSE 0 END) as interne')
            )
            ->join('affectations', 'courriers.id', '=', 'affectations.id_courrier')
            ->where('affectations.statut_affectation', 'a_sg')
            ->where('courriers.created_at', '>=', $startDate)
            ->where('courriers.created_at', '<=', $endDate)
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