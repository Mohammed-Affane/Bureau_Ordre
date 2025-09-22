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
        // Core Statistics
        $stats = $this->getCoreStatistics();
        
        // Performance Metrics
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // Priority & Status Distribution
        $distributionStats = $this->getDistributionStatistics();
        
        // Recent Activity & Critical Items
        $activityData = $this->getActivityData();
        
        // Temporal Analytics
        $temporalData = $this->getTemporalAnalytics();
        
        // Department Performance
        $departmentPerformance = $this->getDepartmentPerformance();
        
        return view('dashboards.cab.index', compact(
            'stats',
            'performanceMetrics', 
            'distributionStats',
            'activityData',
            'temporalData',
            'departmentPerformance'
        ));
    }

    private function getCoreStatistics()
    {
        return [
            // Volume Metrics
            'total_courriers' => Courrier::count(),
            'courriers_aujourd_hui' => Courrier::whereDate('created_at', Carbon::today())->count(),
            'courriers_semaine' => Courrier::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'courriers_mois' => Courrier::whereMonth('created_at', Carbon::now()->month)->count(),

            // Type Distribution
            'courriers_arrive' => Courrier::where('type_courrier', 'arrive')->count(),
            'courriers_depart' => Courrier::where('type_courrier', 'depart')->count(),
            'courriers_interne' => Courrier::where('type_courrier', 'interne')->count(),
            'courriers_visa' => Courrier::where('type_courrier', 'visa')->count(),
            'courriers_decision' => Courrier::where('type_courrier', 'decision')->count(),

            // Status Metrics
            'en_attente' => Courrier::where('statut', 'en_attente')->count(),
            'en_traitement' => Courrier::where('statut', 'en_traitement')->count(),
            'en_cours' => Courrier::where('statut', 'en_cours')->count(),
            'clotures' => Courrier::where('statut', 'cloture')->count(),

            // Critical Metrics
            'urgents' => Courrier::where('priorite', 'urgent')->count(),
            'confidentiels' => Courrier::where('priorite', 'confidentiel')->count(),
            'reponse_obligatoire' => Courrier::where('priorite', 'A reponse obligatoire')->count(),
            'en_retard' => Courrier::whereDate('delais', '<', Carbon::now())
                ->whereNotIn('statut', ['cloture', 'archiver'])->count(),
        ];

    }

    private function getPerformanceMetrics()
    {
        // Average processing time
        $avgProcessingTime = DB::table('affectations')
            ->join('traitements', 'affectations.id', '=', 'traitements.id_affectation')
            ->where('affectations.statut_affectation', 'a_cab')
            ->whereNotNull('traitements.date_traitement')
            ->whereNotNull('affectations.date_affectation')
            ->select(DB::raw('AVG(DATEDIFF(traitements.date_traitement, affectations.date_affectation)) as avg_days'))
            ->first();

        // Response rate
        $totalAffected = Affectation::where('statut_affectation', 'a_cab')->count();
        $totalTreated = Affectation::where('statut_affectation', 'a_cab')
            ->whereHas('traitements', function($query) {
                $query->where('statut', '!=', 'brouillon');
            })->count();

        // Efficiency metrics
        $onTimeCompletion = Courrier::whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->where('statut', 'cloture')
            ->whereColumn('delais', '>=', DB::raw('DATE(updated_at)'))
            ->count();

        $totalCompleted = Courrier::whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->where('statut', 'cloture')
            ->count();

        return [
            'avg_processing_days' => $avgProcessingTime->avg_days ?? 0,
            'response_rate' => $totalAffected > 0 ? round(($totalTreated / $totalAffected) * 100, 1) : 0,
            'on_time_rate' => $totalCompleted > 0 ? round(($onTimeCompletion / $totalCompleted) * 100, 1) : 0,
            'productivity_score' => $this->calculateProductivityScore(),
        ];
    }

    private function getDistributionStatistics()
    {
        // Priority distribution
        $priorityStats = Courrier::select('priorite', DB::raw('count(*) as total'))
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->groupBy('priorite')
            ->get()
            ->pluck('total', 'priorite')
            ->toArray();

        // Status distribution with percentages
        $statusStats = Courrier::select('statut', DB::raw('count(*) as total'))
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->groupBy('statut')
            ->get()
            ->pluck('total', 'statut')
            ->toArray();

        // Type distribution
        $typeStats = Courrier::select('type_courrier', DB::raw('count(*) as total'))
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
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

private function getActivityData()
{
    // Critical items needing immediate attention - only expired ones
    $criticalItems = Courrier::with(['expediteur', 'entiteExpediteur'])
        ->whereHas('affectations', function($query) {
            $query->where('statut_affectation', 'a_cab');
        })
        ->whereDate('delais', '<', Carbon::now())
        ->whereNotIn('statut', ['cloture', 'archiver'])
        ->orderBy('delais')
        ->paginate(5); 

    // Today's activity summary
    $todayActivity = [
        'received' => Courrier::whereDate('created_at', Carbon::today())->count(),
        'processed' => Affectation::whereDate('created_at', Carbon::today())
            ->where('statut_affectation', 'a_cab')->count(),
        'completed' => Courrier::whereDate('updated_at', Carbon::today())
            ->where('statut', 'cloture')->count(),
    ];

    return [
        'critical_items' => $criticalItems,
        'today_activity' => $todayActivity,
    ];
}

    private function getTemporalAnalytics()
    {
        // Last 6 months data
        $monthlyData = $this->getEnhancedMonthlyStats();
        
        // Weekly trends
        $weeklyTrends = $this->getWeeklyTrends();
        
        // Daily patterns
        $dailyPatterns = $this->getDailyPatterns();

        return [
            'monthly' => $monthlyData,
            'weekly' => $weeklyTrends,
            'daily' => $dailyPatterns,
        ];
    }

    private function getDepartmentPerformance()
    {
        return DB::table('affectations')
            ->join('users', 'affectations.id_affecte_a_utilisateur', '=', 'users.id')
            ->leftJoin('traitements', 'affectations.id', '=', 'traitements.id_affectation')
            ->where('affectations.statut_affectation', 'a_cab')
            ->select(
                'users.name as department',
                DB::raw('COUNT(affectations.id) as total_assigned'),
                DB::raw('COUNT(traitements.id) as total_processed'),
                DB::raw('AVG(CASE WHEN traitements.date_traitement IS NOT NULL 
                    THEN DATEDIFF(traitements.date_traitement, affectations.date_affectation) 
                    ELSE NULL END) as avg_processing_time')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_assigned', 'desc')
            ->get();
    }

    private function getEnhancedMonthlyStats()
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        return Courrier::select(
                DB::raw('YEAR(courriers.created_at) as year'),
                DB::raw('MONTH(courriers.created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN courriers.priorite = "urgent" THEN 1 ELSE 0 END) as urgent'),
                DB::raw('SUM(CASE WHEN courriers.statut = "cloture" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN courriers.delais < NOW() AND courriers.statut NOT IN ("cloture", "archiver") THEN 1 ELSE 0 END) as overdue')
            )
            ->join('affectations', 'courriers.id', '=', 'affectations.id_courrier')
            ->where('affectations.statut_affectation', 'a_cab')
            ->whereBetween('courriers.created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    private function getWeeklyTrends()
    {
        return Courrier::select(
                DB::raw('WEEK(created_at) as week'),
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(CASE WHEN priorite = "urgent" THEN 1 ELSE 0 END * 100) as urgent_percentage')
            )
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->whereBetween('created_at', [Carbon::now()->subWeeks(4), Carbon::now()])
            ->groupBy('week')
            ->orderBy('week')
            ->get();
    }

    private function getDailyPatterns()
    {
        return Courrier::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    private function calculateProductivityScore()
    {
        // Complex calculation based on multiple factors
        $completionRate = $this->getCompletionRate();
        $timeEfficiency = $this->getTimeEfficiency();
        $qualityScore = $this->getQualityScore();
        
        return round(($completionRate * 0.4 + $timeEfficiency * 0.3 + $qualityScore * 0.3), 1);
    }

    private function getCompletionRate()
    {
        $total = Courrier::whereHas('affectations', function($query) {
            $query->where('statut_affectation', 'a_cab');
        })->count();
        
        $completed = Courrier::whereHas('affectations', function($query) {
            $query->where('statut_affectation', 'a_cab');
        })->where('statut', 'cloture')->count();
        
        return $total > 0 ? ($completed / $total) * 100 : 0;
    }

    private function getTimeEfficiency()
    {
        $onTime = Courrier::whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->where('statut', 'cloture')
            ->whereColumn('delais', '>=', DB::raw('DATE(updated_at)'))
            ->count();

        $total = Courrier::whereHas('affectations', function($query) {
                $query->where('statut_affectation', 'a_cab');
            })
            ->where('statut', 'cloture')
            ->count();

        return $total > 0 ? ($onTime / $total) * 100 : 0;
    }

    private function getQualityScore()
    {
        // This would be based on feedback, revisions, etc.
        // For now, return a calculated score based on available data
        return 85; // Placeholder - implement based on actual quality metrics
    }

    // API endpoints for real-time updates
    public function getRealtimeStats()
    {
        return response()->json([
            'timestamp' => now()->toISOString(),
            'stats' => $this->getCoreStatistics(),
            'critical_count' => Courrier::whereHas('affectations', function($query) {
                    $query->where('statut_affectation', 'a_cab');
                })
                ->where('priorite', 'urgent')
                ->where('statut', '!=', 'cloture')
                ->count()
        ]);
    }

    public function exportReport(Request $request)
    {
        // Implementation for exporting dashboard data
        // This would generate PDF/Excel reports
    }
}