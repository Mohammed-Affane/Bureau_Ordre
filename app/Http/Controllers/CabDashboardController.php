<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\Affectation;
use App\Models\Traitement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabDashboardController extends Controller
{
    public function index()
    {
        // Core Statistics - TOUS les courriers
        $stats = $this->getCoreStatistics();
        
        // Performance Metrics - TOUS les courriers
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // Priority & Status Distribution - TOUS les courriers
        $distributionStats = $this->getDistributionStatistics();
        
        // Recent Activity & Critical Items - TOUS les courriers
        $activityData = $this->getActivityData();
        
        // Temporal Analytics - TOUS les courriers
        $temporalData = $this->getTemporalAnalytics();
        
        // Chart Data - TOUS les courriers
        $chartData = $this->getChartData();

        return view('dashboards.cab.index', compact(
            'stats',
            'performanceMetrics', 
            'distributionStats',
            'activityData',
            'temporalData',
            'chartData'
        ));
    }

    private function getCoreStatistics()
    {
        return [
            // Volume Metrics - TOUS les courriers
            'total_courriers' => Courrier::count(),
            'courriers_aujourd_hui' => Courrier::whereDate('created_at', Carbon::today())->count(),
            'courriers_semaine' => Courrier::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'courriers_mois' => Courrier::whereMonth('created_at', Carbon::now()->month)->count(),

            // Type Distribution - TOUS les courriers
            'courriers_arrive' => Courrier::where('type_courrier', 'arrive')->count(),
            'courriers_depart' => Courrier::where('type_courrier', 'depart')->count(),
            'courriers_interne' => Courrier::where('type_courrier', 'interne')->count(),
            'courriers_visa' => Courrier::where('type_courrier', 'visa')->count(),
            'courriers_decision' => Courrier::where('type_courrier', 'decision')->count(),

            // Status Metrics - TOUS les courriers
            'en_attente' => Courrier::where('statut', 'en_attente')->count(),
            'en_cours' => Courrier::where('statut', 'en_cours')->count(),
            'en_traitement' => Courrier::where('statut', 'en_traitement')->count(),
            'traites' => Courrier::where('statut', 'traite')->count(),
            'clotures' => Courrier::where('statut', 'cloture')->count(),

            // Critical Metrics - TOUS les courriers
            'urgents' => Courrier::where('priorite', 'urgent')->count(),
            'confidentiels' => Courrier::where('priorite', 'confidentiel')->count(),
            'reponse_obligatoire' => Courrier::where('priorite', 'A reponse obligatoire')->count(),
            'en_retard' => Courrier::whereDate('delais', '<', Carbon::now())
                ->whereNotIn('statut', ['cloture', 'archiver'])->count(),
        ];
    }

    private function getPerformanceMetrics()
    {
        // Calculate average processing time - TOUS les traitements
        $avgProcessingTime = DB::table('affectations')
            ->join('traitements', 'affectations.id', '=', 'traitements.id_affectation')
            ->whereNotNull('traitements.date_traitement')
            ->whereNotNull('affectations.date_affectation')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, affectations.date_affectation, traitements.date_traitement)) as avg_hours'))
            ->first();

        // Global statistics - TOUS les courriers
        $totalCourriers = Courrier::count();
        $completedCourriers = Courrier::where('statut', 'cloture')->count();
        $onTimeCourriers = Courrier::where('statut', 'cloture')
            ->whereColumn('delais', '>=', DB::raw('DATE(updated_at)'))
            ->count();

        // Response rate calculation (taux de traitement)
        $totalAffected = Affectation::count();
        $totalTreated = Affectation::whereHas('traitements')->count();

        return [
            'avg_processing_hours' => $avgProcessingTime->avg_hours ?? 0,
            'completion_rate' => $totalCourriers > 0 ? round(($completedCourriers / $totalCourriers) * 100, 1) : 0,
            'on_time_rate' => $completedCourriers > 0 ? round(($onTimeCourriers / $completedCourriers) * 100, 1) : 0,
            'response_rate' => $totalAffected > 0 ? round(($totalTreated / $totalAffected) * 100, 1) : 0, // Ajout de response_rate
            'productivity_score' => $this->calculateProductivityScore(),
        ];
    }

    private function getDistributionStatistics()
    {
        // Priority distribution - TOUS les courriers
        $priorityStats = Courrier::select('priorite', DB::raw('count(*) as total'))
            ->groupBy('priorite')
            ->get()
            ->pluck('total', 'priorite')
            ->toArray();

        // Status distribution - TOUS les courriers
        $statusStats = Courrier::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get()
            ->pluck('total', 'statut')
            ->toArray();

        // Type distribution - TOUS les courriers
        $typeStats = Courrier::select('type_courrier', DB::raw('count(*) as total'))
            ->groupBy('type_courrier')
            ->get()
            ->pluck('total', 'type_courrier')
            ->toArray();

        return [
            'priority' => $priorityStats,
            'status' => $statusStats,
            'type' => $typeStats,
        ];
    }

    private function getChartData()
    {
        // Monthly data for line chart - TOUS les courriers
        $monthlyData = Courrier::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN priorite = "urgent" THEN 1 ELSE 0 END) as urgent'),
                DB::raw('SUM(CASE WHEN type_courrier = "arrive" THEN 1 ELSE 0 END) as arrive'),
                DB::raw('SUM(CASE WHEN type_courrier = "depart" THEN 1 ELSE 0 END) as depart'),
                DB::raw('SUM(CASE WHEN type_courrier = "interne" THEN 1 ELSE 0 END) as interne')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Priority distribution for doughnut chart - TOUS les courriers
        $priorityData = Courrier::select('priorite', DB::raw('COUNT(*) as total'))
            ->groupBy('priorite')
            ->get();

        // Status distribution for bar chart - TOUS les courriers
        $statusData = Courrier::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();

        // Type distribution for pie chart - TOUS les courriers
        $typeData = Courrier::select('type_courrier', DB::raw('COUNT(*) as total'))
            ->groupBy('type_courrier')
            ->get();

        return [
            'monthly' => $monthlyData,
            'priority' => $priorityData,
            'status' => $statusData,
            'type' => $typeData,
        ];
    }

    private function getActivityData()
    {
        // Critical items (expired and urgent) - TOUS les courriers
        $criticalItems = Courrier::with(['expediteur', 'entiteExpediteur'])
            ->where(function($query) {
                $query->whereDate('delais', '<', Carbon::now())
                      ->orWhere('priorite', 'urgent');
            })
            ->whereNotIn('statut', ['cloture', 'archiver'])
            ->orderBy('delais')
            ->limit(10)
            ->get();

        // Today's activity - TOUS les courriers
        $todayActivity = [
            'received' => Courrier::whereDate('created_at', Carbon::today())->count(),
            'processed' => Traitement::whereDate('date_traitement', Carbon::today())->count(),
            'completed' => Courrier::whereDate('updated_at', Carbon::today())
                ->where('statut', 'cloture')->count(),
        ];

        // Recent courriers - TOUS les courriers
        $recentCourriers = Courrier::with(['expediteur', 'entiteExpediteur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'critical_items' => $criticalItems,
            'today_activity' => $todayActivity,
            'recent_courriers' => $recentCourriers,
        ];
    }

    private function getTemporalAnalytics()
    {
        // Weekly trends - TOUS les courriers
        $weeklyTrends = Courrier::select(
                DB::raw('WEEK(created_at) as week'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN type_courrier = "arrive" THEN 1 ELSE 0 END) as arrive'),
                DB::raw('SUM(CASE WHEN type_courrier = "depart" THEN 1 ELSE 0 END) as depart')
            )
            ->whereBetween('created_at', [Carbon::now()->subWeeks(4), Carbon::now()])
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return [
            'weekly_trend' => 'up',
            'monthly_growth' => 12.5,
            'weekly_data' => $weeklyTrends,
        ];
    }

    private function calculateProductivityScore()
    {
        // Global productivity calculation - TOUS les courriers
        $completionRate = $this->getCompletionRate();
        $timeliness = $this->getTimelinessRate();
        $efficiency = $this->getEfficiencyRate();
        
        return round(($completionRate * 0.4 + $timeliness * 0.3 + $efficiency * 0.3), 1);
    }

    private function getCompletionRate()
    {
        $total = Courrier::count();
        $completed = Courrier::where('statut', 'cloture')->count();
        
        return $total > 0 ? ($completed / $total) * 100 : 0;
    }

    private function getTimelinessRate()
    {
        $onTime = Courrier::where('statut', 'cloture')
            ->whereColumn('delais', '>=', DB::raw('DATE(updated_at)'))
            ->count();

        $totalCompleted = Courrier::where('statut', 'cloture')->count();

        return $totalCompleted > 0 ? ($onTime / $totalCompleted) * 100 : 100;
    }

    private function getEfficiencyRate()
    {
        // Calculate based on processing time and backlog
        $avgProcessingTime = DB::table('affectations')
            ->join('traitements', 'affectations.id', '=', 'traitements.id_affectation')
            ->whereNotNull('traitements.date_traitement')
            ->whereNotNull('affectations.date_affectation')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, affectations.date_affectation, traitements.date_traitement)) as avg_hours'))
            ->first();

        $backlog = Courrier::whereNotIn('statut', ['cloture', 'archiver'])->count();
        $total = Courrier::count();

        $processingScore = $avgProcessingTime->avg_hours <= 24 ? 100 : max(0, 100 - (($avgProcessingTime->avg_hours - 24) / 24 * 100));
        $backlogScore = $total > 0 ? max(0, 100 - ($backlog / $total * 100)) : 100;

        return ($processingScore * 0.6 + $backlogScore * 0.4);
    }

    // API endpoints
    public function getRealtimeStats()
    {
        return response()->json([
            'timestamp' => now()->toISOString(),
            'stats' => $this->getCoreStatistics(),
            'performance' => $this->getPerformanceMetrics(),
        ]);
    }

    public function getChartDataApi()
    {
        $chartData = $this->getChartData();
        
        return response()->json([
            'monthly' => [
                'labels' => $chartData['monthly']->map(function($item) {
                    return Carbon::create($item->year, $item->month)->format('M Y');
                }),
                'total' => $chartData['monthly']->pluck('total'),
                'urgent' => $chartData['monthly']->pluck('urgent'),
                'arrive' => $chartData['monthly']->pluck('arrive'),
                'depart' => $chartData['monthly']->pluck('depart'),
                'interne' => $chartData['monthly']->pluck('interne'),
            ],
            'priority' => [
                'labels' => $chartData['priority']->pluck('priorite'),
                'data' => $chartData['priority']->pluck('total'),
            ],
            'status' => [
                'labels' => $chartData['status']->pluck('statut'),
                'data' => $chartData['status']->pluck('total'),
            ],
            'type' => [
                'labels' => $chartData['type']->pluck('type_courrier'),
                'data' => $chartData['type']->pluck('total'),
            ],
        ]);
    }

    // Nouvelle méthode pour voir tous les courriers
    public function allCourriers(Request $request)
    {
        $query = Courrier::with(['expediteur', 'entiteExpediteur', 'affectations'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('type') && $request->type) {
            $query->where('type_courrier', $request->type);
        }

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('priorite') && $request->priorite) {
            $query->where('priorite', $request->priorite);
        }

        $courriers = $query->paginate(20);

        return view('dashboards.cab.all-courriers', compact('courriers'));
    }
}